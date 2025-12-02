<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - PENS Help Desk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .header-bg {
            background: linear-gradient(to bottom, #1e3a8a, #1e40af);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Back to Login -->
        <div class="mb-6">
            <a href="{{ route('login') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Login
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="header-bg p-6 text-white text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-3xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold">Lupa Password?</h2>
                <p class="text-blue-50 text-sm mt-2">Kami akan mengirim link reset password ke email Anda</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                            <div class="flex-1">
                                <p class="text-green-800 text-sm">{{ session('success') }}</p>
                                <p class="text-green-600 text-xs mt-1">Silakan cek email Anda dan ikuti instruksi untuk mereset password.</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                            <div class="flex-1">
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-800 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 @enderror" 
                            placeholder="nama@pens.ac.id"
                            required
                            autofocus
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Link Reset Password
                    </button>
                </form>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Catatan:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs text-blue-700">
                                <li>Link reset password akan dikirim ke email yang terdaftar</li>
                                <li>Link berlaku selama 60 menit</li>
                                <li>Jika tidak menerima email, cek folder spam</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-sm text-gray-600">
            Ingat password Anda? 
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                Login di sini
            </a>
        </div>
    </div>
</body>
</html>
