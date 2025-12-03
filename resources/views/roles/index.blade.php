@extends('layouts.app')

@section('content')
<div class="min-h-screen py-6 md:py-10 px-4 bg-gray-50">
    <div class="max-w-5xl mx-auto space-y-6">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Role Pengguna</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola akses dan peran pengguna dalam sistem</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- CONTAINER UTAMA --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            {{-- HEADER TABEL (HANYA MUNCUL DI DESKTOP) --}}
            <div class="hidden md:grid md:grid-cols-12 gap-4 px-6 py-3 bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                <div class="col-span-4">Nama & Username</div>
                <div class="col-span-3">NIM/ID</div>
                <div class="col-span-2">Role Saat Ini</div>
                <div class="col-span-3 text-right">Aksi</div>
            </div>

            {{-- LIST USER --}}
            <div class="divide-y divide-gray-200">
                @foreach($users as $user)
                <div class="p-4 md:px-6 md:py-4 hover:bg-gray-50 transition duration-150">
                    
                    {{-- GRID SYSTEM --}}
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-4 md:items-center">
                        
                        {{-- 1. User Info --}}
                        <div class="md:col-span-4 flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm border border-blue-200">
                                {{ substr(strtoupper($user->username), 0, 2) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-gray-900 truncate">{{ $user->username }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                            </div>
                        </div>

                        {{-- 2. NIM --}}
                        <div class="md:col-span-3 flex md:block justify-between items-center text-sm">
                            <span class="md:hidden font-medium text-gray-500 text-xs uppercase">NIM/ID:</span>
                            <span class="text-gray-700 font-mono bg-gray-100 md:bg-transparent px-2 py-0.5 rounded md:p-0">{{ $user->nim }}</span>
                        </div>

                        {{-- 3. Role Badge --}}
                        <div class="md:col-span-2 flex md:block justify-between items-center">
                            <span class="md:hidden font-medium text-gray-500 text-xs uppercase">Role:</span>
                            <span class="px-2.5 py-1 md:py-0.5 inline-flex text-xs leading-5 font-bold rounded-full 
                                @if($user->role == 'admin') bg-purple-100 text-purple-700 border border-purple-200
                                @elseif($user->role == 'teknisi') bg-blue-100 text-blue-700 border border-blue-200
                                @else bg-green-100 text-green-700 border border-green-200 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        {{-- 4. Action Form (Update & Delete) --}}
                        <div class="md:col-span-3 mt-3 md:mt-0 flex flex-col md:flex-row md:items-center md:justify-end gap-2">
                            
                            {{-- Form Update Role --}}
                            <form action="{{ route('roles.update', $user->id) }}" method="POST" 
                                class="flex-1 md:flex-none flex items-center justify-between md:justify-end space-x-2 bg-gray-50 md:bg-transparent p-2 md:p-0 rounded-lg md:rounded-none border md:border-none border-gray-100">
                                @csrf
                                @method('PUT')
                                
                                <span class="md:hidden text-xs font-bold text-gray-500 ml-1">Role:</span>
                                
                                <div class="flex items-center space-x-2">
                                    <select name="role" class="text-xs border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1.5 pl-2 pr-7 cursor-pointer">
                                        <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                        <option value="teknisi" {{ $user->role == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1.5 px-3 rounded-lg transition shadow-sm">
                                        Simpan
                                    </button>
                                </div>
                            </form>

                            {{-- Form Delete (Ikon Tong Sampah) --}}
                            {{-- Mengarah ke route roles.destroy sesuai yang ada di controller --}}
                            <form action="{{ route('roles.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->username }}? Data tidak bisa dikembalikan.');"
                                class="flex-1 md:flex-none">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full md:w-auto flex items-center justify-center px-3 py-1.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 rounded-lg transition shadow-sm group" title="Hapus User">
                                    {{-- Icon Sampah --}}
                                    <svg class="w-4 h-4 md:mr-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    {{-- Teks hanya muncul di Mobile --}}
                                    <span class="md:hidden ml-2 text-xs font-bold">Hapus User</span>
                                </button>
                            </form>

                        </div>

                    </div>
                </div>
                @endforeach
            </div>
            
            {{-- Pesan jika data kosong --}}
            @if($users->isEmpty())
                <div class="p-10 text-center flex flex-col items-center justify-center text-gray-500">
                    <svg class="w-10 h-10 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <p class="text-sm">Belum ada pengguna lain terdaftar.</p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection