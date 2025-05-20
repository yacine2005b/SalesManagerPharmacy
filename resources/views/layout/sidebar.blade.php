<nav class="max-h-screen flex flex-col w-64  bg-white border-r border-gray-200 sticky top-0">
    <!-- Logo/Title -->
    <div class="px-6 py-5 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">PharmaEasy</h1>
    </div>
    
    <!-- Navigation Links -->
    <ul class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        <li>
            <a href="/" class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->is('/') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <!-- Dashboard Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
        </li>
        @auth
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'pharmacist')
            <li>
                <a href="{{ route('inventory.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('inventory.index') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <!-- Inventory Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Inventory
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'cashier')
            <li>
                <a href="{{ route('pos.normal') }}" class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('pos.normal') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <!-- POS Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Point of Sale
                </a>
            </li>
            <li>
                <a href="{{route('sales.history')}}" class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('sales.history') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <!-- Sales History Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Sales History
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'pharmacist')
            <li>
                <a href="{{route('prescription.index')}}" class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('prescription.index') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <!-- Prescription Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Prescriptions
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'admin')
            <li>
                <a href="{{route('admin.users.index')}}" class="flex items-center px-4 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-blue-50 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <!-- Users Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Users
                </a>
            </li>
            @endif

          
        @endauth
    </ul>
@auth
<div class="px-4 py-4 border-t border-gray-200">
    <div class="flex items-center">
        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
            <span class="text-indigo-600 font-medium text-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
            <a href="#" class="text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors duration-200">Sign out</a>
        </div>
    </div>
</div>
@endauth
</nav>