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
        transition: all 0.3s ease;
    }

    .chat-fab:hover {
        transform: scale(1.1);
        box-shadow: var(--shadow-xl);
    }

    .unread-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
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

    .chat-form-wrap {
        border-top: 1px solid var(--gray-200);
        background: #fff;
    }

    .resident-preview {
        display: none;
        margin: 10px 10px 0;
        padding: 10px;
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        background: var(--gray-50);
        align-items: center;
        gap: 10px;
    }

    .resident-preview.show {
        display: flex;
    }

    .resident-preview img {
        width: 68px;
        height: 68px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid var(--gray-200);
        background: #fff;
    }

    .resident-preview-copy {
        min-width: 0;
        flex: 1;
    }

    .resident-preview-title {
        font-size: 12px;
        font-weight: 700;
        color: var(--gray-700);
    }

    .resident-preview-name {
        margin-top: 3px;
        font-size: 11px;
        color: var(--gray-500);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .resident-preview-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .resident-preview-btn {
        border: 1px solid var(--gray-200);
        background: #fff;
        border-radius: 8px;
        font-size: 11px;
        padding: 6px 9px;
        cursor: pointer;
        color: var(--gray-700);
    }

    .resident-preview-btn.send {
        background: var(--secondary);
        border-color: var(--secondary);
        color: #fff;
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
        min-width: 104px;
        width: auto;
        padding: 0 12px;
        background: var(--secondary);
        color: #fff;
        transition: background-color 0.2s ease, opacity 0.2s ease;
    }

    .chat-send:disabled {
        background: #98b59d;
        cursor: not-allowed;
        opacity: 0.8;
    }

    .chat-send-label {
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    @media (max-width: 640px) {
        .chat-panel {
            right: 12px;
            left: 12px;
            width: auto;
            max-width: none;
            bottom: 132px;
            height: 72vh;
        }

        .chat-fab {
            right: 12px;
            bottom: calc(76px + env(safe-area-inset-bottom));
        }
    }
</style>

<button class="chat-fab" id="chatFab" title="Chat with Barangay Officials">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
    </svg>
    <span class="unread-badge">0</span>
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
    <div class="chat-form-wrap">
        <div class="resident-preview" id="residentImagePreview">
            <img id="residentPreviewImg" alt="Selected image preview">
            <div class="resident-preview-copy">
                <div class="resident-preview-title">Image selected</div>
                <div class="resident-preview-name" id="residentPreviewName"></div>
            </div>
            <div class="resident-preview-actions">
                <button type="button" class="resident-preview-btn" id="residentPreviewEdit">Edit</button>
                <button type="button" class="resident-preview-btn" id="residentPreviewCancel">Cancel</button>
                <button type="button" class="resident-preview-btn send" id="residentPreviewSend">Send</button>
            </div>
        </div>
        <form class="chat-form" id="residentChatForm">
            <label class="chat-file chat-file-label" title="Upload image">
                📷
                <input type="file" id="residentChatImage" accept="image/*">
            </label>
            <input type="text" class="chat-input" id="residentChatInput" maxlength="2000" placeholder="Type a message...">
            <button type="submit" class="chat-send" id="residentSendBtn" title="Send" disabled>
                <span class="chat-send-label" id="residentSendLabel">Send</span>
            </button>
        </form>
    </div>
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
        const previewWrap = document.getElementById('residentImagePreview');
        const previewImg = document.getElementById('residentPreviewImg');
        const previewName = document.getElementById('residentPreviewName');
        const previewEdit = document.getElementById('residentPreviewEdit');
        const previewCancel = document.getElementById('residentPreviewCancel');
        const previewSend = document.getElementById('residentPreviewSend');
        const sendButton = document.getElementById('residentSendBtn');
        const sendLabel = document.getElementById('residentSendLabel');

        let currentThreadId = null;
        let lastMessageId = 0;
        let pollTimer = null;
        let previewObjectUrl = null;

        const getComposeState = () => {
            const hasText = input.value.trim().length > 0;
            const hasImage = imageInput.files.length > 0;

            return {
                hasText,
                hasImage,
                hasContent: hasText || hasImage,
            };
        };

        const updateSendButtonState = () => {
            const state = getComposeState();

            if (sendButton) {
                sendButton.disabled = !state.hasContent;
            }

            if (!sendLabel || !sendButton) {
                return;
            }

            if (state.hasText && state.hasImage) {
                sendLabel.textContent = 'Send both';
                sendButton.title = 'Send text and image';
                return;
            }

            if (state.hasImage) {
                sendLabel.textContent = 'Send image';
                sendButton.title = 'Send image';
                return;
            }

            if (state.hasText) {
                sendLabel.textContent = 'Send text';
                sendButton.title = 'Send text';
                return;
            }

            sendLabel.textContent = 'Send';
            sendButton.title = 'Add text or image to send';
        };

        const clearPreview = () => {
            if (previewObjectUrl) {
                URL.revokeObjectURL(previewObjectUrl);
                previewObjectUrl = null;
            }

            if (previewWrap) previewWrap.classList.remove('show');
            if (previewImg) previewImg.removeAttribute('src');
            if (previewName) previewName.textContent = '';
            imageInput.value = '';
            updateSendButtonState();
        };

        const showPreview = (file) => {
            if (!file || !file.type.startsWith('image/')) {
                clearPreview();
                return;
            }

            if (previewObjectUrl) {
                URL.revokeObjectURL(previewObjectUrl);
            }

            previewObjectUrl = URL.createObjectURL(file);
            if (previewImg) previewImg.src = previewObjectUrl;
            if (previewName) previewName.textContent = file.name;
            if (previewWrap) previewWrap.classList.add('show');
            updateSendButtonState();
            input.focus();
        };

        const renderMessage = (message) => {
            const wrapper = document.createElement('div');
            wrapper.className = `chat-item ${message.is_mine ? 'mine' : ''}`;

            const bubble = document.createElement('div');
            bubble.className = `chat-bubble ${message.is_mine ? 'mine' : ''}`;

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
                image.style.maxWidth = '100%';
                image.style.borderRadius = '8px';
                image.style.marginTop = message.body ? '8px' : '0';
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
            currentThreadId = data.thread_id;
            messageBox.innerHTML = '';

            if (!data.messages?.length) {
                messageBox.innerHTML = '<div class="chat-empty">Start a conversation with barangay officials.</div>';
                lastMessageId = 0;
                return;
            }

            data.messages.forEach(renderMessage);
            scrollBottom();
            
            // Mark messages as read
            if (currentThreadId) {
                fetch(`{{ route('resident.chat.mark-read', ':thread') }}`.replace(':thread', currentThreadId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    }
                });
            }
            
            // Update unread count
            updateUnreadCount();
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
            
            // Mark messages as read
            if (currentThreadId) {
                fetch(`{{ route('resident.chat.mark-read', ':thread') }}`.replace(':thread', currentThreadId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    }
                });
            }
            
            updateUnreadCount();
        };

        const updateUnreadCount = async () => {
            const response = await fetch('{{ route('resident.chat.unread-count') }}', {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            const badge = fab?.querySelector('.unread-badge');
            
            if (data.unread_count > 0 && badge) {
                badge.textContent = data.unread_count;
                badge.style.display = 'flex';
            } else if (badge) {
                badge.style.display = 'none';
            }
        };

        const sendMessage = async () => {
            const body = input.value.trim();
            const hasImage = imageInput.files.length > 0;

            if (!body && !hasImage) {
                input.focus();
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
                const errorData = await response.json().catch(() => ({}));
                const firstValidationError = errorData?.errors
                    ? Object.values(errorData.errors)[0]?.[0]
                    : null;
                alert(firstValidationError || errorData?.message || 'Unable to send message. Please try again.');
                return;
            }

            const data = await response.json();
            if (messageBox.querySelector('.chat-empty')) {
                messageBox.innerHTML = '';
            }

            renderMessage(data.message);
            scrollBottom();

            input.value = '';
            clearPreview();
            updateSendButtonState();
            
            updateUnreadCount();
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
            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }
        };

        fab?.addEventListener('click', openPanel);
        closeBtn?.addEventListener('click', closePanel);

        form?.addEventListener('submit', async (event) => {
            event.preventDefault();
            await sendMessage();
        });

        input?.addEventListener('input', () => {
            updateSendButtonState();
        });

        imageInput?.addEventListener('change', () => {
            const file = imageInput.files?.[0] || null;
            showPreview(file);
        });

        previewEdit?.addEventListener('click', () => {
            imageInput.click();
        });

        previewCancel?.addEventListener('click', () => {
            clearPreview();
        });

        previewSend?.addEventListener('click', async () => {
            await sendMessage();
        });

        updateSendButtonState();

        // Initial unread count check every 5 seconds
        setInterval(updateUnreadCount, 5000);

        window.addEventListener('beforeunload', () => {
            if (pollTimer) clearInterval(pollTimer);
            if (previewObjectUrl) URL.revokeObjectURL(previewObjectUrl);
        });
    })();
</script>
