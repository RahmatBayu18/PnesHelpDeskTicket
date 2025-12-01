@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 pb-24 bg-gray-50">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- BAGIAN 2: HEADER & TOMBOL BUAT --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tiket Saya</h1>
                <p class="text-sm text-gray-500">Kelola dan pantau status laporan Anda.</p>
            </div>
            <a href="{{ route('tickets.create') }}" class="w-full md:w-auto bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-md flex items-center justify-center gap-2 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Tiket Baru
            </a>
        </div>

        {{-- BAGIAN 3: FILTER & PENCARIAN --}}
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
            {{-- Form mengarah ke route 'tickets.my_tickets' sesuai web.php Anda --}}
            <form action="{{ route('tickets.my_tickets') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                
                {{-- Search --}}
                <div class="md:col-span-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari (Judul / Kode)</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: AC Rusak..." class="w-full pl-9 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
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
                        Terapkan
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                        <a href="{{ route('tickets.my_tickets') }}" class="px-3 py-2.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Reset Filter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- BAGIAN 4: LIST TIKET (GRID + GAMBAR) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($tickets as $ticket)
            <a href="{{ route('tickets.show', $ticket->id) }}" class="block group h-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 h-full flex flex-col">
                    
                    {{-- Area Gambar --}}
                    <div class="relative h-48 bg-gray-100 overflow-hidden border-b border-gray-100">
                        @if($ticket->image_path)
                            {{-- Menggunakan asset() untuk memanggil gambar dari public/storage --}}
                            <img src="{{ asset('storage/' . $ticket->image_path) }}" alt="Bukti" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            {{-- Placeholder jika tidak ada gambar --}}
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-xs">Tidak ada gambar</span>
                            </div>
                        @endif

                        {{-- Badge Status di Pojok Kanan Atas --}}
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

                    {{-- Konten Text --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">#{{ $ticket->ticket_code }}</span>
                            <span class="text-xs text-gray-400">{{ $ticket->created_at->diffForHumans() }}</span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            {{ $ticket->title }}
                        </h3>
                        
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1">
                            {{ $ticket->description }}
                        </p>

                        <div class="border-t border-gray-50 pt-3 mt-auto flex items-center justify-between text-xs text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ Str::limit($ticket->location, 15) }}
                            </div>
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded">
                                {{ $ticket->category->name ?? 'Umum' }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
                <div class="col-span-full py-16 text-center">
                    <div class="bg-white rounded-full h-20 w-20 flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Tidak ditemukan tiket</h3>
                    <p class="text-gray-500 mt-1">Coba ubah filter pencarian Anda atau buat tiket baru.</p>
                </div>
            @endforelse
        </div>

        {{-- BAGIAN 5: PAGINATION --}}
        <div class="mt-8">
            {{-- withQueryString() wajib agar filter tidak hilang saat ganti halaman --}}
            {{ $tickets->withQueryString()->links() }}
        </div>

    </div>
</div>
@endsection