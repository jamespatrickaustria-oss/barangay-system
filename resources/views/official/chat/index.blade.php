@extends('layouts.app')

@section('title', 'Resident Chat')

@section('content')
<style>
    .chat-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 16px;
        height: calc(100vh - 180px);
        min-height: 520px;
    }

    .thread-list,
    .thread-panel {
        background: #fff;
        border: 1px solid #c8e4f8;
        border-radius: 12px;
        overflow: hidden;
    }

    .thread-header {
        padding: 14px 16px;
        border-bottom: 1px solid #c8e4f8;
        font-size: 14px;
        font-weight: 700;
    }

    .thread-items {
        overflow-y: auto;
        height: calc(100% - 50px);
    }

    .thread-item {
        padding: 12px 14px;
        border-bottom: 1px solid #e6f2fb;
        cursor: pointer;
    }

    .thread-item:hover,
    .thread-item.active {
        background: #eaf5ff;
    }

    .thread-name {
        font-weight: 600;
        font-size: 13px;
        color: #0d1b2a;
    }

    .thread-preview {
        margin-top: 4px;
        font-size: 12px;
        color: #5a7a9a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .thread-time {
        margin-top: 4px;
        font-size: 11px;
        color: #8aa7c2;
    }

    .messages-area {
        height: calc(100% - 122px);
        overflow-y: auto;
        padding: 16px;
        background: #f6fbff;
    }

    .chat-message {
        max-width: 70%;
        margin-bottom: 12px;
    }

    .chat-message.mine {
        margin-left: auto;
    }

    .msg-bubble {
        background: #fff;
        border: 1px solid #d9ecfb;
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 13px;
        color: #0d1b2a;
        line-height: 1.45;
        word-break: break-word;
    }

    .chat-message.mine .msg-bubble {
        background: #1a6fcc;
        border-color: #1a6fcc;
        color: #fff;
    }

    .msg-meta {
        margin-top: 4px;
        font-size: 10px;
        color: #6c8cab;
    }

    .msg-image {
        max-width: 220px;
        border-radius: 10px;
        border: 1px solid #d9ecfb;
        margin-top: 6px;
        display: block;
    }

    .chat-compose {
        border-top: 1px solid #c8e4f8;
        padding: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chat-compose input[type='text'] {
        flex: 1;
        border: 1px solid #c8e4f8;
        border-radius: 10px;
        padding: 10px;
        font-size: 13px;
        font-family: inherit;
    }

    .btn-icon,
    .btn-send {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
    }

    .btn-icon {
        position: relative;
        overflow: hidden;
        background: #eef6fe;
    }

    .btn-icon input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .btn-send {
        background: #3a8a3f;
        color: #fff;
    }

    .empty-note {
        color: #6c8cab;
        text-align: center;
        font-size: 13px;
        margin-top: 36px;
    }

    @media (max-width: 980px) {
        .chat-layout {
            grid-template-columns: 1fr;
            height: auto;
        }

        .thread-list {
            height: 260px;
        }

        .thread-panel {
            min-height: 520px;
        }
    }
</style>

<div class="chat-layout">
    <section class="thread-list">
        <div class="thread-header">Resident Conversations</div>
        <div class="thread-items" id="threadItems">
            <div class="empty-note">No resident messages yet.</div>
        </div>
    </section>

    <section class="thread-panel">
        <div class="thread-header" id="selectedThreadLabel">Select a conversation</div>
        <div class="messages-area" id="officialMessages">
            <div class="empty-note">Choose a resident thread to start chatting.</div>
        </div>
        <form class="chat-compose" id="officialChatForm">
            <label class="btn-icon" title="Upload image">📷
                <input type="file" id="officialChatImage" accept="image/*">
            </label>
            <input type="text" id="officialChatInput" maxlength="2000" placeholder="Type a reply...">
            <button type="submit" class="btn-send">➤</button>
        </form>
    </section>
</div>

<script>
    (() => {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const threadItems = document.getElementById('threadItems');
        const selectedThreadLabel = document.getElementById('selectedThreadLabel');
        const messagesEl = document.getElementById('officialMessages');
        const form = document.getElementById('officialChatForm');
        const input = document.getElementById('officialChatInput');
        const imageInput = document.getElementById('officialChatImage');

        let threads = [];
        let activeThreadId = null;
        let lastMessageId = 0;

        const renderThreads = () => {
            if (!threads.length) {
                threadItems.innerHTML = '<div class="empty-note">No resident messages yet.</div>';
                return;
            }

            threadItems.innerHTML = '';
            threads.forEach((thread) => {
                const item = document.createElement('div');
                item.className = `thread-item ${thread.id === activeThreadId ? 'active' : ''}`;
                item.innerHTML = `
                    <div class="thread-name">${thread.resident_name}</div>
                    <div class="thread-preview">${thread.latest_preview ?? ''}</div>
                    <div class="thread-time">${thread.last_message_at_human ?? ''}</div>
                `;
                item.addEventListener('click', async () => {
                    activeThreadId = thread.id;
                    lastMessageId = 0;
                    renderThreads();
                    await loadMessages();
                });
                threadItems.appendChild(item);
            });
        };

        const appendMessage = (message) => {
            const wrap = document.createElement('div');
            wrap.className = `chat-message ${message.is_mine ? 'mine' : ''}`;

            const bubble = document.createElement('div');
            bubble.className = 'msg-bubble';

            if (message.body) {
                const body = document.createElement('div');
                body.textContent = message.body;
                bubble.appendChild(body);
            }

            if (message.image_url) {
                const image = document.createElement('img');
                image.src = message.image_url;
                image.className = 'msg-image';
                image.alt = 'chat image';
                bubble.appendChild(image);
            }

            const meta = document.createElement('div');
            meta.className = 'msg-meta';
            meta.textContent = `${message.sender_name} • ${message.created_at_human}`;

            wrap.appendChild(bubble);
            wrap.appendChild(meta);
            messagesEl.appendChild(wrap);

            lastMessageId = Math.max(lastMessageId, Number(message.id || 0));
        };

        const scrollBottom = () => {
            messagesEl.scrollTop = messagesEl.scrollHeight;
        };

        const loadThreads = async () => {
            const response = await fetch('{{ route('official.chat.threads') }}', {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();
            threads = data.threads || [];

            if (!activeThreadId && threads.length) {
                activeThreadId = threads[0].id;
            }

            renderThreads();

            if (activeThreadId) {
                await loadMessages();
            }
        };

        const loadMessages = async () => {
            if (!activeThreadId) return;

            const response = await fetch(`/official/chat/threads/${activeThreadId}/messages?after_id=0`, {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();

            selectedThreadLabel.textContent = data.resident_name || 'Resident Conversation';
            messagesEl.innerHTML = '';
            lastMessageId = 0;

            if (!data.messages?.length) {
                messagesEl.innerHTML = '<div class="empty-note">No messages in this thread yet.</div>';
                return;
            }

            data.messages.forEach(appendMessage);
            scrollBottom();
        };

        const pollMessages = async () => {
            if (!activeThreadId) return;

            const response = await fetch(`/official/chat/threads/${activeThreadId}/messages?after_id=${lastMessageId}`, {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) return;

            const data = await response.json();
            if (!data.messages?.length) return;

            if (messagesEl.querySelector('.empty-note')) {
                messagesEl.innerHTML = '';
            }

            data.messages.forEach(appendMessage);
            scrollBottom();
            await loadThreads();
        };

        const sendMessage = async () => {
            if (!activeThreadId) return;

            const body = input.value.trim();
            const hasImage = imageInput.files.length > 0;
            if (!body && !hasImage) return;

            const payload = new FormData();
            if (body) payload.append('body', body);
            if (hasImage) payload.append('image', imageInput.files[0]);

            const response = await fetch(`/official/chat/threads/${activeThreadId}/messages`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: payload,
            });

            if (!response.ok) return;

            const data = await response.json();

            if (messagesEl.querySelector('.empty-note')) {
                messagesEl.innerHTML = '';
            }

            appendMessage(data.message);
            scrollBottom();
            input.value = '';
            imageInput.value = '';
            await loadThreads();
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            await sendMessage();
        });

        loadThreads();
        setInterval(pollMessages, 3000);
        setInterval(loadThreads, 7000);
    })();
</script>
@endsection
