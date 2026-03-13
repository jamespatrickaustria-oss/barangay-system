<style>
    .chat-fab {
        position: fixed;
        right: 20px;
        bottom: 20px;
        width: 58px;
        height: 58px;
        border: none;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        box-shadow: var(--shadow-lg);
        z-index: 1200;
    }

    .chat-panel {
        position: fixed;
        right: 20px;
        bottom: 90px;
        width: 360px;
        max-width: calc(100vw - 24px);
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        box-shadow: var(--shadow-xl);
        display: none;
        flex-direction: column;
        overflow: hidden;
        z-index: 1201;
        height: 520px;
        max-height: 75vh;
    }

    .chat-panel.open {
        display: flex;
    }

    .chat-header {
        background: var(--primary);
        color: #fff;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-title {
        font-size: 14px;
        font-weight: 700;
    }

    .chat-subtitle {
        font-size: 12px;
        opacity: 0.9;
    }

    .chat-close {
        border: none;
        background: transparent;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 14px;
        background: var(--gray-50);
    }

    .chat-empty {
        font-size: 13px;
        color: var(--gray-500);
        text-align: center;
        margin-top: 24px;
    }

    .chat-item {
        max-width: 82%;
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .chat-item.mine {
        margin-left: auto;
        align-items: flex-end;
    }

    .chat-bubble {
        border-radius: 12px;
        padding: 10px 12px;
        font-size: 13px;
        line-height: 1.45;
        box-shadow: var(--shadow-sm);
        background: #fff;
        border: 1px solid var(--gray-200);
        word-break: break-word;
    }

    .chat-item.mine .chat-bubble {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .chat-meta {
        font-size: 10px;
        color: var(--gray-500);
    }

    .chat-image {
        max-width: 180px;
        border-radius: 10px;
        border: 1px solid var(--gray-200);
    }

    .chat-form {
        border-top: 1px solid var(--gray-200);
        padding: 10px;
        display: flex;
        gap: 8px;
        align-items: center;
        background: #fff;
    }

    .chat-input {
        flex: 1;
        border: 1px solid var(--gray-300);
        border-radius: 10px;
        padding: 9px 10px;
        font-size: 13px;
        font-family: inherit;
    }

    .chat-file {
        width: 36px;
        position: relative;
        overflow: hidden;
    }

    .chat-file input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .chat-file-label,
    .chat-send {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .chat-file-label {
        background: var(--gray-100);
    }

    .chat-send {
        background: var(--secondary);
        color: #fff;
    }

    @media (max-width: 640px) {
        .chat-panel {
            right: 12px;
            left: 12px;
            width: auto;
            max-width: none;
            bottom: 84px;
            height: 72vh;
        }

        .chat-fab {
            right: 12px;
            bottom: 12px;
        }
    }
</style>

<button class="chat-fab" id="chatFab" title="Chat with Barangay Officials">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
</button>

<div class="chat-panel" id="chatPanel">
    <div class="chat-header">
        <div>
            <div class="chat-title">Resident Support Chat</div>
            <div class="chat-subtitle">Talk directly with barangay officials</div>
        </div>
        <button class="chat-close" id="chatClose">×</button>
    </div>
    <div class="chat-messages" id="residentChatMessages">
        <div class="chat-empty">Start a conversation with barangay officials.</div>
    </div>
    <form class="chat-form" id="residentChatForm">
        <label class="chat-file chat-file-label" title="Upload image">
            📷
            <input type="file" id="residentChatImage" accept="image/*">
        </label>
        <input type="text" class="chat-input" id="residentChatInput" maxlength="2000" placeholder="Type a message...">
        <button type="submit" class="chat-send" title="Send">➤</button>
    </form>
</div>

<script>
    (() => {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const panel = document.getElementById('chatPanel');
        const fab = document.getElementById('chatFab');
        const closeBtn = document.getElementById('chatClose');
        const messageBox = document.getElementById('residentChatMessages');
        const form = document.getElementById('residentChatForm');
        const input = document.getElementById('residentChatInput');
        const imageInput = document.getElementById('residentChatImage');

        let lastMessageId = 0;
        let pollTimer = null;

        const renderMessage = (message) => {
            const wrapper = document.createElement('div');
            wrapper.className = `chat-item ${message.is_mine ? 'mine' : ''}`;

            const bubble = document.createElement('div');
            bubble.className = 'chat-bubble';

            if (message.body) {
                const text = document.createElement('div');
                text.textContent = message.body;
                bubble.appendChild(text);
            }

            if (message.image_url) {
                const image = document.createElement('img');
                image.src = message.image_url;
                image.className = 'chat-image';
                image.alt = 'chat image';
                bubble.appendChild(image);
            }

            const meta = document.createElement('div');
            meta.className = 'chat-meta';
            meta.textContent = `${message.sender_name} • ${message.created_at_human}`;

            wrapper.appendChild(bubble);
            wrapper.appendChild(meta);
            messageBox.appendChild(wrapper);

            lastMessageId = Math.max(lastMessageId, Number(message.id || 0));
        };

        const scrollBottom = () => {
            messageBox.scrollTop = messageBox.scrollHeight;
        };

        const loadThread = async () => {
            const response = await fetch('{{ route('resident.chat.thread') }}', {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            messageBox.innerHTML = '';

            if (!data.messages?.length) {
                messageBox.innerHTML = '<div class="chat-empty">Start a conversation with barangay officials.</div>';
                lastMessageId = 0;
                return;
            }

            data.messages.forEach(renderMessage);
            scrollBottom();
        };

        const pollMessages = async () => {
            const response = await fetch(`{{ route('resident.chat.messages') }}?after_id=${lastMessageId}`, {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            if (!data.messages?.length) {
                return;
            }

            if (messageBox.querySelector('.chat-empty')) {
                messageBox.innerHTML = '';
            }

            data.messages.forEach(renderMessage);
            scrollBottom();
        };

        const sendMessage = async () => {
            const body = input.value.trim();
            const hasImage = imageInput.files.length > 0;

            if (!body && !hasImage) {
                return;
            }

            const formData = new FormData();
            if (body) formData.append('body', body);
            if (hasImage) formData.append('image', imageInput.files[0]);

            const response = await fetch('{{ route('resident.chat.send') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            if (messageBox.querySelector('.chat-empty')) {
                messageBox.innerHTML = '';
            }

            renderMessage(data.message);
            scrollBottom();

            input.value = '';
            imageInput.value = '';
        };

        const openPanel = async () => {
            panel.classList.add('open');
            await loadThread();

            if (!pollTimer) {
                pollTimer = setInterval(pollMessages, 3000);
            }
        };

        const closePanel = () => {
            panel.classList.remove('open');
        };

        fab?.addEventListener('click', openPanel);
        closeBtn?.addEventListener('click', closePanel);

        form?.addEventListener('submit', async (event) => {
            event.preventDefault();
            await sendMessage();
        });

        window.addEventListener('beforeunload', () => {
            if (pollTimer) clearInterval(pollTimer);
        });
    })();
</script>
