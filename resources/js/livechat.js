/**
 * Live Chat Logic
 * Membutuhkan window.LiveChatConfig dari Blade view
 */

let currentConversationId = null;
let echoChannel = null;

// Mengambil config dari Blade (Global Variable)
const currentUserId = window.LiveChatConfig.userId;
const csrfToken = window.LiveChatConfig.csrfToken;

// --- RESPONSIVE HELPERS ---

function showMobileChat() {
    const sidebar = document.getElementById('conversations-sidebar');
    const chatInterface = document.getElementById('chat-interface');
    
    if (window.innerWidth < 768) {
        sidebar.classList.add('hidden');
        chatInterface.classList.remove('hidden');
        chatInterface.classList.add('flex');
    }
}

// Diexpose ke window agar bisa dipanggil via onclick HTML
window.backToConversations = function() {
    const sidebar = document.getElementById('conversations-sidebar');
    const chatInterface = document.getElementById('chat-interface');
    
    sidebar.classList.remove('hidden');
    chatInterface.classList.add('hidden');
    chatInterface.classList.remove('flex');
}

// --- MAIN FUNCTIONS ---

window.selectConversation = function(conversationId) {
    currentConversationId = conversationId;
    
    // Update UI list
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
    });
    
    const activeItem = document.querySelector(`[data-conversation-id="${conversationId}"]`);
    if(activeItem) {
        activeItem.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
    }
    
    document.getElementById('no-conversation-selected').classList.add('hidden');
    const chatContainer = document.getElementById('chat-container');
    chatContainer.classList.remove('hidden');
    chatContainer.classList.add('flex');
    
    // Trigger Mobile View
    showMobileChat();

    // Reset Header
    document.getElementById('chat-user-name').textContent = "Loading...";
    
    loadMessages(conversationId);
    
    // Echo Setup
    if (echoChannel) {
        window.Echo.leave(`chat.${echoChannel}`);
    }
    echoChannel = conversationId;
    
    console.log('Subscribing to channel:', `chat.${conversationId}`);
    
    window.Echo.private(`chat.${conversationId}`)
        .listen('.message.sent', (e) => {
            console.log('Message received:', e);
            appendMessage(e, true);
            scrollToBottom();
        });
}

function loadMessages(conversationId) {
    fetch(`/chat/${conversationId}/messages`)
        .then(response => response.json())
        .then(data => {
            const user = data.conversation.user;
            document.getElementById('chat-user-initial').textContent = user.username.charAt(0).toUpperCase();
            document.getElementById('chat-user-name').textContent = user.username;
            document.getElementById('chat-user-info').textContent = `${user.nim}`;
            
            const container = document.getElementById('messages-container');
            container.innerHTML = '';
            
            data.messages.forEach(message => {
                appendMessage(message, false);
            });
            
            scrollToBottom();
        });
}

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

function appendMessage(message, isNewMessage = false) {
    const container = document.getElementById('messages-container');
    const isOwnMessage = message.user_id === currentUserId;
    
    // Prevent duplicates
    const existingMessage = container.querySelector(`[data-message-id="${message.id}"]`);
    if (existingMessage) return;
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'}`;
    messageDiv.setAttribute('data-message-id', message.id);
    
    messageDiv.innerHTML = `
        <div class="max-w-[85%] lg:max-w-md">
            ${!isOwnMessage ? `<p class="text-xs text-gray-500 mb-1 ml-1">${message.user.username}</p>` : ''}
            <div class="px-4 py-2 rounded-2xl ${isOwnMessage ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white text-gray-900 shadow-sm border border-gray-100 rounded-tl-none'}">
                <p class="text-sm break-words">${escapeHtml(message.message)}</p>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 ${isOwnMessage ? 'text-right mr-1' : 'ml-1'}">${formatTime(message.created_at)}</p>
        </div>
    `;
    
    container.appendChild(messageDiv);
    
    if (!isOwnMessage && isNewMessage) {
        playNotificationSound();
    }
}

// Diexpose ke window untuk form onsubmit
window.sendMessage = function(event) {
    event.preventDefault();
    
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    
    if (!message || !currentConversationId) return;
    
    fetch(`/chat/${currentConversationId}/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken // Menggunakan variabel dari config
        },
        body: JSON.stringify({ message })
    })
    .then(response => response.json())
    .then(data => {
        input.value = '';
        scrollToBottom();
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Failed to send message.');
    });
}

// Diexpose ke window
window.closeConversation = function() {
    if (!currentConversationId) return;
    
    if (confirm('Are you sure you want to close this conversation?')) {
        fetch(`/chat/${currentConversationId}/close`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(() => {
            if(window.innerWidth < 768) {
                window.backToConversations();
            }
            location.reload();
        });
    }
}

function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
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