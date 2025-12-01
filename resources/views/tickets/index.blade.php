@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8 pb-40 bg-gray-50">
    <div class="max-w-7xl mx-auto space-y-8">
        
        {{-- HEADER DASHBOARD --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Dashboard {{ ucfirst(Auth::user()->role) }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">Pantau kinerja sistem dan kelola laporan masuk.</p>
            </div>
            
            {{-- Tombol Buat Tiket (Manual oleh Admin/Teknisi) --}}
            <a href="{{ route('tickets.create') }}" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center gap-2 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Input Tiket Manual
            </a>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- BAGIAN 1: STATISTIK & GRAFIK --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Kiri: Statistik Angka & Grafik Garis --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Kartu Statistik --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    {{-- Total --}}
                    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col items-center justify-center">
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Tiket</div>
                        <div class="text-3xl font-extrabold text-gray-900 mt-2">{{ $stats['total'] }}</div>
                    </div>
                    {{-- Open --}}
                    <div class="bg-red-50 p-5 rounded-xl shadow-sm border border-red-100 flex flex-col items-center justify-center">
                        <div class="text-red-500 text-xs font-bold uppercase tracking-wider">Open</div>
                        <div class="text-3xl font-extrabold text-red-600 mt-2">{{ $stats['open'] }}</div>
                    </div>
                    {{-- In Progress --}}
                    <div class="bg-yellow-50 p-5 rounded-xl shadow-sm border border-yellow-100 flex flex-col items-center justify-center">
                        <div class="text-yellow-600 text-xs font-bold uppercase tracking-wider">On Progress</div>
                        <div class="text-3xl font-extrabold text-yellow-700 mt-2">{{ $stats['progress'] }}</div>
                    </div>
                    {{-- Selesai --}}
                    <div class="bg-green-50 p-5 rounded-xl shadow-sm border border-green-100 flex flex-col items-center justify-center">
                        <div class="text-green-600 text-xs font-bold uppercase tracking-wider">Selesai</div>
                        <div class="text-3xl font-extrabold text-green-700 mt-2">{{ $stats['resolved'] + $stats['closed'] }}</div>
                    </div>
                </div>

                {{-- Grafik Tren --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-800 mb-6 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        Tren Laporan Masuk ({{ date('Y') }})
                    </h3>
                    <div class="h-72 w-full">
                        <canvas id="ticketChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Kanan: Pie Chart Status --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <h3 class="text-sm font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    Komposisi Status
                </h3>
                <div class="flex-1 flex items-center justify-center relative">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: TABEL MANAJEMEN TIKET --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
            {{-- Toolbar Tabel --}}
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Daftar Tiket Masuk
                </h3>
                
                {{-- Filter --}}
                <form action="{{ route('tickets.index') }}" method="GET" class="flex items-center">
                    <label for="status" class="mr-2 text-sm text-gray-600 font-medium">Filter:</label>
                    <select name="status" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-3 pr-10 py-2">
                        <option value="">Semua Status</option>
                        <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </form>
            </div>

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Info Tiket</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Teknisi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            {{-- Kolom 1: Tiket --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="hover:text-blue-600 hover:underline">
                                                #{{ $ticket->ticket_code }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500 line-clamp-1 mt-0.5">{{ $ticket->title }}</div>
                                        <div class="text-xs text-blue-500 mt-1 bg-blue-50 inline-block px-1.5 py-0.5 rounded border border-blue-100">
                                            {{ $ticket->category->name ?? 'Umum' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom 2: Pelapor --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->user->username }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $ticket->created_at->format('d M Y, H:i') }}</div>
                                <div class="text-xs text-gray-400">({{ $ticket->created_at->diffForHumans() }})</div>
                            </td>

                            {{-- Kolom 3: Status --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                    @if($ticket->status=='Open') bg-red-100 text-red-800 border border-red-200
                                    @elseif($ticket->status=='In Progress') bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @elseif($ticket->status=='Resolved') bg-green-100 text-green-800 border border-green-200
                                    @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                    {{ $ticket->status }}
                                </span>
                            </td>

                            {{-- Kolom 4: Assign Teknisi --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" id="form-tech-{{ $ticket->id }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="{{ $ticket->status }}"> 
                                    
                                    @if(Auth::user()->role === 'admin')
                                        <select name="technician_id" onchange="document.getElementById('form-tech-{{ $ticket->id }}').submit()" 
                                            class="text-xs border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full max-w-[140px] py-1.5 shadow-sm">
                                            <option value="">-- Belum Ada --</option>
                                            @foreach($technicians as $tech)
                                                <option value="{{ $tech->id }}" {{ $ticket->technician_id == $tech->id ? 'selected' : '' }}>
                                                    {{ $tech->username }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <span class="text-sm text-gray-600 font-medium">
                                            {{ $ticket->technician->username ?? '-' }}
                                        </span>
                                    @endif
                                </form>
                            </td>

                            {{-- Kolom 5: Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800 font-bold transition">
                                        Detail
                                    </a>
                                    
                                    @if(Auth::user()->role === 'admin')
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Hapus tiket ini secara permanen?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <p class="text-base font-medium">Belum ada tiket yang masuk.</p>
                                <p class="text-sm mt-1">Laporan baru akan muncul di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $tickets->withQueryString()->links() }}
            </div>
        </div>

    </div>
</div>

{{-- SCRIPT CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Konfigurasi Chart.js
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6B7280';

    // 1. Line Chart (Tren Bulanan)
    const ctxTicket = document.getElementById('ticketChart').getContext('2d');
    new Chart(ctxTicket, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Laporan Masuk',
                data: @json($chartFormatted),
                borderColor: '#3B82F6', // Blue 500
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
                    return gradient;
                },
                borderWidth: 2,
                pointBackgroundColor: '#FFFFFF',
                pointBorderColor: '#3B82F6',
                pointHoverBackgroundColor: '#3B82F6',
                pointHoverBorderColor: '#FFFFFF',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1F2937',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 13 },
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                }
            },
            scales: { 
                y: { 
                    beginAtZero: true, 
                    grid: { borderDash: [4, 4], color: '#E5E7EB' },
                    ticks: { padding: 10 }
                }, 
                x: { 
                    grid: { display: false },
                    ticks: { padding: 10 }
                } 
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // 2. Doughnut Chart (Komposisi Status)
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'On Progress', 'Selesai'],
            datasets: [{
                data: [{{ $stats['open'] }}, {{ $stats['progress'] }}, {{ $stats['resolved'] + $stats['closed'] }}],
                backgroundColor: [
                    '#EF4444', // Red 500
                    '#F59E0B', // Yellow 500
                    '#10B981'  // Green 500
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        usePointStyle: true, 
                        padding: 20,
                        font: { size: 12, weight: 'bold' } 
                    } 
                }
            }
        }
    });
</script>
@endsection