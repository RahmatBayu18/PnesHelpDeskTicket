<!-- Floating Chat Widget -->
@if(Auth::check() && Auth::user()->role === 'mahasiswa')
<div id="chat-widget" class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-50">
    <button id="chat-toggle-btn" onclick="toggleChat()" 
            class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-200 flex items-center justify-center relative">
        <svg id="chat-icon" class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <svg id="close-icon" class="w-6 h-6 md:w-8 md:h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span id="unread-badge" class="absolute -top-1 -right-1 hidden w-5 h-5 md:w-6 md:h-6 bg-red-500 text-white text-[10px] md:text-xs font-bold rounded-full flex items-center justify-center">
            0
        </span>
    </button>

    <div id="chat-window" class="hidden absolute bottom-16 md:bottom-20 right-0 w-[calc(100vw-2rem)] md:w-96 h-[60vh] md:h-[500px] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden origin-bottom-right border border-gray-200">
        
        <div class="p-3 md:p-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base md:text-lg">Live Support</h3>
                    <p class="text-[10px] md:text-xs text-blue-100">We're here to help!</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div id="connection-status" class="w-2 h-2 bg-green-400 rounded-full"></div>
                </div>
            </div>
        </div>

        <div id="chat-messages" class="flex-1 overflow-y-auto p-3 md:p-4 space-y-3 bg-gray-50">
            <div class="text-center text-gray-500 text-xs md:text-sm py-4">
                <p>👋 Hello! How can we help you today?</p>
            </div>
        </div>

        <div id="typing-indicator" class="hidden px-4 py-2 text-xs md:text-sm text-gray-500">
            <span class="inline-flex space-x-1">
                <span class="animate-bounce">●</span>
                <span class="animate-bounce" style="animation-delay: 0.1s;">●</span>
                <span class="animate-bounce" style="animation-delay: 0.2s;">●</span>
            </span>
            <span class="ml-2">Admin is typing...</span>
        </div>

        <div class="p-3 md:p-4 border-t border-gray-200 bg-white">
            <form id="chat-form" onsubmit="sendChatMessage(event)" class="flex space-x-2">
                <input type="text" 
                       id="chat-input" 
                       placeholder="Type your message..." 
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs md:text-sm"
                       required>
                <button type="submit" 
                        class="px-3 md:px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all flex items-center justify-center">
                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let chatConversationId = null;
let chatUserId = {{ Auth::id() }};
let isChatOpen = false;

// Notification sound function
function playNotificationSound() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = 800;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.5);
    } catch (error) {
        console.error('Error playing sound:', error);
    }
}

function toggleChat() {
    const window = document.getElementById('chat-window');
    const chatIcon = document.getElementById('chat-icon');
    const closeIcon = document.getElementById('close-icon');
    const badge = document.getElementById('unread-badge');
    
    if (window.classList.contains('hidden')) {
        window.classList.remove('hidden');
        chatIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        isChatOpen = true;
        
        // Reset badge
        badge.textContent = '0';
        badge.classList.add('hidden');
        
        if (!chatConversationId) {
            initializeChat();
        }
    } else {
        window.classList.add('hidden');
        chatIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
        isChatOpen = false;
    }
}

function initializeChat() {
    if (typeof window.Echo === 'undefined') {
        console.error('Laravel Echo is not initialized!');
        alert('Chat system is not properly initialized. Please refresh the page.');
        return;
    }
    
    fetch('/chat/conversation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        chatConversationId = data.conversation.id;
        loadChatMessages();
        subscribeToChat();
    })
    .catch(error => {
        console.error('Error initializing chat:', error);
        alert('Failed to initialize chat. Please try again.');
    });
}

function loadChatMessages() {
    fetch(`/chat/${chatConversationId}/messages`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('chat-messages');
            container.innerHTML = '';
            
            if (data.messages.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-gray-500 text-sm py-4">
                        <p>👋 Hello! How can we help you today?</p>
                    </div>
                `;
            } else {
                data.messages.forEach(message => {
                    appendChatMessage(message);
                });
            }
            
            scrollChatToBottom();
        })
        .catch(error => {
            console.error('Error loading messages:', error);
        });
}

function subscribeToChat() {
    console.log('Subscribing to chat channel:', `chat.${chatConversationId}`);
    
    window.Echo.private(`chat.${chatConversationId}`)
        .listen('.message.sent', (e) => {
            console.log('New message received:', e);
            appendChatMessage(e);
            scrollChatToBottom();
            
            // Play sound if message is from another user (admin/teknisi)
            if (e.user_id !== chatUserId) {
                playNotificationSound();
            }
            
            // Update unread badge if chat is closed
            if (!isChatOpen) {
                updateUnreadBadge();
            }
        })
        .error((error) => {
            console.error('Echo channel error:', error);
        });
    
    console.log('Echo subscription complete');
}

function appendChatMessage(message) {
    const container = document.getElementById('chat-messages');
    const isOwnMessage = message.user_id === chatUserId;
    
    // Check if message already exists to prevent duplicates
    const existingMessage = container.querySelector(`[data-message-id="${message.id}"]`);
    if (existingMessage) {
        console.log('Message already exists, skipping:', message.id);
        return;
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'}`;
    messageDiv.setAttribute('data-message-id', message.id);
    
    messageDiv.innerHTML = `
        <div class="max-w-[75%]">
            ${!isOwnMessage ? `<p class="text-xs text-gray-500 mb-1">${message.user.role === 'admin' ? '👤 Admin' : '🔧 Support'}</p>` : ''}
            <div class="px-3 py-2 rounded-lg ${isOwnMessage ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 shadow-sm border border-gray-200'}">
                <p class="text-sm">${escapeHtml(message.message)}</p>
            </div>
            <p class="text-xs text-gray-400 mt-1 ${isOwnMessage ? 'text-right' : ''}">${formatTime(message.created_at)}</p>
        </div>
    `;
    
    container.appendChild(messageDiv);
}

function sendChatMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    
    if (!message || !chatConversationId) return;
    
    // Clear input immediately for better UX
    input.value = '';
    
    fetch(`/chat/${chatConversationId}/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message })
    })
    .then(response => response.json())
    .then(data => {
        // Don't append message here - let Echo broadcast handle it
        // This prevents duplicate messages
        console.log('Message sent successfully');
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
        // Restore the message in input on error
        input.value = message;
    });
}

function scrollChatToBottom() {
    const container = document.getElementById('chat-messages');
    setTimeout(() => {
        container.scrollTop = container.scrollHeight;
    }, 100);
}

function updateUnreadBadge() {
    const badge = document.getElementById('unread-badge');
    let count = parseInt(badge.textContent) || 0;
    count++;
    badge.textContent = count;
    badge.classList.remove('hidden');
    
    // Play notification sound
    playNotificationSound();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
}
</script>

<style>
@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-5px);
    }
}

.animate-bounce {
    animation: bounce 1s infinite;
}
</style>
@endif
