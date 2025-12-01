@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-7 h-7 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Pengaturan Profil
            </h1>
            <p class="text-sm text-gray-500 mt-2">Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        {{-- Success Messages --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Left Column: Profile Picture --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Foto Profil</h2>
                    
                    <div class="flex flex-col items-center">
                        {{-- Profile Picture Display --}}
                        <div class="relative group">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                            @else
                                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center border-4 border-gray-200">
                                    <span class="text-4xl font-bold text-white">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Upload Form --}}
                        <form action="{{ route('profile.picture.update') }}" method="POST" enctype="multipart/form-data" class="mt-4 w-full">
                            @csrf
                            <label for="profile_picture" class="block">
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" onchange="this.form.submit()">
                                <span class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium cursor-pointer flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Upload Foto
                                </span>
                            </label>
                            @error('profile_picture')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </form>

                        {{-- Delete Picture --}}
                        @if($user->profile_picture)
                            <form action="{{ route('profile.picture.delete') }}" method="POST" class="mt-2 w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-red-600 hover:text-red-800 text-sm font-medium py-2" onclick="return confirm('Hapus foto profil?')">
                                    Hapus Foto
                                </button>
                            </form>
                        @endif

                        <p class="text-xs text-gray-500 mt-3 text-center">JPG, PNG atau GIF (Max. 2MB)</p>
                    </div>
                </div>
            </div>

            {{-- Right Column: Profile Info & Password --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Profile Information --}}
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Profil</h2>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Username --}}
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @error('username')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NIM --}}
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                            <input type="text" id="nim" name="nim" value="{{ old('nim', $user->nim) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @error('nim')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Role (Read-only) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <input type="text" value="{{ ucfirst($user->role) }}" disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Password Change --}}
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ubah Password</h2>
                    
                    <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Current Password --}}
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-end">
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition font-medium shadow-sm">
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
