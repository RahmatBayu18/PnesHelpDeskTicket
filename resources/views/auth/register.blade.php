<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Gaya tambahan untuk latar belakang dan gambar ilustrasi agar mirip */
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
                <div class="w-full h-80 relative rounded-xl overflow-hidden shadow-xl">

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

        <div class="w-full md:w-7/12 p-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Registration</h2>
            <div class="w-12 h-1 bg-blue-500 mb-8"></div>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 @error('username') border-red-500 @enderror"
                            placeholder="Please enter your username">
                    </div>

                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700">NIM</label>
                        <input type="text" name="nim" id="nim" value="{{ old('nim') }}" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500 
                            @error('nim') border-red-500 @enderror"
                            placeholder="Please enter your NIM">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
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

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Please enter your password again">
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender (Optional)</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="male" class="form-radio text-blue-600">
                                <span class="ml-2 text-gray-700">Male</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="female" class="form-radio text-blue-600" checked>
                                <span class="ml-2 text-gray-700">Female</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="other" class="form-radio text-blue-600">
                                <span class="ml-2 text-gray-700">Other</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="prefer_not_to_say" class="form-radio text-blue-600">
                                <span class="ml-2 text-gray-700">Prefer not to say</span>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="mt-8 w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    Next Step 
                    <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 underline">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>