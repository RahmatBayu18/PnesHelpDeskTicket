@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8 px-4 pb-5 bg-gray-50">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">

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
        <div class="lg:col-span-1 space-y-6">
            
            {{-- FORM UPDATE STATUS (ADMIN & TEKNISI ONLY) --}}
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'teknisi')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h2 class="font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Kelola Tiket
                    </h2>
                    
                    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        {{-- Status Update --}}
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status Tiket</label>
                            <select name="status" id="status" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>🔴 Open</option>
                                <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>🟡 In Progress</option>
                                <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>🟢 Resolved</option>
                                <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>⚫ Closed</option>
                            </select>
                        </div>
                        
                        {{-- Technician Assignment (ADMIN ONLY) --}}
                        @if(Auth::user()->role === 'admin')
                            <div>
                                <label for="technician_id" class="block text-sm font-semibold text-gray-700 mb-2">Tugaskan Teknisi</label>
                                <select name="technician_id" id="technician_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">-- Belum Ditugaskan --</option>
                                    @foreach(\App\Models\User::where('role', 'teknisi')->get() as $tech)
                                        <option value="{{ $tech->id }}" {{ $ticket->technician_id == $tech->id ? 'selected' : '' }}>
                                            {{ $tech->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        
                        {{-- Current Technician Info --}}
                        @if($ticket->technician)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <p class="text-xs font-semibold text-blue-700 mb-1">Teknisi Saat Ini:</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($ticket->technician->username, 0, 2) }}
                                    </div>
                                    <span class="text-sm font-medium text-blue-900">{{ $ticket->technician->username }}</span>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-xs text-yellow-700">
                                    <span class="font-semibold">⚠️ Belum ada teknisi yang ditugaskan</span>
                                </p>
                            </div>
                        @endif
                        
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-bold hover:bg-blue-700 transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Tiket
                        </button>
                    </form>
                </div>
            @endif
            
            {{-- DISKUSI --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col">
                <div class="p-5 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                    <h2 class="font-bold text-gray-800">Riwayat Diskusi</h2>
                </div>

                <div class="p-5 overflow-y-auto max-h-[400px] space-y-4">
                    @forelse ($ticket->comments as $comment)
                        <div class="flex gap-3 {{ $comment->user_id == Auth::id() ? 'flex-row-reverse' : '' }}">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-600 uppercase">
                                    {{ substr($comment->user->username ?? 'U', 0, 2) }}
                                </div>
                            </div>
                            <div class="max-w-[85%]">
                                <div class="p-3 rounded-lg text-sm shadow-sm 
                                    {{ $comment->user_id == Auth::id() ? 'bg-blue-50 border border-blue-100 text-gray-800' : 'bg-gray-50 border border-gray-200 text-gray-800' }}">
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