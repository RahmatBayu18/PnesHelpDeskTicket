@extends('layouts.app')

@section('title', 'Live Chat Support')

@section('content')
<div class="fixed inset-0 top-32 bg-gray-100">
    <div class="h-full max-w-7xl mx-auto p-4">
        <div class="bg-white rounded-xl shadow-lg h-full overflow-hidden">
            <div class="flex h-full">
            <!-- Conversations Sidebar -->
            <div class="w-1/3 border-r border-gray-200 flex flex-col">
                <!-- Header -->
                <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        Live Chat Support
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">{{ $conversations->count() }} conversation(s)</p>
                </div>

                <!-- Conversations List -->
                <div id="conversations-list" class="flex-1 overflow-y-auto">
                    @forelse($conversations as $conversation)
                        <div class="conversation-item p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors" 
                             data-conversation-id="{{ $conversation->id }}"
                             onclick="selectConversation({{ $conversation->id }})">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($conversation->user->username, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $conversation->user->username }}
                                        </p>
                                        @if($conversation->unread_count > 0)
                                            <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $conversation->user->nim }} • {{ $conversation->user->email }}</p>
                                    @if($conversation->latestMessage)
                                        <p class="text-sm text-gray-600 truncate mt-1">
                                            {{ Str::limit($conversation->latestMessage->message, 40) }}
                                        </p>
                                    @endif
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-gray-400">
                                            {{ $conversation->last_message_at?->diffForHumans() ?? 'No messages' }}
                                        </span>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $conversation->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($conversation->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="font-medium">No conversations yet</p>
                            <p class="text-sm mt-1">Waiting for students to start chatting</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col min-h-0">
                <div id="no-conversation-selected" class="flex-1 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <svg class="w-24 h-24 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-lg font-medium">Select a conversation</p>
                        <p class="text-sm mt-2">Choose a conversation from the sidebar to start chatting</p>
                    </div>
                </div>

                <div id="chat-container" class="hidden h-full flex-col">
                    <!-- Chat Header -->
                    <div class="flex-shrink-0 p-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                    <span id="chat-user-initial"></span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900" id="chat-user-name"></p>
                                    <p class="text-xs text-gray-500" id="chat-user-info"></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="closeConversation()" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    Close Chat
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 min-h-0">
                        <!-- Messages will be inserted here -->
                    </div>

                    <!-- Message Input -->
                    <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-white">
                        <form id="message-form" onsubmit="sendMessage(event)" class="flex space-x-2">
                            <input type="text" 
                                   id="message-input" 
                                   placeholder="Type your message..." 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            <button type="submit" 
                                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentConversationId = null;
let currentUserId = {{ Auth::id() }};
let echoChannel = null;

// Notification sound function
function playNotificationSound() {
    try {
        // Create a simple notification sound using Web Audio API
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

function selectConversation(conversationId) {
    currentConversationId = conversationId;
    
    // Update UI
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500');
    });
    document.querySelector(`[data-conversation-id="${conversationId}"]`)?.classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
    
    document.getElementById('no-conversation-selected').classList.add('hidden');
    document.getElementById('chat-container').classList.remove('hidden');
    document.getElementById('chat-container').classList.add('flex');
    
    loadMessages(conversationId);
    
    // Unsubscribe from previous channel if exists
    if (echoChannel) {
        window.Echo.leave(`chat.${echoChannel}`);
    }
    
    // Subscribe to new channel
    console.log('Subscribing to channel:', `chat.${conversationId}`);
    echoChannel = conversationId;
    
    window.Echo.private(`chat.${conversationId}`)
        .listen('.message.sent', (e) => {
            console.log('Message received:', e);
            appendMessage(e, true); // Pass true to indicate this is a new real-time message
            scrollToBottom();
        })
        .error((error) => {
            console.error('Channel error:', error);
        });
}

function loadMessages(conversationId) {
    fetch(`/chat/${conversationId}/messages`)
        .then(response => response.json())
        .then(data => {
            // Update header
            const user = data.conversation.user;
            document.getElementById('chat-user-initial').textContent = user.username.charAt(0).toUpperCase();
            document.getElementById('chat-user-name').textContent = user.username;
            document.getElementById('chat-user-info').textContent = `${user.nim} • ${user.email}`;
            
            // Clear and load messages
            const container = document.getElementById('messages-container');
            container.innerHTML = '';
            
            data.messages.forEach(message => {
                appendMessage(message, false); // Pass false to indicate historical message (no sound)
            });
            
            scrollToBottom();
        });
}

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

function appendMessage(message, isNewMessage = false) {
    const container = document.getElementById('messages-container');
    const isOwnMessage = message.user_id === currentUserId;
    
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
        <div class="max-w-xs lg:max-w-md">
            ${!isOwnMessage ? `<p class="text-xs text-gray-500 mb-1">${message.user.username}</p>` : ''}
            <div class="px-4 py-2 rounded-lg ${isOwnMessage ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 shadow'}">
                <p class="text-sm">${escapeHtml(message.message)}</p>
            </div>
            <p class="text-xs text-gray-400 mt-1 ${isOwnMessage ? 'text-right' : ''}">${formatTime(message.created_at)}</p>
        </div>
    `;
    
    container.appendChild(messageDiv);
    
    // Play sound only for new real-time messages from other users
    if (!isOwnMessage && isNewMessage) {
        playNotificationSound();
    }
}

function sendMessage(event) {
    event.preventDefault();
    
    const input = document.getElementById('message-input');
    const message = input.value.trim();
    
    if (!message || !currentConversationId) return;
    
    fetch(`/chat/${currentConversationId}/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message })
    })
    .then(response => response.json())
    .then(data => {
        // Don't append here - let Echo broadcast handle it
        // This prevents duplicate messages
        input.value = '';
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    });
}

function closeConversation() {
    if (!currentConversationId) return;
    
    if (confirm('Are you sure you want to close this conversation?')) {
        fetch(`/chat/${currentConversationId}/close`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => {
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

// Assign admin to conversation on first interaction
function assignToMe() {
    if (!currentConversationId) return;
    
    fetch(`/chat/${currentConversationId}/assign`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
}

// Auto-assign when selecting conversation
document.addEventListener('DOMContentLoaded', function() {
    // Listen for new conversations
    // You can add more real-time updates here
});
</script>
@endpush
@endsection
