<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - PENS Help Desk</title>
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
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="header-bg p-6 text-white text-center">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - PNES Help Desk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-3xl text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-bold">Reset Password</h2>
                <p class="text-blue-100 text-sm mt-2">Buat password baru untuk akun Anda</p>
            </div>

            <!-- Form -->
            <div class="p-8">
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

                <form action="{{ route('password.update') }}" method="POST" class="space-y-6" x-data="{ 
                    showPassword: false, 
                    showPasswordConfirm: false,
                    password: '',
                    password_confirmation: '',
                    passwordStrength: 0,
                    calculateStrength() {
                        let strength = 0;
                        if (this.password.length >= 8) strength += 25;
                        if (this.password.match(/[a-z]/)) strength += 25;
                        if (this.password.match(/[A-Z]/)) strength += 25;
                        if (this.password.match(/[0-9]/)) strength += 25;
                        this.passwordStrength = strength;
                    }
                }">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Email Display -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                        </label>
                        <div class="px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
                            {{ $email }}
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-key mr-2 text-gray-400"></i>Password Baru
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                id="password" 
                                x-model="password"
                                @input="calculateStrength()"
                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('password') border-red-500 @enderror" 
                                placeholder="Minimal 8 karakter"
                                required
                            >
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div class="mt-2" x-show="password.length > 0">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div 
                                    class="h-full transition-all duration-300"
                                    :class="{
                                        'bg-red-500': passwordStrength <= 25,
                                        'bg-orange-500': passwordStrength > 25 && passwordStrength <= 50,
                                        'bg-yellow-500': passwordStrength > 50 && passwordStrength <= 75,
                                        'bg-green-500': passwordStrength > 75
                                    }"
                                    :style="`width: ${passwordStrength}%`"
                                ></div>
                            </div>
                            <p class="text-xs mt-1" :class="{
                                'text-red-600': passwordStrength <= 25,
                                'text-orange-600': passwordStrength > 25 && passwordStrength <= 50,
                                'text-yellow-600': passwordStrength > 50 && passwordStrength <= 75,
                                'text-green-600': passwordStrength > 75
                            }">
                                <span x-show="passwordStrength <= 25">Password lemah</span>
                                <span x-show="passwordStrength > 25 && passwordStrength <= 50">Password sedang</span>
                                <span x-show="passwordStrength > 50 && passwordStrength <= 75">Password kuat</span>
                                <span x-show="passwordStrength > 75">Password sangat kuat</span>
                            </p>
                        </div>

                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-shield-alt mr-2 text-gray-400"></i>Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPasswordConfirm ? 'text' : 'password'" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                x-model="password_confirmation"
                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                placeholder="Ketik ulang password baru"
                                required
                            >
                            <button 
                                type="button" 
                                @click="showPasswordConfirm = !showPasswordConfirm"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i :class="showPasswordConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        
                        <!-- Password Match Indicator -->
                        <div class="mt-2" x-show="password_confirmation.length > 0">
                            <p class="text-xs" :class="password === password_confirmation && password.length >= 8 ? 'text-green-600' : 'text-red-600'">
                                <i :class="password === password_confirmation && password.length >= 8 ? 'fas fa-check-circle' : 'fas fa-times-circle'" class="mr-1"></i>
                                <span x-text="password === password_confirmation && password.length >= 8 ? 'Password cocok' : 'Password tidak cocok'"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-xs font-medium text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>Password harus memenuhi:
                        </p>
                        <ul class="space-y-1 text-xs text-gray-600">
                            <li :class="password.length >= 8 ? 'text-green-600' : ''">
                                <i :class="password.length >= 8 ? 'fas fa-check-circle text-green-500' : 'far fa-circle'" class="mr-2 w-4"></i>
                                Minimal 8 karakter
                            </li>
                            <li :class="password.match(/[a-z]/) ? 'text-green-600' : ''">
                                <i :class="password.match(/[a-z]/) ? 'fas fa-check-circle text-green-500' : 'far fa-circle'" class="mr-2 w-4"></i>
                                Mengandung huruf kecil
                            </li>
                            <li :class="password.match(/[A-Z]/) ? 'text-green-600' : ''">
                                <i :class="password.match(/[A-Z]/) ? 'fas fa-check-circle text-green-500' : 'far fa-circle'" class="mr-2 w-4"></i>
                                Mengandung huruf besar
                            </li>
                            <li :class="password.match(/[0-9]/) ? 'text-green-600' : ''">
                                <i :class="password.match(/[0-9]/) ? 'fas fa-check-circle text-green-500' : 'far fa-circle'" class="mr-2 w-4"></i>
                                Mengandung angka
                            </li>
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="password.length < 8 || password !== password_confirmation"
                    >
                        <i class="fas fa-check mr-2"></i>Reset Password
                    </button>
                </form>
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

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
