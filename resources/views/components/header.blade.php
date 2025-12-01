<header class="fixed top-6 left-1/2 -translate-x-1/2 w-[95%] md:w-[90%] bg-white/90 backdrop-blur-md shadow-lg rounded-full px-6 py-3 flex justify-between items-center z-50 transition-all duration-300">
    
    {{-- LOGO & BRAND --}}
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 flex items-center justify-center text-white text-xs font-bold shadow-md group-hover:scale-105 transition-transform">
            P
        </div>
        <span class="font-bold text-gray-800 tracking-tight text-lg group-hover:text-blue-600 transition-colors">
            PensHelpDesk
        </span>
    </a>

    {{-- NAVIGATION LINKS (Desktop) --}}
    <nav class="hidden md:flex items-center space-x-1 bg-gray-100/50 rounded-full px-2 py-1">
        
        @auth
            @if(auth()->user()->role === 'mahasiswa')
                {{-- MENU KHUSUS MAHASISWA --}}
                
                <a href="{{ route('student.dashboard') }}" 
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('student.dashboard') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                   Home
                </a>

                <a href="{{ route('tickets.my_tickets') }}" 
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('tickets.my_tickets*') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                   Tiket Saya
                </a>

            @else
                {{-- MENU KHUSUS ADMIN & TEKNISI --}}
                
                <a href="{{ route('tickets.index') }}" 
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('tickets.index') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                   Dashboard Tiket
                </a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('roles.index') }}" 
                       class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                       {{ request()->routeIs('roles.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                       Users & Role
                    </a>

                    <a href="{{ route('announcements.index') }}" 
                       class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                       {{ request()->routeIs('announcements.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                       Pengumuman
                    </a>
                @endif
            @endif
        @endauth

    </nav>

    {{-- RIGHT SIDE (AUTH & NOTIF) --}}
    <div class="flex items-center space-x-4">
        
        @auth
            {{-- 1. NOTIFICATION BELL --}}
            <div class="relative group cursor-pointer">
                <button class="relative p-2 text-gray-400 hover:text-blue-600 transition hover:bg-blue-50 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    
                    {{-- Badge Count --}}
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1 right-1 flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </span>
                    @endif
                </button>

                {{-- Dropdown Notifikasi --}}
                <div class="absolute right-0 mt-4 w-80 bg-white shadow-xl rounded-2xl border border-gray-100 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                    <div class="flex justify-between items-center px-4 py-2 border-b border-gray-50">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Notifikasi</span>
                        <div class="flex items-center gap-2">
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">{{ auth()->user()->unreadNotifications->count() }} Baru</span>
                                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-blue-600 hover:text-blue-800 font-medium">Tandai Semua</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    
                    <div class="max-h-64 overflow-y-auto">
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notif)
                            <a href="{{ route('notifications.mark-read', $notif->id) }}" class="block px-4 py-3 hover:bg-gray-50 rounded-lg transition group/item">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-800 font-medium group-hover/item:text-blue-600 transition">{{ $notif->data['title'] ?? 'Info Sistem' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notif->data['message'] ?? 'Ada pembaruan status.' }}</p>
                                        <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-6 text-gray-400">
                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <span class="text-xs">Tidak ada notifikasi baru</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- 2. PROFILE DROPDOWN --}}
            <div class="relative group cursor-pointer pl-2">
                <div class="flex items-center space-x-2">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-800 leading-none">{{ auth()->user()->username }}</p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wide mt-0.5">{{ auth()->user()->role }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border border-white shadow-sm overflow-hidden p-0.5">
                        @if(auth()->user()->profile_picture)
                            <img class="w-full h-full rounded-full object-cover" 
                                 src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                 alt="Profile Picture">
                        @else
                            <img class="w-full h-full rounded-full object-cover" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=0D8ABC&color=fff&bold=true" 
                                 alt="Avatar">
                        @endif
                    </div>
                </div>

                {{-- Dropdown Menu --}}
                <div class="absolute right-0 mt-4 w-56 bg-white shadow-xl rounded-2xl border border-gray-100 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                    
                    <div class="sm:hidden px-3 py-2 border-b border-gray-50 mb-1">
                        <p class="text-sm font-bold text-gray-800">{{ auth()->user()->username }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2.5 rounded-xl text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition group/link">
                        <svg class="w-4 h-4 mr-3 text-gray-400 group-hover/link:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profile Saya
                    </a>

                    <div class="h-px bg-gray-100 my-1"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2.5 rounded-xl text-sm text-red-500 hover:bg-red-50 transition group/logout">
                            <svg class="w-4 h-4 mr-3 text-red-400 group-hover/logout:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

        @else
            <div class="flex items-center space-x-1">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold bg-blue-600 text-white rounded-full hover:bg-blue-700 shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">Daftar</a>
            </div>
        @endauth
    </div>
</header>