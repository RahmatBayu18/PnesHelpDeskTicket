<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PensHelpDesk - Sistem Helpdesk Kampus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- GSAP Core -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/ScrollTrigger.min.js"></script>
    
    <!-- Particles.js for background effect -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #2563eb 0%, #fbbf24 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-blue-yellow {
            background: linear-gradient(135deg, #1e40af 0%, #fbbf24 100%);
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: blob-animation 8s ease-in-out infinite;
        }
        
        @keyframes blob-animation {
            0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
            50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
            75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
        }
        
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .ticket-card {
            position: relative;
            overflow: hidden;
        }
        
        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.3) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }
        
        .ticket-card:hover::before {
            transform: translateX(100%);
        }
        
        /* Initial state for animations */
        .feature-card,
        .feature-header,
        .step-card,
        .how-header,
        .cta-content > * {
            opacity: 1; /* Start visible, GSAP will handle animation */
        }
    </style>
</head>
<body class="bg-gray-50 overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed top-6 left-1/2 -translate-x-1/2 w-[95%] md:w-[90%] bg-white/90 backdrop-blur-md shadow-lg rounded-full px-6 py-3 flex justify-between items-center transition-all duration-300" id="navbar" style="z-index: 9999;">
        <a href="/" class="flex items-center space-x-2 group">
            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 flex items-center justify-center text-white text-xs font-bold shadow-md group-hover:scale-105 transition-transform">
                P
            </div>
            <span class="font-bold text-gray-800 tracking-tight text-lg group-hover:text-blue-600 transition-colors">
                PensHelpDesk
            </span>
        </a>
        
        <div class="flex items-center space-x-2">
            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold bg-blue-600 text-white rounded-full hover:bg-blue-700 shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                Daftar Sekarang
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
        <!-- Particles Background -->
        <div id="particles-js"></div>
        
        <!-- Animated Blobs -->
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400 opacity-20 blob"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-yellow-400 opacity-20 blob" style="animation-delay: -4s;"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Left Content -->
                <div class="text-left space-y-8" id="hero-content">
                    <div class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-100 to-yellow-100 px-6 py-3 rounded-full border border-blue-200">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
                        </span>
                        <span class="text-blue-700 font-semibold text-sm">
                            Platform Helpdesk Kampus #1
                        </span>
                    </div>
                    
                    <h1 class="text-6xl md:text-7xl font-extrabold text-gray-900 leading-tight">
                        Kelola Laporan
                        <span class="gradient-text block mt-2">Lebih Efisien</span>
                    </h1>
                    
                    <p class="text-xl text-gray-600 leading-relaxed max-w-lg">
                        Sistem helpdesk modern yang memudahkan mahasiswa melaporkan masalah fasilitas kampus. 
                        <span class="font-semibold text-blue-600">Cepat, Transparan, dan Terpercaya.</span>
                    </p>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="group px-8 py-4 text-white rounded-2xl hover:shadow-2xl shadow-lg transition-all duration-300 transform hover:-translate-y-1 font-bold flex items-center gap-3" style="background: linear-gradient(135deg, #1e40af 0%, #fbbf24 100%);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Mulai Gratis
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#features" class="px-8 py-4 bg-white text-gray-700 rounded-2xl hover:bg-gray-50 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 font-bold border-2 border-gray-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            Lihat Demo
                        </a>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t-2 border-gray-200">
                        <div class="stat-item text-center">
                            <div class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-yellow-500 bg-clip-text text-transparent">1000+</div>
                            <div class="text-sm text-gray-600 font-medium mt-1">Tiket Selesai</div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-yellow-500 bg-clip-text text-transparent">500+</div>
                            <div class="text-sm text-gray-600 font-medium mt-1">Mahasiswa</div>
                        </div>
                        <div class="stat-item text-center">
                            <div class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 to-yellow-500 bg-clip-text text-transparent">99%</div>
                            <div class="text-sm text-gray-600 font-medium mt-1">Kepuasan</div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Content - Modern Ticket Preview -->
                <div class="relative" id="hero-image">
                    <div class="relative">
                        <!-- Main Dashboard Preview -->
                        <div class="glass-card rounded-3xl p-6 shadow-2xl transform hover:scale-105 transition-transform duration-500">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-gray-100">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Dashboard Tiket</h3>
                                    <p class="text-sm text-gray-500">Status real-time</p>
                                </div>
                                <div class="flex gap-2">
                                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-blue-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                </div>
                            </div>
                            
                            <!-- Ticket Cards -->
                            <div class="space-y-3">
                                <!-- Completed Ticket -->
                                <div class="ticket-card group p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-l-4 border-green-500 hover:shadow-lg transition-all duration-300 cursor-pointer">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-bold text-green-900 text-sm">AC Rusak - Lab 101</span>
                                                <span class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded-full font-semibold">#ABC123</span>
                                            </div>
                                            <p class="text-xs text-green-700 mb-2">Selesai diperbaiki oleh Tim Teknisi</p>
                                            <div class="flex items-center gap-2 text-xs text-green-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Diselesaikan 2 hari lalu</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- In Progress Ticket -->
                                <div class="ticket-card group p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border-l-4 border-blue-500 hover:shadow-lg transition-all duration-300 cursor-pointer">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform relative">
                                            <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-bold text-blue-900 text-sm">Proyektor Mati - Aula</span>
                                                <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded-full font-semibold">#DEF456</span>
                                            </div>
                                            <p class="text-xs text-blue-700 mb-2">Teknisi sedang menangani</p>
                                            <div class="flex items-center gap-2 text-xs text-blue-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Diproses 1 jam lalu</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- New Ticket -->
                                <div class="ticket-card group p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border-l-4 border-yellow-500 hover:shadow-lg transition-all duration-300 cursor-pointer">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-bold text-yellow-900 text-sm">WiFi Lemah - Perpustakaan</span>
                                                <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full font-semibold">#GHI789</span>
                                            </div>
                                            <p class="text-xs text-yellow-700 mb-2">Menunggu penugasan teknisi</p>
                                            <div class="flex items-center gap-2 text-xs text-yellow-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Baru saja dibuat</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Floating Elements -->
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-blue-400 to-yellow-400 rounded-2xl opacity-20 blur-xl floating"></div>
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-br from-yellow-400 to-blue-400 rounded-2xl opacity-20 blur-xl floating" style="animation-delay: -1.5s;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gradient-to-b from-white to-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-20 feature-header">
                <div class="inline-block mb-4">
                    <span class="bg-gradient-to-r from-blue-100 to-yellow-100 text-blue-700 px-6 py-2 rounded-full text-sm font-bold uppercase tracking-wide border border-blue-200">
                        Fitur Unggulan
                    </span>
                </div>
                <h2 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6">
                    Kenapa Memilih <span class="gradient-text">PensHelpDesk</span>?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Platform yang dirancang khusus dengan teknologi terkini untuk memberikan pengalaman terbaik dalam pelaporan masalah kampus
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Feature 1 -->
                <div class="feature-card group relative p-8 rounded-3xl bg-white border-2 border-gray-100 hover:border-blue-300 hover:shadow-2xl transition-all duration-500 cursor-pointer overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400 to-yellow-400 opacity-10 rounded-bl-full"></div>
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors">Cepat & Mudah</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Buat laporan hanya dalam <span class="font-semibold text-blue-600">30 detik</span>. Interface yang intuitif dan mudah dipahami siapa saja.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card group relative p-8 rounded-3xl bg-white border-2 border-gray-100 hover:border-yellow-300 hover:shadow-2xl transition-all duration-500 cursor-pointer overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-400 to-blue-400 opacity-10 rounded-bl-full"></div>
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-yellow-600 transition-colors">Notifikasi Real-time</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Dapatkan <span class="font-semibold text-yellow-600">update instant</span> setiap ada perubahan status. Pantau progress laporan Anda kapan saja.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card group relative p-8 rounded-3xl bg-white border-2 border-gray-100 hover:border-blue-300 hover:shadow-2xl transition-all duration-500 cursor-pointer overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400 to-yellow-400 opacity-10 rounded-bl-full"></div>
                    <div class="relative">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-xl">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-blue-600 transition-colors">Aman & Terpercaya</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Data Anda dilindungi dengan <span class="font-semibold text-blue-600">enkripsi tingkat tinggi</span>. Privasi dan keamanan adalah prioritas kami.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-purple-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16 how-header">
                <span class="text-blue-600 font-semibold text-sm uppercase tracking-wide">Cara Kerja</span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-3">Mudah Dalam 3 Langkah</h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-12 relative">
                <!-- Step 1 -->
                <div class="step-card text-center">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto shadow-lg">
                            <span class="text-3xl font-bold text-white">1</span>
                        </div>
                        <div class="absolute top-10 right-0 hidden md:block">
                            <svg class="w-32 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 200 50">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 25 Q 100 5, 190 25" stroke-dasharray="5,5"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Daftar Akun</h3>
                    <p class="text-gray-600">
                        Buat akun gratis menggunakan email kampus Anda dalam hitungan detik.
                    </p>
                </div>
                
                <!-- Step 2 -->
                <div class="step-card text-center">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto shadow-lg">
                            <span class="text-3xl font-bold text-white">2</span>
                        </div>
                        <div class="absolute top-10 right-0 hidden md:block">
                            <svg class="w-32 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 200 50">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 25 Q 100 5, 190 25" stroke-dasharray="5,5"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Buat Laporan</h3>
                    <p class="text-gray-600">
                        Laporkan masalah dengan detail dan foto untuk mempercepat penanganan.
                    </p>
                </div>
                
                <!-- Step 3 -->
                <div class="step-card text-center">
                    <div class="mb-6">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto shadow-lg">
                            <span class="text-3xl font-bold text-white">3</span>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Lacak Progress</h3>
                    <p class="text-gray-600">
                        Pantau status penyelesaian masalah dan dapatkan notifikasi real-time.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-blue-600 via-blue-700 to-yellow-500 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-64 h-64 bg-yellow-400 opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-400 opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center cta-content">
                <div class="inline-block mb-6">
                    <span class="bg-white/20 text-white px-6 py-3 rounded-full text-sm font-bold backdrop-blur-sm border border-white/30">
                        🚀 Bergabung Sekarang
                    </span>
                </div>
                
                <h2 class="text-5xl md:text-6xl font-extrabold text-white mb-6 leading-tight">
                    Siap Memulai Perjalanan Anda?
                </h2>
                <p class="text-xl text-blue-50 mb-10 leading-relaxed max-w-2xl mx-auto">
                    Bergabunglah dengan <span class="font-bold text-yellow-300">ratusan mahasiswa</span> yang sudah merasakan kemudahan melaporkan masalah kampus.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('register') }}" class="group px-10 py-5 bg-white text-blue-600 rounded-2xl hover:bg-gray-50 shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2 font-bold text-lg flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Daftar Gratis Sekarang
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" class="px-10 py-5 bg-transparent text-white rounded-2xl hover:bg-white/10 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 font-bold text-lg border-2 border-white/40 backdrop-blur-sm">
                        Sudah Punya Akun? Masuk
                    </a>
                </div>
                
                <!-- Trust Badges -->
                <div class="mt-16 flex flex-wrap justify-center items-center gap-8 text-white/80">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="font-semibold">99% Kepuasan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">Data Aman</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-semibold">Support 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 flex items-center justify-center text-white text-xs font-bold">
                            P
                        </div>
                        <span class="font-bold text-white text-lg">PensHelpDesk</span>
                    </div>
                    <p class="text-sm text-gray-400">
                        Platform helpdesk modern untuk kampus yang lebih baik.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold text-white mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-blue-400 transition">Fitur</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Cara Kerja</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Harga</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-white mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-400 transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Kontak</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Bantuan</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-white mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-blue-400 transition">Privacy</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition">Terms</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-500">
                <p>&copy; 2025 PensHelpDesk. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- GSAP Animations Script -->
    <script>
        // Register ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);
        
        // Navbar animation on scroll
        gsap.to("#navbar", {
            scrollTrigger: {
                trigger: "body",
                start: "100px top",
                toggleActions: "play none none reverse"
            },
            backgroundColor: "rgba(255, 255, 255, 0.95)",
            boxShadow: "0 10px 30px rgba(0, 0, 0, 0.1)",
            duration: 0.3
        });
        
        // Hero content animation
        gsap.from("#hero-content > *", {
            opacity: 0,
            y: 50,
            duration: 1,
            stagger: 0.2,
            ease: "power3.out"
        });
        
        // Hero image animation
        gsap.from("#hero-image", {
            opacity: 0,
            x: 100,
            duration: 1.2,
            ease: "power3.out"
        });
        
        // Stats counter animation
        document.querySelectorAll('.stat-item').forEach((stat, index) => {
            gsap.from(stat, {
                scrollTrigger: {
                    trigger: stat,
                    start: "top 80%"
                },
                opacity: 0,
                scale: 0.5,
                duration: 0.8,
                delay: index * 0.1,
                ease: "back.out(1.7)"
            });
        });
        
        // Features header animation
        gsap.from(".feature-header", {
            scrollTrigger: {
                trigger: ".feature-header",
                start: "top 85%",
                toggleActions: "play none none none"
            },
            opacity: 0,
            y: 50,
            duration: 1,
            ease: "power3.out"
        });
        
        // Feature cards animation - Improved with better trigger
        document.querySelectorAll('.feature-card').forEach((card, index) => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%",
                    toggleActions: "play none none none"
                },
                opacity: 0,
                y: 80,
                duration: 1,
                delay: index * 0.2,
                ease: "power3.out"
            });
        });
        
        // How it works header
        gsap.from(".how-header", {
            scrollTrigger: {
                trigger: ".how-header",
                start: "top 85%",
                toggleActions: "play none none none"
            },
            opacity: 0,
            y: 50,
            duration: 1,
            ease: "power3.out"
        });
        
        // Steps animation - Individual triggers
        document.querySelectorAll('.step-card').forEach((step, index) => {
            gsap.from(step, {
                scrollTrigger: {
                    trigger: step,
                    start: "top 85%",
                    toggleActions: "play none none none"
                },
                opacity: 0,
                scale: 0.8,
                duration: 1,
                delay: index * 0.2,
                ease: "back.out(1.7)"
            });
        });
        
        // CTA animation - Individual elements
        document.querySelectorAll('.cta-content > *').forEach((elem, index) => {
            gsap.from(elem, {
                scrollTrigger: {
                    trigger: ".cta-content",
                    start: "top 85%",
                    toggleActions: "play none none none"
                },
                opacity: 0,
                y: 50,
                duration: 1,
                delay: index * 0.15,
                ease: "power3.out"
            });
        });
        
        // Particles.js configuration
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: '#2563eb' },
                shape: { type: 'circle' },
                opacity: { value: 0.5, random: false },
                size: { value: 3, random: true },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#2563eb',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: true, mode: 'repulse' },
                    onclick: { enable: true, mode: 'push' },
                    resize: true
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>
