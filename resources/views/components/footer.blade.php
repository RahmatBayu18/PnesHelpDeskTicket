<footer class="mt-8 mb-8 mx-auto w-[95%] md:w-[90%] bg-[#0B1120] text-white rounded-3xl shadow-2xl border border-gray-800 flex flex-col md:flex-row justify-between items-center px-6 py-4">
    
    {{-- Kiri: Brand --}}
    <div class="flex items-center space-x-2 mb-2 md:mb-0">
        <div style="
            height: 38px; 
            display: flex; 
            align-items: center; 
            gap: 8px;
        ">
            <img 
                src="{{ asset('aset/logo-PensHelpDes.svg') }}"
                alt="Logo"
                style="
                    height: 70%;
                    width: auto;
                    object-fit: contain;
                "
                >

                <div style="color: #0056D2; font-size: 20px; font-weight: 600;">
                    Pens<span style="color: #FFC107;">HelpDesk</span>
                </div>
            </div>
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