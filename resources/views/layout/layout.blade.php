<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>

    <body class="flex min-h-screen bg-gray-50">
     
            @include('layout.sidebar')
      
       
        
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            @auth
            <!-- Header for Authenticated Users -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <!-- mobile display-->
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-medium text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center space-x-1 text-gray-500 hover:text-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            @endauth

            @guest
            <!-- Header for Guests -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <!-- Logo or other content for guests -->
                    </div>
                    
                    <div>
                        <a href="{{ route('login') }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                            Login
                        </a>
                    </div>
                </div>
            </header>
            @endguest

            <!-- Main Content -->
            <div class="">
                @include('shared.message')
                @yield('content')
            </div>
        </main>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>