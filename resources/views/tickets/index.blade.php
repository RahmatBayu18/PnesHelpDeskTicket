@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header & Notifikasi --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                Dashboard {{ ucfirst(Auth::user()->role) }}
            </h1>
            
            @if(Auth::user()->role === 'mahasiswa')
                <a href="{{ route('tickets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-md">
                    + Buat Tiket
                </a>
            @endif
        </div>

        {{-- Flash Message Success --}}
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- TABLE DASHBOARD (Untuk Admin & Teknisi) --}}
        @if(Auth::user()->role !== 'mahasiswa')
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID & Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tickets as $ticket)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">#{{ $ticket->ticket_code }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($ticket->title, 30) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $ticket->user->username }}</div>
                                    <div class="text-xs text-gray-500">{{ $ticket->user->nim }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $ticket->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($ticket->status=='Open') bg-red-100 text-red-800 
                                        @elseif($ticket->status=='In Progress') bg-yellow-100 text-yellow-800 
                                        @elseif($ticket->status=='Resolved') bg-green-100 text-green-800 
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                
                                {{-- FORM UPDATE STATUS & ASSIGN (Hanya Admin/Teknisi) --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" id="form-{{ $ticket->id }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        @if(Auth::user()->role === 'admin')
                                            <select name="technician_id" onchange="document.getElementById('form-{{ $ticket->id }}').submit()" class="text-xs border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 max-w-[150px]">
                                                <option value="">-- Pilih --</option>
                                                @foreach($technicians as $tech)
                                                    <option value="{{ $tech->id }}" {{ $ticket->technician_id == $tech->id ? 'selected' : '' }}>
                                                        {{ $tech->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="text-sm text-gray-500">{{ $ticket->technician->username ?? 'Belum ada' }}</span>
                                        @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-2">
                                        {{-- Dropdown Status --}}
                                        <select name="status" onchange="document.getElementById('form-{{ $ticket->id }}').submit()" class="block w-full text-xs border-gray-300 rounded shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <option value="Open" {{ $ticket->status=='Open'?'selected':'' }}>Open</option>
                                            <option value="In Progress" {{ $ticket->status=='In Progress'?'selected':'' }}>In Progress</option>
                                            <option value="Resolved" {{ $ticket->status=='Resolved'?'selected':'' }}>Resolved</option>
                                            <option value="Closed" {{ $ticket->status=='Closed'?'selected':'' }}>Closed</option>
                                        </select>
                                    </form> {{-- Tutup form update disini --}}

                                    <div class="flex items-center justify-between mt-2">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-900 text-xs font-bold">Detail</a>
                                        
                                        {{-- TOMBOL DELETE (KHUSUS ADMIN) --}}
                                        @if(Auth::user()->role === 'admin')
                                            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-bold ml-2" title="Hapus Tiket">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $tickets->links() }}
            </div>

        @else
            {{-- TAMPILAN GRID KHUSUS MAHASISWA --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                 @forelse ($tickets as $ticket)
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="block group">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 h-full flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">#{{ $ticket->ticket_code }}</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($ticket->status=='Open') bg-red-100 text-red-800 
                                    @elseif($ticket->status=='In Progress') bg-yellow-100 text-yellow-800 
                                    @elseif($ticket->status=='Resolved') bg-green-100 text-green-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $ticket->status }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-blue-600">{{ $ticket->title }}</h3>
                            <p class="text-sm text-gray-500 mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $ticket->location }}
                            </p>
                            <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                                <span>{{ $ticket->category->name }}</span>
                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        Belum ada tiket.
                    </div>
                @endforelse
            </div>
             <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        @endif

    </div>
</div>
@endsection