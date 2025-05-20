<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Authentication</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            background-color: #f8fafc;
            background-image: linear-gradient(to right top, #f3f4f6, #e5e7eb, #d1d5db);
        }
        .auth-container {
            min-height: 100vh;
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="auth-container flex items-center justify-center p-4">
        <div class="w-full max-w-md space-y-8">
            <!-- Header Section -->
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <h1 class="mt-4 text-3xl font-bold text-gray-900">Welcome Back</h1>
                <p class="mt-2 text-sm text-gray-600">Please sign in to continue</p>
            </div>

            <!-- Form Container -->
            <div class="bg-white p-8 rounded-xl shadow-lg ring-1 ring-black/5 transition-all duration-300 hover:ring-blue-500/30">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input id="email" name="email" type="email" required autocomplete="email" autofocus
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg form-input focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400"
                            value="{{ old('email') }}"
                            placeholder="your@email.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg form-input focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400"
                            placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                   

                    <button type="submit" 
                            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 rounded-lg text-sm font-semibold text-white transition-all duration-200 transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Sign In
                    </button>

                  
                    
                </form>
            </div>
        </div>
    </div>
</body>
</html>