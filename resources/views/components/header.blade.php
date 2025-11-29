<header class="fixed top-6 left-1/2 -translate-x-1/2 w-[90%] bg-white shadow-md rounded-full px-6 py-3 flex justify-between items-center z-50">
    {{-- LOGO & BRAND --}}
    <div class="flex items-center space-x-2">
        <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">P</div>
        <span class="font-semibold text-gray-800 tracking-tight">PensHelpDesk</span>
    </div>

    {{-- NAVIGATION LINKS --}}
    <nav class="hidden md:flex space-x-8 text-sm font-medium text-gray-500">
        <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Home</a>
        <a href="{{ route('tickets.index') }}" class="hover:text-blue-600 transition {{ request()->routeIs('tickets.*') ? 'text-blue-600' : '' }}">Tiket Saya</a>
        @if(auth()->check() && auth()->user()->role !== 'mahasiswa')
             {{-- Menu tambahan untuk Teknisi/Admin --}}
            <a href="#" class="hover:text-blue-600 transition">Laporan</a>
        @endif
    </nav>

    {{-- RIGHT SIDE (AUTH & NOTIF) --}}
    <div class="flex items-center space-x-5">
        
        @auth
            {{-- 1. NOTIFICATION BELL --}}
            <div class="relative group cursor-pointer">
                <button class="relative text-gray-400 hover:text-blue-600 transition mt-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    
                    {{-- Badge Count --}}
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>

                {{-- Dropdown Notifikasi --}}
                <div class="absolute right-0 mt-2 w-72 bg-white shadow-xl rounded-xl border border-gray-100 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right">
                    <p class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Notifikasi</p>
                    @forelse(auth()->user()->unreadNotifications->take(3) as $notif)
                        <a href="{{ $notif->data['url'] ?? '#' }}" class="block px-4 py-3 hover:bg-gray-50 rounded-lg transition">
                            <p class="text-sm text-gray-800 font-medium">{{ $notif->data['title'] ?? 'Info' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($notif->data['message'], 40) }}</p>
                        </a>
                    @empty
                        <div class="text-center py-4 text-gray-400 text-sm">Tidak ada notifikasi baru</div>
                    @endforelse
                </div>
            </div>

            {{-- 2. PROFILE DROPDOWN --}}
            <div class="relative group cursor-pointer">
                {{-- Avatar --}}
                <div class="flex items-center space-x-2">
                    <div class="w-9 h-9 rounded-full bg-gray-200 border-2 border-white shadow-sm overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff" alt="Avatar">
                    </div>
                </div>

                {{-- Dropdown Menu --}}
                <div class="absolute right-0 mt-3 w-48 bg-white shadow-xl rounded-xl p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right border border-gray-100">
                    <div class="px-3 py-2 border-b border-gray-100 mb-2">
                        <p class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>

                    {{-- Menu Khusus Admin --}}
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('roles.index') ?? '#' }}" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                            Role Management
                        </a>
                    @endif

                    <a href="#" class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                        Profile
                    </a>

                    {{-- Secure Logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 rounded-lg text-sm text-red-500 hover:bg-red-50 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            {{-- JIKA BELUM LOGIN --}}
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-black">Login</a>
            <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition">Register</a>
        @endauth
    </div>
</header>