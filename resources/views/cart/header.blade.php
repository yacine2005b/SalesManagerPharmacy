<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
    <!-- Cart Header -->
    <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Your Cart</h2>
        
       
            <div class="flex gap-2">
                @if($isInsuranceSale)
                    <a href="{{ route('pos.normal') }}" class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-md flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Normal Sale
                    </a>
                @else
                    <a href="{{ route('pos.insurance') }}" class="text-sm bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1 rounded-md flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Insurance Sale
                    </a>
                @endif
            </div>
       
    </div>