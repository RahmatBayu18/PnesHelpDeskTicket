@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto space-y-8">
        
        {{-- HEADER --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengumuman</h1>
                <p class="text-sm text-gray-500 mt-1">Buat dan kelola pengumuman untuk mahasiswa</p>
            </div>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- FORM BUAT PENGUMUMAN --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Pengumuman Baru
            </h2>

            <form action="{{ route('announcements.store') }}" method="POST" class="space-y-4">
                @csrf
                
                {{-- GRID BARIS PERTAMA: Judul, Kategori, Tipe --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    {{-- 1. Input Judul --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman</label>
                        <input type="text" name="title" required 
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm"
                            placeholder="Contoh: Pemeliharaan Server">
                    </div>

                    {{-- 2. Input Kategori (BARU) --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm">
                            <option value="BAAK">BAAK</option>
                            <option value="Keuangan">Keuangan</option>
                            <option value="Kemahasiswaan">Kemahasiswaan</option>
                            <option value="Jurusan">Jurusan</option>
                            <option value="Umum" selected>Umum</option>
                        </select>
                    </div>

                    {{-- 3. Input Tipe --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe / Warna</label>
                        <select name="type" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm">
                            <option value="info">Info (Biru)</option>
                            <option value="warning">Peringatan (Kuning)</option>
                            <option value="danger">Penting (Merah)</option>
                        </select>
                    </div>
                </div>

                {{-- BARIS KEDUA: Isi Konten --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman</label>
                    <textarea name="content" rows="4" required
                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm"
                        placeholder="Tulis detail pengumuman di sini..."></textarea>
                </div>

                {{-- BARIS KETIGA: Notifikasi --}}
                <div class="flex items-center">
                    <input id="send_notification" name="send_notification" type="checkbox" checked
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="send_notification" class="ml-2 block text-sm text-gray-700">
                        Kirim notifikasi ke semua mahasiswa
                    </label>
                </div>

                {{-- TOMBOL SUBMIT --}}
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition shadow-md font-medium text-sm">
                        Publikasikan Pengumuman
                    </button>
                </div>
            </form>
        </div>

        {{-- LIST PENGUMUMAN --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="font-bold text-gray-800">Daftar Pengumuman</h2>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($announcements as $announcement)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="font-bold text-gray-900">{{ $announcement->title }}</h3>
                                    
                                    {{-- Badge Tipe --}}
                                    <span class="px-2 py-1 text-xs font-bold rounded-full
                                        @if($announcement->type == 'info') bg-blue-100 text-blue-700
                                        @elseif($announcement->type == 'warning') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($announcement->type) }}
                                    </span>

                                    {{-- Badge Status --}}
                                    <span class="px-2 py-1 text-xs font-bold rounded-full
                                        {{ $announcement->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $announcement->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mb-2">{{ $announcement->content }}</p>
                                <p class="text-xs text-gray-400">{{ $announcement->created_at->format('d M Y, H:i') }}</p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 ml-4">
                                {{-- Toggle Status --}}
                                <form action="{{ route('announcements.toggle', $announcement->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition
                                        {{ $announcement->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                        {{ $announcement->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p>Belum ada pengumuman</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($announcements->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection