@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-7xl mx-auto space-y-6">
        
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Dashboard {{ ucfirst(Auth::user()->role) }}
                </h1>
                <p class="text-sm text-gray-500">Ringkasan aktivitas dan laporan fasilitas.</p>
            </div>
            
            @if(Auth::user()->role === 'mahasiswa')
                <a href="{{ route('tickets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Tiket
                </a>
            @endif
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- BAGIAN STATISTIK & CHART (Hanya Tampil untuk Admin/Teknisi) --}}
        @if(Auth::user()->role !== 'mahasiswa')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Kiri: Statistik Kartu (2/3 lebar di layar besar) --}}
                <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-4">
                    {{-- Total --}}
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
                        <div class="text-gray-500 text-xs font-medium uppercase">Total Tiket</div>
                        <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</div>
                    </div>
                    {{-- Open --}}
                    <div class="bg-red-50 p-4 rounded-xl shadow-sm border border-red-100 flex flex-col items-center justify-center">
                        <div class="text-red-500 text-xs font-medium uppercase">Open</div>
                        <div class="text-3xl font-bold text-red-600 mt-1">{{ $stats['open'] }}</div>
                    </div>
                    {{-- In Progress --}}
                    <div class="bg-yellow-50 p-4 rounded-xl shadow-sm border border-yellow-100 flex flex-col items-center justify-center">
                        <div class="text-yellow-600 text-xs font-medium uppercase">In Progress</div>
                        <div class="text-3xl font-bold text-yellow-700 mt-1">{{ $stats['progress'] }}</div>
                    </div>
                    {{-- Selesai (Resolved + Closed) --}}
                    <div class="bg-green-50 p-4 rounded-xl shadow-sm border border-green-100 flex flex-col items-center justify-center">
                        <div class="text-green-600 text-xs font-medium uppercase">Selesai</div>
                        <div class="text-3xl font-bold text-green-700 mt-1">{{ $stats['resolved'] + $stats['closed'] }}</div>
                    </div>

                    {{-- Grafik (Melebar ke bawah kartu statistik) --}}
                    <div class="col-span-2 sm:col-span-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-sm font-bold text-gray-700 mb-4">Tren Tiket Masuk (Tahun Ini)</h3>
                        <div class="h-64">
                            <canvas id="ticketChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Pie Chart Ringkas (Status) --}}
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col">
                    <h3 class="text-sm font-bold text-gray-700 mb-2">Komposisi Status</h3>
                    <div class="flex-1 flex items-center justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        @endif


        {{-- BAGIAN TABEL / LIST TIKET --}}
        @if(Auth::user()->role !== 'mahasiswa')
            {{-- TAMPILAN ADMIN & TEKNISI (TABEL) --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Daftar Tiket Masuk</h3>
                    {{-- Filter Sederhana --}}
                    <form action="{{ route('tickets.index') }}" method="GET">
                        <select name="status" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tiket</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelapor</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Teknisi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">#{{ $ticket->ticket_code }}</div>
                                    <div class="text-sm text-gray-500 line-clamp-1">{{ $ticket->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-0">
                                            <div class="text-sm font-medium text-gray-900">{{ $ticket->user->username }}</div>
                                            <div class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($ticket->status=='Open') bg-red-100 text-red-800 
                                        @elseif($ticket->status=='In Progress') bg-yellow-100 text-yellow-800 
                                        @elseif($ticket->status=='Resolved') bg-green-100 text-green-800 
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" id="form-tech-{{ $ticket->id }}">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="{{ $ticket->status }}"> {{-- Pertahankan status saat ganti teknisi --}}
                                        
                                        @if(Auth::user()->role === 'admin')
                                            <select name="technician_id" onchange="document.getElementById('form-tech-{{ $ticket->id }}').submit()" class="text-xs border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 w-32">
                                                <option value="">-- Pilih --</option>
                                                @foreach($technicians as $tech)
                                                    <option value="{{ $tech->id }}" {{ $ticket->technician_id == $tech->id ? 'selected' : '' }}>
                                                        {{ $tech->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="text-sm text-gray-500">{{ $ticket->technician->username ?? '-' }}</span>
                                        @endif
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                        
                                        @if(Auth::user()->role === 'admin')
                                            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
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
            <div class="mt-4">{{ $tickets->withQueryString()->links() }}</div>

        @else
            {{-- TAMPILAN MAHASISWA (GRID) - Code asli Anda tetap disini --}}
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
                    <div class="col-span-full text-center py-12 text-gray-500">Belum ada tiket.</div>
                @endforelse
            </div>
            <div class="mt-4">{{ $tickets->links() }}</div>
        @endif

    </div>
</div>

{{-- SCRIPT CHART.JS --}}
@if(Auth::user()->role !== 'mahasiswa')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Line Chart (Tren Bulanan)
    const ctxTicket = document.getElementById('ticketChart').getContext('2d');
    new Chart(ctxTicket, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Tiket Masuk',
                data: @json($chartFormatted), // Data dari Controller
                borderColor: '#3B82F6', // Blue 500
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { borderDash: [2, 2] } }, x: { grid: { display: false } } }
        }
    });

    // 2. Doughnut Chart (Komposisi Status)
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'In Progress', 'Selesai'],
            datasets: [{
                data: [{{ $stats['open'] }}, {{ $stats['progress'] }}, {{ $stats['resolved'] + $stats['closed'] }}],
                backgroundColor: ['#EF4444', '#F59E0B', '#10B981'], // Red, Yellow, Green
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 11 } } }
            }
        }
    });
</script>
@endif

@endsection