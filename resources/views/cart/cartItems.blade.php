<div class="divide-y divide-gray-200">
    @if (empty($cart))
        <!-- Empty Cart -->
        <div class="p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
        </div>
    @else
        <!-- Cart Items List -->
        @foreach ($cart as $index => $item)
        <div class="p-4 flex items-start">
            <!-- Product Info -->
            <div class="flex-1 min-w-0">
                <h3 class="text-base font-medium text-gray-800">{{ $item['product_name'] ?? 'Product' }}</h3>
                <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600">
                    <span>{{ number_format($item['price'], 2) }}DA × {{ $item['quantity'] }}</span>
                    @if ($isInsuranceSale ?? false)
                        @if($item['discount'] ?? 0 > 0)
                            <span class="text-green-600">-{{ number_format($item['discount'], 2) }}DA</span>
                        @endif
                        <span>{{ $item['is_reimbursable'] ?? false ? 'Remboursable' : 'Not remboursable' }}</span>
                    @endif
                </div>
                
                <!-- Quantity Update -->
                <form action="{{ route('cart.update') }}" method="POST" class="mt-2 flex items-center">
                    @csrf
                    <input type="hidden" name="index" value="{{ $index }}">
                    <input type="hidden" name="is_insurance" value="{{ $isInsuranceSale ?? false }}">
                    
                    <div class="flex border border-gray-300 rounded-md">
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                               class="w-12 px-2 py-1 text-center border-0 focus:ring-1 focus:ring-blue-500">
                        <button type="submit" class="px-2 py-1 bg-gray-100 border-l text-sm">
                            Update
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Price and Remove -->
            <div class="ml-4 text-right">
                <p class="text-base font-medium text-gray-900">
                    {{ number_format(($item['price'] * $item['quantity']) - ($isInsuranceSale ? ($item['discount'] ?? 0) : 0), 2) }}DA
                </p>
                <form action="{{ route('cart.remove') }}" method="POST" class="mt-1">
                    @csrf
                    <input type="hidden" name="is_insurance" value="{{ $isInsuranceSale ?? false }}">
                    <input type="hidden" name="index" value="{{ $index }}">
                    <button type="submit" class="text-red-500 hover:text-red-700 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Remove
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    @endif
</div>
