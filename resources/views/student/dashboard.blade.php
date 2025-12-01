@extends('layouts.app')

@section('content')
{{-- SETUP ALPINE JS: Root Wrapper --}}
<div x-data="{ 
        viewMode: localStorage.getItem('ticketViewMode') || 'grid' 
     }" 
     x-init="$watch('viewMode', val => localStorage.setItem('ticketViewMode', val))"
     class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 pb-40 bg-gray-50">
    
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- BAGIAN 1: HEADER & PENGUMUMAN (Tetap) --}}
        <div class="flex flex-col md:flex-row gap-6">
            {{-- A. Welcome Message --}}
            <div class="w-full md:w-1/3">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 shadow-lg text-white h-full flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-blue-400 opacity-10 rounded-full blur-xl"></div>
                    <div>
                        <h1 class="text-2xl font-bold">Halo, {{ Auth::user()->username }}! 👋</h1>
                        <p class="text-blue-100 text-sm mt-2 leading-relaxed">
                            Selamat datang. Pantau status laporan fasilitas kampus di sini.
                        </p>
                    </div>
                    <a href="{{ route('tickets.create') }}" class="mt-6 w-full bg-white text-blue-700 px-4 py-3 rounded-lg hover:bg-blue-50 transition flex items-center justify-center gap-2 shadow-sm font-bold text-sm group">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Laporan Baru
                    </a>
                </div>
            </div>

            {{-- B. Papan Pengumuman --}}
            <div class="w-full md:w-2/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-full flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
                        <h2 class="font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Papan Pengumuman
                        </h2>
                        @if($announcements->count() > 0)
                            <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded-full font-bold">{{ $announcements->count() }} Terbaru</span>
                        @endif
                    </div>
                    <div class="p-4 overflow-y-auto max-h-[250px] space-y-3 custom-scrollbar">
                        @forelse($announcements as $info)
                            <a href="{{ route('announcements.show', $info->id) }}" class="block group">
                                <div class="relative bg-white border rounded-lg p-4 transition-all duration-200 hover:shadow-md hover:border-blue-300
                                    @if($info->type == 'danger') border-l-4 border-l-red-500 border-gray-200
                                    @elseif($info->type == 'warning') border-l-4 border-l-yellow-500 border-gray-200
                                    @else border-l-4 border-l-blue-500 border-gray-200 @endif">
                                    <div class="flex justify-between items-start mb-1">
                                        <h3 class="font-bold text-gray-900 text-sm group-hover:text-blue-600 transition-colors line-clamp-1">{{ $info->title }}</h3>
                                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200">{{ $info->category ?? 'UMUM' }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 line-clamp-2 mb-2 leading-relaxed">{{ Str::limit($info->content, 120) }}</p>
                                    <div class="flex justify-between items-center border-t border-gray-50 pt-2 mt-2">
                                        <div class="flex items-center text-[10px] text-gray-400">{{ $info->created_at->format('d M Y') }}</div>
                                        <span class="text-[10px] font-bold text-blue-600 group-hover:underline">Baca Surat &rarr;</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="flex flex-col items-center justify-center h-40 text-gray-400"><p class="text-sm">Tidak ada pengumuman terbaru.</p></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: CONTROL PANEL (TAMPILAN & FILTER) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            {{-- A. Header Panel: Judul & Tombol Ganti Tampilan --}}
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Daftar Tiket Fasilitas
                </h3>

                {{-- Toggle View (Paling Atas Kanan) --}}
                <div class="w-full xl:w-auto flex justify-end">
                    <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-200 flex items-center space-x-3">
                        <span class="text-xs font-medium text-gray-500 pl-2">Tampilan:</span>
                        <div class="flex bg-gray-100 p-1 rounded-lg">
                            {{-- Tombol Grid --}}
                            <button type="button" @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' 
                                    ? 'bg-white text-blue-600 shadow-sm' 
                                    : 'text-gray-400 hover:text-gray-600'"
                                class="px-2 py-1.5 rounded-md transition-all duration-200 flex items-center justify-center"
                                title="Tampilan Grid">

                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 
                                        012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0-01-2-2V6zM4 16a2 2 0 012-2h2a2 2 
                                        0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 
                                        012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>

                            {{-- Tombol List --}}
                            <button type="button" @click="viewMode = 'list'"
                                :class="viewMode === 'list' 
                                    ? 'bg-white text-blue-600 shadow-sm' 
                                    : 'text-gray-400 hover:text-gray-600'"
                                class="px-2 py-1.5 rounded-md transition-all duration-200 flex items-center justify-center"
                                title="Tampilan List">

                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- B. Body Panel: Form Filter --}}
            <div class="p-5 bg-white">
                <form action="{{ route('student.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    {{-- Search --}}
                    <div class="md:col-span-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Pencarian</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kerusakan..." class="w-full pl-9 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    {{-- Tanggal --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    {{-- Tombol Filter --}}
                    <div class="md:col-span-2 flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-gray-900 text-white py-2 rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                            <a href="{{ route('student.dashboard') }}" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Reset">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- BAGIAN 3: DAFTAR TIKET --}}
        
        {{-- MODE GRID --}}
        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($tickets as $ticket)
            <a href="{{ route('tickets.show', $ticket->id) }}" class="block group h-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 h-full flex flex-col">
                    <div class="relative h-48 bg-gray-100 overflow-hidden border-b border-gray-100">
                        @if($ticket->image_path)
                            <img src="{{ asset('storage/' . $ticket->image_path) }}" alt="Bukti" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs">No Image</span>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full shadow-sm backdrop-blur-md border border-white/20 text-white
                                @if($ticket->status=='Open') bg-red-500/90 
                                @elseif($ticket->status=='In Progress') bg-yellow-500/90 
                                @else bg-green-500/90 @endif">
                                {{ $ticket->status }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-center mb-2 text-xs text-gray-500">
                            <span class="font-semibold text-gray-700">{{ $ticket->user->username ?? 'Anonim' }}</span>
                            <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded">#{{ $ticket->ticket_code }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $ticket->title }}</h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1">{{ $ticket->description }}</p>
                        <div class="border-t border-gray-50 pt-3 mt-auto flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mr-1 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ Str::limit($ticket->location, 15) }}
                            </div>
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-100">{{ $ticket->category->name ?? 'Umum' }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-xl border border-dashed border-gray-300">Belum ada tiket.</div>
            @endforelse
        </div>

        {{-- MODE LIST --}}
        <div x-show="viewMode === 'list'" class="grid grid-cols-1 md:grid-cols-2 gap-4" style="display: none;">
            @foreach ($tickets as $ticket)
                @php
                    if($ticket->status == 'Open') {
                        $bgColor = 'bg-yellow-50'; $borderColor = 'border-yellow-200'; $iconBg = 'bg-yellow-500'; $textColor = 'text-yellow-800';
                        $icon = '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>';
                        $statusText = 'Menunggu penugasan teknisi';
                    } elseif($ticket->status == 'In Progress') {
                        $bgColor = 'bg-blue-50'; $borderColor = 'border-blue-200'; $iconBg = 'bg-blue-500'; $textColor = 'text-blue-800';
                        $icon = '<svg class="w-6 h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
                        $statusText = 'Teknisi sedang menangani';
                    } else {
                        $bgColor = 'bg-green-50'; $borderColor = 'border-green-200'; $iconBg = 'bg-green-500'; $textColor = 'text-green-800';
                        $icon = '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                        $statusText = 'Masalah telah diselesaikan';
                    }
                @endphp
                <a href="{{ route('tickets.show', $ticket->id) }}" class="block group">
                    <div class="relative p-5 rounded-xl shadow-sm border {{ $borderColor }} {{ $bgColor }} hover:shadow-md transition-all duration-300 flex items-start gap-4">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 rounded-l-xl {{ str_replace('bg-', 'bg-', $iconBg) }}"></div>
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $iconBg }} flex items-center justify-center shadow-sm">
                            {!! $icon !!}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <h3 class="text-base font-bold text-gray-900 group-hover:text-blue-600 transition truncate pr-2">{{ $ticket->title }}</h3>
                                <span class="flex-shrink-0 inline-block px-2 py-0.5 text-[10px] font-mono font-bold bg-white border border-gray-200 rounded text-gray-500 shadow-sm">#{{ $ticket->ticket_code }}</span>
                            </div>
                            <p class="text-sm {{ $textColor }} font-medium mt-0.5">{{ $statusText }}</p>
                            <div class="flex items-center gap-3 mt-3 text-xs text-gray-500">
                                <div class="flex items-center"><svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $ticket->created_at->diffForHumans() }}</div>
                                <div class="flex items-center"><svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>{{ $ticket->user->username }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
            @if($tickets->isEmpty())
                <div class="col-span-full py-12 text-center bg-white rounded-xl border border-dashed border-gray-300">Belum ada tiket.</div>
            @endif
        </div>

        {{-- PAGINATION --}}
        <div class="mt-8">{{ $tickets->withQueryString()->links() }}</div>
    </div>
</div>

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
@endpush
@endsection