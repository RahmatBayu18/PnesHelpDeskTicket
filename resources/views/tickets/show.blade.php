@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI: DETAIL TIKET --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $ticket->title }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Ticket ID: <span class="font-mono bg-gray-100 px-2 rounded text-gray-700">#{{ $ticket->ticket_code }}</span></p>
                    </div>
                    <span class="px-3 py-1 text-sm font-bold rounded-full 
                        @if($ticket->status=='Open') bg-red-100 text-red-700
                        @elseif($ticket->status=='In Progress') bg-yellow-100 text-yellow-700
                        @elseif($ticket->status=='Resolved') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-600 @endif">
                        {{ $ticket->status }}
                    </span>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500">Pelapor</span>
                            {{-- PERBAIKAN: Menggunakan username --}}
                            <span class="font-medium text-gray-800">{{ $ticket->user->username ?? 'User' }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500">Kategori</span>
                            <span class="font-medium text-gray-800">{{ $ticket->category->name }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500">Lokasi</span>
                            <span class="font-medium text-gray-800">{{ $ticket->location }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500">Tanggal</span>
                            <span class="font-medium text-gray-800">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="font-semibold text-gray-900 mb-2">Deskripsi</h3>
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $ticket->description }}</p>
                    </div>

                    @if ($ticket->image_path)
                        <div class="mt-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Lampiran Foto</h3>
                            <div class="rounded-lg overflow-hidden border border-gray-200 w-full md:w-1/2">
                                <img src="{{ asset('storage/'.$ticket->image_path) }}" alt="Lampiran" class="w-full h-auto object-cover">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: KOMENTAR & DISKUSI --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-full flex flex-col">
                <div class="p-5 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                    <h2 class="font-bold text-gray-800">Riwayat Diskusi</h2>
                </div>

                <div class="flex-1 p-5 overflow-y-auto max-h-[600px] space-y-4">
                    @forelse ($ticket->comments as $comment)
                        <div class="flex gap-3 {{ $comment->user_id == Auth::id() ? 'flex-row-reverse' : '' }}">
                            <div class="flex-shrink-0">
                                {{-- PERBAIKAN: Avatar menggunakan inisial username --}}
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600 uppercase">
                                    {{ substr($comment->user->username ?? 'U', 0, 2) }}
                                </div>
                            </div>
                            <div class="max-w-[85%]">
                                <div class="p-3 rounded-lg text-sm shadow-sm 
                                    {{ $comment->user_id == Auth::id() ? 'bg-blue-50 border border-blue-100 text-gray-800' : 'bg-gray-50 border border-gray-200 text-gray-800' }}">
                                    {{-- PERBAIKAN: Nama user menggunakan username --}}
                                    <p class="font-bold text-xs mb-1 {{ $comment->user_id == Auth::id() ? 'text-blue-700' : 'text-gray-600' }}">
                                        {{ $comment->user->username ?? 'User' }}
                                    </p>
                                    <p>{{ $comment->message }}</p>
                                </div>
                                <span class="text-xs text-gray-400 mt-1 block {{ $comment->user_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 text-sm italic py-4">Belum ada komentar.</p>
                    @endforelse
                </div>

                <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                    <form action="{{ route('tickets.comment', $ticket->id) }}" method="POST">
                        @csrf
                        <label class="sr-only">Tulis Komentar</label>
                        <textarea name="message" rows="3" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 p-3 text-sm" placeholder="Tulis balasan..."></textarea>
                        <button type="submit" class="mt-2 w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                            Kirim Balasan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection