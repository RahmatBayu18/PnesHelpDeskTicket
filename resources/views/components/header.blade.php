<header 
    x-data="{ mobileMenuOpen: false, notifOpen: false }" 
    class="fixed top-6 left-1/2 -translate-x-1/2 w-[95%] md:w-[90%] bg-white/90 backdrop-blur-md shadow-lg rounded-[2rem] px-6 py-3 flex flex-col z-50 transition-all duration-300"
>
    
    {{-- BAGIAN ATAS: LOGO - MENU - AKSI --}}
    <div class="w-full flex justify-between items-center relative">
        
        {{-- 1. LOGO & BRAND --}}
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group shrink-0">
            <div style="font-family: 'Inter', sans-serif; font-size: 24px; font-weight: bold; display: flex; align-items: center; gap: 8px;">
                <div style="height: 38px; display: flex; align-items: center; gap: 8px;">
                    {{-- Logo Image --}}
                    <img 
                        src="{{ asset('aset/logo-PensHelpDes.svg') }}" 
                        alt="Logo" 
                        style="height: 70%; width: auto; object-fit: contain;"
                    >
                    {{-- Text Brand --}}
                    <div style="color: #0056D2; font-size: 20px; font-weight: 600;">
                        Pens<span style="color: #FFC107;">HelpDesk</span>
                    </div>
                </div>
            </div>
        </a>

        {{-- 2. DESKTOP NAVIGATION (Hanya muncul di Layar Besar/LG) --}}
        {{-- Menggunakan 'hidden lg:flex' agar Tablet (MD) menggunakan Hamburger Menu --}}
        <nav class="hidden lg:flex items-center space-x-1 bg-gray-100/50 rounded-full px-2 py-1 mx-4">
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
                    {{-- Menu Admin/Teknisi --}}
                    <a href="{{ route('tickets.index') }}" 
                       class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 
                       {{ request()->routeIs('tickets.index') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                       Dashboard Tiket
                    </a>
                    <a href="{{ route('chat.index') }}" 
                       class="px-4 py-1.5 rounded-full text-sm font-medium transition-all duration-200 flex items-center space-x-1 
                       {{ request()->routeIs('chat.*') ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-900' }}">
                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
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

        {{-- 3. RIGHT SIDE (AUTH & ACTIONS) --}}
        <div class="flex items-center space-x-2 lg:space-x-4">
            
            @auth
                {{-- A. NOTIFICATION BELL (Mobile Friendly Click) --}}
                <div class="relative">
                    <button 
                        @click="notifOpen = !notifOpen" 
                        class="relative p-2 text-gray-400 hover:text-blue-600 transition hover:bg-blue-50 rounded-full focus:outline-none"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 flex h-2.5 w-2.5">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                            </span>
                        @endif
                    </button>

                    {{-- Dropdown Notifikasi --}}
                    <div 
                        x-show="notifOpen" 
                        @click.outside="notifOpen = false" 
                        x-cloak 
                        class="absolute right-0 mt-4 w-80 bg-white shadow-xl rounded-2xl border border-gray-100 p-2 z-50 origin-top-right"
                        x-transition:enter="transition ease-out duration-200 opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75 opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                    >
                        <div class="flex justify-between items-center px-4 py-2 border-b border-gray-50">
                            <span class="text-xs font-bold text-gray-400 uppercase">Notifikasi</span>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-blue-600 font-bold hover:underline">Tandai Semua Dibaca</button> 
                                </form>
                            @endif
                        </div>
                        
                        <div class="max-h-64 overflow-y-auto">
                            @forelse(auth()->user()->unreadNotifications->take(5) as $notif)
                                <a href="{{ route('notifications.mark-read', $notif->id) }}" class="block px-4 py-3 hover:bg-gray-50 rounded-lg group">
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 mr-2 shrink-0"></div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 group-hover:text-blue-600 transition">{{ $notif->data['title'] ?? 'Info' }}</p>
                                            <p class="text-xs text-gray-500 line-clamp-2">{{ $notif->data['message'] ?? 'Ada pembaruan status.' }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-6 text-gray-400">
                                    <span class="text-xs">Tidak ada notifikasi baru</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- B. PROFILE DROPDOWN (Desktop Only - LG ke atas) --}}
                <div class="relative group cursor-pointer pl-1 hidden lg:block"> 
                    <div class="flex items-center space-x-2">
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-800 leading-none">{{ auth()->user()->username }}</p>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wide mt-0.5">{{ auth()->user()->role }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-gray-100 border border-white shadow-sm overflow-hidden p-0.5">
                            <img class="w-full h-full rounded-full object-cover" 
                                 src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username).'&background=0D8ABC&color=fff&bold=true' }}" 
                                 alt="Profile">
                        </div>
                    </div>
                    
                    {{-- Menu Dropdown Profile --}}
                    <div class="absolute right-0 mt-4 w-48 bg-white shadow-xl rounded-2xl border border-gray-100 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg">Profile Saya</a>
                        <div class="h-px bg-gray-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 rounded-lg">Logout</button>
                        </form>
                    </div>
                </div>

            @else
                {{-- C. GUEST BUTTONS (Desktop Only) --}}
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold bg-blue-600 text-white rounded-full hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5">Daftar</a>
                </div>
            @endauth

            {{-- D. HAMBURGER BUTTON (Muncul di Mobile & Tablet / lg:hidden) --}}
            <div class="lg:hidden flex items-center">
                 @auth
                    {{-- Mini Avatar di sebelah hamburger (Mobile/Tablet) --}}
                    <div class="mr-3 w-8 h-8 rounded-full bg-gray-100 overflow-hidden border border-gray-200">
                        <img class="w-full h-full object-cover" 
                             src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->username).'&background=0D8ABC&color=fff&bold=true' }}" 
                             alt="Avatar">
                    </div>
                @endauth

                {{-- Tombol Toggle --}}
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="text-gray-500 hover:text-blue-600 focus:outline-none p-2 bg-gray-50 hover:bg-blue-50 rounded-full relative w-10 h-10 flex justify-center items-center transition-colors"
                >
                    {{-- Icon Garis Tiga --}}
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6 absolute transition-opacity duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    
                    {{-- Icon Close (Silang) --}}
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6 absolute transition-opacity duration-300 transform rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- BAGIAN BAWAH: MOBILE/TABLET MENU EXPANSION --}}
    <div 
        x-show="mobileMenuOpen" 
        x-cloak 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="w-full mt-4 lg:hidden bg-white/50 backdrop-blur-sm rounded-xl p-4 border border-gray-100 flex flex-col space-y-2"
    >
        @auth
            {{-- Info User Ringkas di Menu Mobile --}}
            <div class="px-4 py-2 mb-2 border-b border-gray-200/50">
                <p class="font-bold text-gray-800">{{ auth()->user()->username }}</p>
                <p class="text-xs text-gray-500 uppercase">{{ auth()->user()->role }}</p>
            </div>

            @if(auth()->user()->role === 'mahasiswa')
                <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('student.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-white/80' }}">Home</a>
                <a href="{{ route('tickets.my_tickets') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('tickets.my_tickets*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-white/80' }}">Tiket Saya</a>
            @else
                <a href="{{ route('tickets.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('tickets.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-white/80' }}">Dashboard Tiket</a>
                <a href="{{ route('chat.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('chat.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-white/80' }}">Live Chat</a>
                
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('roles.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('roles.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-white/80' }}">Users & Role</a>
                    <a href="{{ route('announcements.index') }}" class="block px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('announcements.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-white/80' }}">Pengumuman</a>
                @endif
            @endif

            <div class="h-px bg-gray-200 my-2"></div>
            
            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-white/80 hover:text-blue-600">Profile Saya</a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50">Logout</button>
            </form>

        @else
             {{-- Guest Links Mobile --}}
             <a href="{{ route('login') }}" class="block px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-white/80">Masuk</a>
             <a href="{{ route('register') }}" class="block px-4 py-2 rounded-lg text-sm font-bold text-blue-600 bg-blue-50 mt-2 text-center shadow-sm">Daftar Sekarang</a>
        @endauth
    </div>

</header>