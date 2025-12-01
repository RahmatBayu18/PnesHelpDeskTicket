<footer class="relative bottom-6 left-1/2 -translate-x-1/2 w-[95%] md:w-[90%] bg-[#0B1120] text-white rounded-3xl shadow-2xl z-40 border border-gray-800 flex flex-col md:flex-row justify-between items-center px-6 py-4">
    
    {{-- Kiri: Brand --}}
    <div class="flex items-center space-x-2 mb-2 md:mb-0">
        <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-lg shadow-blue-900/50">
            P
        </div>
        <span class="font-bold tracking-tight text-white text-sm">PensHelpDesk</span>
    </div>

    {{-- Tengah: Copyright (Hidden di HP biar gak penuh, muncul di MD ke atas) --}}
    <div class="text-xs text-gray-500 hidden md:block">
        &copy; {{ date('Y') }} Mahasiswa PENS. All rights reserved.
    </div>

    {{-- Kanan: Link Simpel --}}
    <div class="flex items-center space-x-4">
        <a href="#" class="text-xs text-gray-400 hover:text-blue-400 transition-colors">Bantuan</a>
        <a href="#" class="text-xs text-gray-400 hover:text-blue-400 transition-colors">Privacy</a>
    </div>

</footer>