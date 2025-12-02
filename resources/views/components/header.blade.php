{{-- Tambahkan x-data di sini --}}
<header 
    x-data="{ mobileMenuOpen: false }" 
    class="fixed top-6 left-1/2 -translate-x-1/2 w-[95%] md:w-[90%] bg-white/90 backdrop-blur-md shadow-lg rounded-[2rem] px-6 py-3 flex flex-wrap justify-between items-center z-50 transition-all duration-300"
>
    
    {{-- 1. LOGO & BRAND (Kode Asli) --}}
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group shrink-0">
        <div style="font-family: 'Inter', sans-serif; font-size: 24px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
            <div style="height: 38px; display: flex; align-items: center; gap: 8px;">
                <img 
                    src="{{ asset('aset/logo-PensHelpDes.svg') }}"
                    alt="Logo"
                    style="height: 70%; width: auto; object-fit: contain;"
                >
                <div style="color: #0056D2; font-size: 20px; font-weight: 600;">
                    Pens<span style="color: #FFC107;">HelpDesk</span>
                </div>
            </div>
        </div>
    </a>

    {{-- 2. NAVIGATION LINKS (Desktop Only - Tambahkan class 'hidden md:flex') --}}
    <nav class="hidden md:flex items-center space-x-1 bg-gray-100/50 rounded-full px-2 py-1">
        @auth
            @if(auth()->user()->role === 'mahasiswa')
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
                <a href="{{ route('tickets.index') }}" 
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200
                   {{ request()->routeIs('tickets.index') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                   Dashboard Tiket
                </a>

                <a href="{{ route('chat.index') }}" 
                   class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 flex items-center space-x-1
                   {{ request()->routeIs('chat.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                   <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                   </svg>
                   <span>Live Chat</span>
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

    {{-- 3. RIGHT SIDE (AUTH & NOTIF) --}}
    <div class="flex items-center space-x-2 md:space-x-4">
        
        @auth
            {{-- NOTIFICATION BELL (Tetap utuh kode aslinya) --}}
            <div class="relative group cursor-pointer">
                <button class="relative p-2 text-gray-400 hover:text-blue-600 transition hover:bg-blue-50 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1 right-1 flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </span>
                    @endif
                </button>

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

            {{-- PROFILE DROPDOWN (Tetap utuh kode aslinya) --}}
            <div class="relative group cursor-pointer pl-2 hidden md:block"> {{-- Hidden on mobile to avoid clutter --}}
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

                {{-- Dropdown Menu Profile --}}
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
            {{-- Guest Buttons Desktop --}}
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold bg-blue-600 text-white rounded-full hover:bg-blue-700 shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">Daftar</a>
            </div>
        @endauth

        {{-- HAMBURGER BUTTON (Mobile Only - Baru Ditambahkan) --}}
        <div class="md:hidden flex items-center">
             {{-- Profile Icon Tiny for Mobile --}}
             @auth
                <div class="mr-3 w-8 h-8 rounded-full bg-gray-100 overflow-hidden border border-gray-200">
                    <img class="w-full h-full object-cover" 
                        src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username).'&background=0D8ABC&color=fff&bold=true' }}" 
                        alt="Avatar">
                </div>
            @endauth

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-blue-600 focus:outline-none p-2 bg-gray-50 rounded-full">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

    </div>

    {{-- 4. MOBILE MENU CONTAINER (Baru Ditambahkan) --}}
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        x-cloak
        class="w-full mt-4 md:hidden bg-white/50 backdrop-blur-sm rounded-xl p-4 border border-gray-100 flex flex-col space-y-2"
    >
        @auth
            @if(auth()->user()->role === 'mahasiswa')
                <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('student.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Home</a>
                <a href="{{ route('tickets.my_tickets') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('tickets.my_tickets*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Tiket Saya</a>
            @else
                <a href="{{ route('tickets.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('tickets.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Dashboard Tiket</a>
                <a href="{{ route('chat.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('chat.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Live Chat</a>
                
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('roles.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('roles.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Users & Role</a>
                    <a href="{{ route('announcements.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('announcements.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Pengumuman</a>
                @endif
            @endif

            <div class="h-px bg-gray-200 my-2"></div>
            
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Profile Saya</a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50">Logout</button>
            </form>

        @else
             <a href="{{ route('login') }}" class="block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">Masuk</a>
             <a href="{{ route('register') }}" class="block px-4 py-2 rounded-lg text-sm font-bold text-blue-600 bg-blue-50 mt-2 text-center">Daftar Sekarang</a>
        @endauth
    </div>

</header>