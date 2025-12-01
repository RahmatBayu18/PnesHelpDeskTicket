<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Gaya tambahan yang sama dengan halaman register */
        .illustration-bg {
            background-image: linear-gradient(to bottom, #1e3a8a, #0c4a6e); /* Gradasi gelap */
            position: relative;
            overflow: hidden;
        }
        .illustration-bg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.2); /* Overlay gelap */
        }
        .illustration-content {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="flex w-full max-w-5xl bg-white shadow-2xl rounded-xl overflow-hidden">
        <div class="illustration-bg hidden md:flex md:w-5/12 p-10 flex-col justify-between relative overflow-hidden rounded-r-2xl bg-gradient-to-b from-[#002E6E] to-[#004A9F] text-white">
            <!-- Decorative Blur Background -->
            <div class="absolute inset-0">
                <div class="absolute rounded-full w-72 h-72 bg-white/10 blur-3xl -top-10 -left-10"></div>
                <div class="absolute rounded-full w-56 h-56 bg-blue-300/10 blur-2xl bottom-0 right-0"></div>
            </div>

            <!-- Top Section Logo -->
            <div class="relative flex items-center gap-3">
                <img 
                    src="{{ asset('aset/logo-PensHelpDes.svg') }}"
                    alt="Logo"
                    class="h-12 w-auto object-contain drop-shadow-lg"
                >
                <div class="text-[26px] font-bold tracking-wide">
                    <span class="text-white">Pens</span>
                    <span class="text-yellow-400">HelpDesk</span>
                </div>
            </div>

            <!-- Illustration -->
            <div class="illustration-content relative flex flex-col items-center justify-center h-full mt-8">
                <div class="w-full h-72 relative rounded-xl overflow-hidden shadow-xl">

                    <!-- Pattern Background -->
                    <!-- <div class="absolute inset-0 bg-cover bg-center opacity-30"
                        style="background-image: url('https://i.imgur.com/example.png');">
                    </div> -->

                    <!-- Main Image -->
                    <img 
                        src="{{ asset('aset/gedung pens.jpg') }}" 
                        alt="Gedung PENS"
                        class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-lighten"
                    >

                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                </div>

                <div class="mt-4 text-center text-white/90 text-sm tracking-wide">
                    Membangun layanan kampus yang lebih cepat dan responsif.
                </div>
            </div>
        </div>

        <div class="w-full md:w-7/12 p-12 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Sign In</h2>
            <div class="w-12 h-1 bg-blue-500 mb-8"></div>

             @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 
                        @error('email') border-red-500 @enderror"
                        placeholder="Please enter your email">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 
                        @error('password') border-red-500 @enderror"
                        placeholder="Please enter your password">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <button type="submit" class="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    Sign In
                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 underline">Register here</a>
            </p>
        </div>
    </div>
</body>
</html>