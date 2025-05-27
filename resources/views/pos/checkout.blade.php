@include('cart.header', ['isInsuranceSale' => $isInsuranceSale ?? false])

<!-- Cart Items Container -->
<div class="w-full bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Cart Items -->
    <div class="divide-y divide-gray-200">
        @if (empty($cart))
            <!-- Empty Cart -->
            <div class="p-8 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
            </div>
        @else
            <!-- Cart Items List -->
            @foreach ($cart as $index => $item)
            <div class="p-4 flex items-start gap-4 w-full">
                <!-- Product Info -->
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-medium text-gray-800 truncate">{{ $item['product_name'] ?? 'Product' }}</h3>
                    <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600">
                        <span>{{ number_format($item['price'], 2) }} DA × {{ $item['quantity'] }}</span>
                        @if ($isInsuranceSale ?? false)
                            @if($item['discount'] ?? 0 > 0)
                                <span class="text-green-600">-{{ number_format($item['discount'], 2) }} DA</span>
                            @endif
                            <span class="{{ $item['is_reimbursable'] ?? false ? 'text-green-600' : 'text-gray-500' }}">
                                {{ $item['is_reimbursable'] ?? false ? 'Remboursable' : 'Non-remboursable' }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Quantity Update -->
                    <form action="{{ route('cart.update') }}" method="POST" class="mt-3 flex items-center gap-2 w-full">
                        @csrf
                        <input type="hidden" name="index" value="{{ $index }}">
                        <input type="hidden" name="is_insurance" value="{{ $isInsuranceSale ?? false }}">
                        
                        <div class="flex border border-gray-300 rounded-md overflow-hidden w-full max-w-xs">
                            <input type="number" 
                                   name="quantity" 
                                   value="{{ $item['quantity'] }}" 
                                   min="1" 
                                   class="w-16 px-3 py-2 text-center focus:ring-1 focus:ring-blue-500 flex-grow">
                            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-sm font-medium">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Price and Remove -->
                <div class="flex flex-col items-end justify-between">
                    <p class="text-lg font-semibold text-gray-900 whitespace-nowrap">
                        {{ number_format(($item['price'] * $item['quantity']) - ($isInsuranceSale ? ($item['discount'] ?? 0) : 0), 2) }} DA
                    </p>
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_insurance" value="{{ $isInsuranceSale ?? false }}">
                        <input type="hidden" name="index" value="{{ $index }}">
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                            Remove
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    @if (!empty($cart))
        <!-- Cart Footer -->
        <div class="p-6 bg-gray-50 border-t border-gray-200 w-full">
            <!-- Subtotal -->
            <div class="flex justify-between items-center mb-6">
                <span class="text-lg font-medium text-gray-700">Subtotal</span>
                <span class="text-xl font-semibold text-gray-900">
                    {{ number_format(collect($cart)->sum(fn($item) => ($item['price'] * $item['quantity']) - ($isInsuranceSale ? ($item['discount'] ?? 0) : 0)), 2) }} DA
                </span>
            </div>
            
            <!-- Checkout Button -->
            <form action="{{ route('checkout') }}" method="POST" class="w-full">
                @csrf
                  @if(isset($shifaCard))
        <input type="hidden" name="coverage_type" value="{{ $shifaCard->coverage_type }}">
        <input type="hidden" name="shifa_card_number" value="{{ $shifaCard->card_number }}">
        <input type="hidden" name="issue_date" value="{{ $shifaCard->issue_date }}">
        <input type="hidden" name="expiry_date" value="{{ $shifaCard->expiry_date }}">
    @else
        <input type="hidden" name="coverage_type" value="{{ session('coverage_type') }}">
        <input type="hidden" name="shifa_card_number" value="{{ session('shifa_card_number') }}">
        <input type="hidden" name="issue_date" value="{{ session('issue_date') }}">
        <input type="hidden" name="expiry_date" value="{{ session('expiry_date') }}">
    @endif
    <input type="hidden" name="is_insurance" value="1">
                <button type="submit" class="w-full py-3 px-6 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md shadow-sm flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Checkout
                </button>
            </form>
        </div>
    @endif
</div>