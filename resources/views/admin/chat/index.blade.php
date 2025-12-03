@extends('layouts.app')

@section('title', 'Live Chat Support')
@section('content')
<div class="fixed inset-0 top-25 bg-gray-100 z-0">
    <div class="h-full max-w-7xl mx-auto p-4 md:py-8 md:px-6">
        
        <div class="bg-white rounded-2xl shadow-2xl h-full overflow-hidden border border-gray-200 flex relative">
            
            <div id="conversations-sidebar" class="w-full md:w-1/3 border-r border-gray-200 flex flex-col h-full bg-white z-10">
                
                <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        HelpDesk Chat
                    </h2>
                    <p class="text-blue-100 text-xs mt-1 font-medium ml-9">{{ $conversations->total() }} conversations total</p>
                </div>

                <div id="conversations-list" class="flex-1 overflow-y-auto custom-scrollbar">
                    @forelse($conversations as $conversation)
                        <div class="conversation-item p-4 border-b border-gray-100 hover:bg-blue-50 cursor-pointer transition-all duration-200 group" 
                             data-conversation-id="{{ $conversation->id }}"
                             onclick="selectConversation({{ $conversation->id }})">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 relative">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg border-2 border-white shadow-sm group-hover:border-blue-200 transition-colors">
                                        {{ substr($conversation->user->username, 0, 1) }}
                                    </div>
                                    @if($conversation->unread_count > 0)
                                        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 ring-2 ring-white text-[10px] font-bold text-white shadow-sm">
                                            {{ $conversation->unread_count }}
                                        </span>
                                    @endif
                                </div>

                                <div class="ml-4 flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-gray-900 truncate group-hover:text-blue-700">
                                            {{ $conversation->user->username }}
                                        </p>
                                        <span class="text-[10px] text-gray-400 font-medium bg-gray-50 px-2 py-0.5 rounded-full">
                                            {{ $conversation->latestMessage ? $conversation->latestMessage->created_at->shortAbsoluteDiffForHumans() : '' }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-xs text-gray-500 mt-0.5 mb-1 font-mono">{{ $conversation->user->nim }}</p>
                                    
                                    <div class="flex justify-between items-end">
                                        <p class="text-xs text-gray-600 truncate max-w-[140px]">
                                            {{ Str::limit($conversation->latestMessage->message ?? 'No messages', 30) }}
                                        </p>
                                        
                                        <span class="w-2 h-2 rounded-full {{ $conversation->status === 'open' ? 'bg-green-500' : 'bg-gray-400' }}" title="{{ ucfirst($conversation->status) }}"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 p-8">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p class="text-sm">Belum ada chat.</p>
                        </div>
                    @endforelse
                </div>

                @if($conversations->hasPages())
                <div class="p-3 border-t border-gray-200 bg-gray-50 text-xs">
                    {{ $conversations->links('pagination::simple-tailwind') }}
                </div>
                @endif
            </div>

            <div id="chat-interface" class="hidden md:flex flex-1 flex-col min-h-0 bg-gray-50 absolute md:relative inset-0 w-full z-20 md:z-auto">
                
                <div id="no-conversation-selected" class="flex-1 flex flex-col items-center justify-center text-gray-400 bg-gray-50/50">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-600">Selamat Datang di Support</p>
                    <p class="text-sm mt-2 text-gray-500">Pilih percakapan di sebelah kiri untuk memulai.</p>
                </div>

                <div id="chat-container" class="hidden h-full flex-col w-full bg-white">
                    <div class="flex-shrink-0 px-6 py-3 border-b border-gray-100 flex items-center justify-between shadow-sm bg-white z-10">
                        <div class="flex items-center">
                            <button onclick="backToConversations()" class="md:hidden mr-3 text-gray-500 hover:text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>

                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-md">
                                <span id="chat-user-initial"></span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-900" id="chat-user-name"></p>
                                <div class="flex items-center space-x-2">
                                    <p class="text-xs text-gray-500" id="chat-user-info"></p>
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                </div>
                            </div>
                        </div>
                        
                        <button onclick="closeConversation()" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50" title="Close Ticket">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div id="messages-container" class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 bg-[#f8fafc] scroll-smooth">
                        </div>

                    <div class="flex-shrink-0 p-4 bg-white border-t border-gray-100">
                        <form id="message-form" onsubmit="sendMessage(event)" class="relative flex items-center">
                            <input type="text" 
                                   id="message-input" 
                                   placeholder="Ketik pesan balasan..." 
                                   class="w-full pl-5 pr-14 py-3 bg-gray-50 border-0 rounded-full text-sm focus:ring-2 focus:ring-blue-100 focus:bg-white transition-all shadow-inner text-gray-700"
                                   required
                                   autocomplete="off">
                            
                            <button type="submit" 
                                    class="absolute right-2 p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 shadow-md transition-transform transform active:scale-95">
                                <svg class="w-5 h-5 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    window.LiveChatConfig = {
        userId: {{ Auth::id() }},
        csrfToken: '{{ csrf_token() }}'
    };
</script>

@vite(['resources/js/livechat.js'])
@endpush
@endsection