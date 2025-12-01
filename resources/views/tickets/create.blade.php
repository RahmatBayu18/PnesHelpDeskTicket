@extends('layouts.app')

@section('content')
<div class="py-10 px-4 pb-40 bg-gray-50">
    <div class="max-w-3xl mx-auto">
        
        <div class="mb-6">
            <a href="{{ Auth::user()->role === 'mahasiswa' ? route('student.dashboard') : route('tickets.index') }}" 
            class="text-sm text-gray-500 hover:text-blue-600 flex items-center transition group">
                
                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                
                {{-- LOGIKA TEKS: Ubah teks sesuai tujuan --}}
                {{ Auth::user()->role === 'mahasiswa' ? 'Kembali ke Dashboard' : 'Kembali ke Daftar Tiket' }}
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 bg-blue-600 text-white">
                <h1 class="text-2xl font-bold">Buat Tiket Baru</h1>
                <p class="text-blue-100 text-sm mt-1">Sampaikan keluhan atau masalah teknis Anda di sini.</p>
            </div>

            <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Judul --}}
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Masalah</label>
                        <input type="text" name="title" required placeholder="Contoh: Internet lantai 2 mati"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2.5 border placeholder-gray-400">
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2.5 border bg-white">
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Lokasi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" name="location" required placeholder="Contoh: Ruang Server 1"
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2.5 border placeholder-gray-400">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Detail</label>
                        <textarea name="description" rows="4" required placeholder="Jelaskan masalahnya secara rinci..."
                            class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-2.5 border placeholder-gray-400"></textarea>
                    </div>

                    {{-- Upload Foto --}}
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Foto (Opsional)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition cursor-pointer group">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="text-sm text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload a file</span>
                                        <input id="file-upload" name="foto" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1 inline">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        Kirim Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection