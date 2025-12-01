@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10 px-4 bg-gray-50 flex justify-center">
    <div class="w-full max-w-4xl">
        
        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-blue-600 flex items-center gap-2 transition font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>

        {{-- KERTAS PENGUMUMAN --}}
        <div class="bg-white shadow-xl rounded-none md:rounded-lg overflow-hidden border-t-[6px] 
            @if($announcement->type == 'danger') border-red-600 
            @elseif($announcement->type == 'warning') border-yellow-500 
            @else border-blue-600 @endif">
            
            <div class="p-8 md:p-12">
                
                {{-- HEADER SURAT --}}
                <div class="border-b-2 border-gray-100 pb-6 mb-8">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 uppercase tracking-wide leading-snug text-center md:text-left">
                        {{ $announcement->title }}
                    </h1>
                    
                    <div class="mt-6 space-y-2 text-sm font-mono text-gray-600 bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] gap-1">
                            <span class="font-bold text-gray-500 uppercase text-xs tracking-wider self-center">Kategori</span>
                            <span class="font-bold text-gray-900 uppercase">: {{ $announcement->category }}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] gap-1">
                            <span class="font-bold text-gray-500 uppercase text-xs tracking-wider self-center">Oleh</span>
                            <span class="font-medium text-gray-900">
                                : {{ $announcement->user->username ?? 'Admin' }} 
                                <span class="text-gray-400 text-xs">({{ ucfirst($announcement->user->role ?? 'Staff') }})</span>
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-[150px_1fr] gap-1">
                            <span class="font-bold text-gray-500 uppercase text-xs tracking-wider self-center">Tanggal Kirim</span>
                            <span class="font-medium text-gray-900 uppercase">: {{ $announcement->created_at->format('d-M-y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- ISI SURAT --}}
                <div class="prose max-w-none text-gray-800 leading-loose text-justify text-lg font-serif">
                    {{-- Judul diulang sebagai pembuka kalimat --}}
                    <p class="font-bold mb-4 uppercase">{{ $announcement->title }}.</p>

                    {{-- Isi Pengumuman (nl2br agar enter terbaca) --}}
                    {!! nl2br(e($announcement->content)) !!}
                    
                    {{-- Link jika ada di dalam teks (otomatisasi sederhana bisa ditambahkan via JS atau regex di controller, tapi manual link text pun oke) --}}
                </div>

                {{-- FOOTER SURAT --}}
                <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-end">
                    <div>
                        <p class="text-gray-800 font-bold">Terimakasih.</p>
                        <a href="https://www.pens.ac.id" target="_blank" class="text-blue-600 hover:underline text-sm font-medium mt-1 block">
                            www.pens.ac.id
                        </a>
                    </div>
                    
                    <div class="mt-6 md:mt-0 text-right">
                        {{-- Stempel Digital Sederhana --}}
                        <div class="inline-block border-2 border-gray-300 text-gray-300 px-3 py-1 rounded text-[10px] font-bold uppercase tracking-widest rotate-[-5deg] opacity-50">
                            PENS OFFICIAL
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection