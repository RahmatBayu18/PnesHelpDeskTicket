@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- BAGIAN 1: HEADER & PENGUMUMAN --}}
        <div class="flex flex-col md:flex-row gap-6">
            {{-- Welcome Message (Kiri) --}}
            <div class="w-full md:w-1/3">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 h-full">
                    <h1 class="text-2xl font-bold text-gray-900">Halo, {{ Auth::user()->username }}! 👋</h1>
                    <p class="text-sm text-gray-500 mt-2">Ini adalah daftar laporan fasilitas terkini di seluruh kampus.</p>
                    <a href="{{ route('tickets.create') }}" class="mt-4 w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Laporan Baru
                    </a>
                </div>
            </div>

            {{-- Papan Pengumuman (Kanan - Scrollable jika banyak) --}}
            <div class="w-full md:w-2/3">
                @if($announcements->count() > 0)
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100 h-full max-h-48 overflow-y-auto">
                        <h2 class="font-bold text-blue-800 flex items-center mb-3">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            Pengumuman Admin
                        </h2>
                        <div class="space-y-3">
                            @foreach($announcements as $info)
                                <div class="bg-white/60 p-3 rounded-lg border border-blue-100">
                                    <h3 class="font-semibold text-blue-900 text-sm">{{ $info->title }}</h3>
                                    <p class="text-blue-700 text-xs mt-1">{{ $info->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl p-6 border border-gray-200 h-full flex items-center justify-center text-gray-400 text-sm">
                        Tidak ada pengumuman terbaru.
                    </div>
                @endif
            </div>
        </div>

        {{-- BAGIAN 2: FILTER & PENCARIAN (Sama persis logic-nya, tapi action ke student.dashboard) --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            <form action="{{ route('student.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                
                {{-- Search --}}
                <div class="md:col-span-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari Tiket Publik</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kerusakan..." class="w-full pl-9 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                {{-- Status --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua</option>
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
                    <button type="submit" class="flex-1 bg-gray-900 text-white py-2.5 rounded-lg hover:bg-gray-800 transition text-sm font-medium">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('student.dashboard') }}" class="px-3 py-2.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Reset">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- BAGIAN 3: TIMELINE TIKET (SEMUA USER) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($tickets as $ticket)
            <a href="{{ route('tickets.show', $ticket->id) }}" class="block group h-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 h-full flex flex-col">
                    
                    {{-- Gambar --}}
                    <div class="relative h-48 bg-gray-100 overflow-hidden border-b border-gray-100">
                        @if($ticket->image_path)
                            <img src="{{ asset('storage/' . $ticket->image_path) }}" alt="Bukti" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs">No Image</span>
                            </div>
                        @endif

                        {{-- Status Badge --}}
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 text-xs font-bold rounded-full shadow-sm backdrop-blur-md border border-white/20
                                @if($ticket->status=='Open') bg-red-500/90 text-white 
                                @elseif($ticket->status=='In Progress') bg-yellow-500/90 text-white 
                                @elseif($ticket->status=='Resolved') bg-green-500/90 text-white 
                                @else bg-gray-500/90 text-white @endif">
                                {{ $ticket->status }}
                            </span>
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div class="p-5 flex-1 flex flex-col">
                        {{-- Header Kecil: Pelapor & Waktu --}}
                        <div class="flex justify-between items-center mb-2 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <span class="font-semibold text-gray-700">{{ $ticket->user->username ?? 'Anonim' }}</span>
                                <span>•</span>
                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded">#{{ $ticket->ticket_code }}</span>
                        </div>

                        {{-- Judul --}}
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            {{ $ticket->title }}
                        </h3>
                        
                        {{-- Deskripsi --}}
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1">
                            {{ $ticket->description }}
                        </p>

                        {{-- Footer: Lokasi & Kategori --}}
                        <div class="border-t border-gray-50 pt-3 mt-auto flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mr-1 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ Str::limit($ticket->location, 15) }}
                            </div>
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-100">
                                {{ $ticket->category->name ?? 'Umum' }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-xl border border-dashed border-gray-300">
                    <div class="mb-2 text-4xl">📭</div>
                    <h3 class="text-gray-900 font-medium">Belum ada laporan masuk</h3>
                    <p class="text-gray-500 text-sm">Jadilah yang pertama melaporkan masalah!</p>
                </div>
            @endforelse
        </div>

        {{-- BAGIAN 4: PAGINATION --}}
        <div class="mt-8">
            {{ $tickets->withQueryString()->links() }}
        </div>

    </div>
</div>
@endsection