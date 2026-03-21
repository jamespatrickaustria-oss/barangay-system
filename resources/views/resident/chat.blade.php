@extends('layouts.resident')

@section('title', 'Chat with Official')

@section('content')
<div id="chat-container" style="display: none;">
    <div id="resident-chat-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl h-3/4 flex flex-col">
            <!-- Chat Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 flex items-center justify-between rounded-t-lg">
                <div>
                    <h2 class="text-xl font-bold">Official Support</h2>
                    <p class="text-sm opacity-90">Message an official</p>
                </div>
                <button id="close-chat" class="text-white hover:bg-blue-800 rounded-full p-2 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Messages Area -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-4 bg-gray-50" style="max-height: calc(100% - 180px);">
                <div class="text-center text-gray-500 py-8">
                    <p>Loading conversation...</p>
                </div>
            </div>

            <!-- Message Input -->
            <div class="border-t border-gray-200 p-4 bg-white rounded-b-lg">
                <form id="message-form" class="space-y-3">
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <textarea id="message-input" placeholder="Type your message..." rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <label class="flex items-end">
                            <input type="file" id="image-input" accept="image/*" style="display: none;">
                            <button type="button" id="image-upload-btn" class="p-2 text-gray-500 hover:text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </label>
                    </div>
                    <div id="image-preview" class="text-sm text-gray-600"></div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Floating Chat Button -->
    <button id="chat-fab" class="fixed bottom-8 right-8 z-40 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition transform hover:scale-110 relative" title="Chat with official">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <span id="unread-badge" class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center" style="display: none;">
            0
        </span>
    </button>
</div>

<script>
    let currentThreadId = null;
    let pollInterval = null;
    let lastMessageId = 0;

    // Initialize chat on page load
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chat-container');
        const chatFab = document.getElementById('chat-fab');
        const modal = document.getElementById('resident-chat-modal');
        const closeBtn = document.getElementById('close-chat');
        const messageForm = document.getElementById('message-form');
        const imageUploadBtn = document.getElementById('image-upload-btn');
        const imageInput = document.getElementById('image-input');

        // Show chat container
        chatContainer.style.display = 'block';

        // Load initial thread data
        loadResidentThread();
        updateUnreadCount();

        // Chat FAB click
        chatFab.addEventListener('click', function() {
            modal.style.display = 'flex';
            loadMessages();
            startPolling();
        });

        // Close button
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            stopPolling();
        });

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
                stopPolling();
            }
        });

        // Message form submission
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage();
        });

        // Image upload button
        imageUploadBtn.addEventListener('click', function() {
            imageInput.click();
        });

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const preview = document.getElementById('image-preview');
                preview.innerHTML = `<div class="text-green-600">📷 Image selected: ${file.name}</div>`;
            }
        });
    });

    function loadResidentThread() {
        fetch('{{ route("resident.chat.thread") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(response => response.json())
        .then(data => {
            currentThreadId = data.thread_id;
            lastMessageId = 0;
            loadMessages();
        })
        .catch(error => console.error('Error loading thread:', error));
    }

    function loadMessages() {
        if (!currentThreadId) return;

        const url = new URL('{{ route("resident.chat.messages") }}', window.location.origin);
        url.searchParams.set('after_id', lastMessageId);

        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('messages-container');
            
            if (data.messages.length === 0) {
                if (lastMessageId === 0) {
                    container.innerHTML = '<div class="text-center text-gray-400 py-8">No messages yet. Start the conversation!</div>';
                }
                return;
            }

            // Initial load or append new messages
            if (lastMessageId === 0) {
                container.innerHTML = '';
            }

            data.messages.forEach(message => {
                displayMessage(message);
                lastMessageId = Math.max(lastMessageId, message.id);
            });

            // Scroll to bottom
            container.scrollTop = container.scrollHeight;

            // Mark messages as read
            if (currentThreadId) {
                fetch(`{{ route('resident.chat.mark-read', ':thread') }}`.replace(':thread', currentThreadId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
            }

            updateUnreadCount();
        })
        .catch(error => console.error('Error loading messages:', error));
    }

    function displayMessage(message) {
        const container = document.getElementById('messages-container');
        const messageDiv = document.createElement('div');
        
        const isMine = message.is_mine;
        messageDiv.className = `mb-4 flex ${isMine ? 'justify-end' : 'justify-start'}`;

        let content = '';
        if (message.body) {
            content += `<p class="text-sm">${escapeHtml(message.body)}</p>`;
        }
        if (message.image_url) {
            content += `<img src="${message.image_url}" alt="Message image" class="max-w-xs rounded-lg" style="max-height: 300px;">`;
        }

        messageDiv.innerHTML = `
            <div class="max-w-xs ${isMine ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'} rounded-lg px-4 py-2">
                ${content}
                <div class="text-xs ${isMine ? 'text-blue-100' : 'text-gray-500'} mt-1">
                    ${message.created_at_human}
                </div>
            </div>
        `;

        container.appendChild(messageDiv);
    }

    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const imageInput = document.getElementById('image-input');
        const body = messageInput.value.trim();

        if (!body && !imageInput.files.length) {
            alert('Please type a message or select an image');
            return;
        }

        const formData = new FormData();
        if (body) formData.append('body', body);
        if (imageInput.files.length) formData.append('image', imageInput.files[0]);

        fetch('{{ route("resident.chat.send") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                displayMessage(data.message);
                lastMessageId = data.message.id;
                messageInput.value = '';
                imageInput.value = '';
                document.getElementById('image-preview').innerHTML = '';
                document.getElementById('messages-container').scrollTop = document.getElementById('messages-container').scrollHeight;
                updateUnreadCount();
            }
        })
        .catch(error => console.error('Error sending message:', error));
    }

    function startPolling() {
        if (pollInterval) return;
        pollInterval = setInterval(loadMessages, 2000); // Poll every 2 seconds
    }

    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    function updateUnreadCount() {
        fetch('{{ route("resident.chat.unread-count") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('unread-badge');
            if (data.unread_count > 0) {
                badge.textContent = data.unread_count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(error => console.error('Error updating unread count:', error));
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>

<style>
    #resident-chat-modal {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    #chat-fab {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
@endsection
