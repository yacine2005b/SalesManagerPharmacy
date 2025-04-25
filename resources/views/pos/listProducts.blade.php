<div class="col-span-2 p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Available Products</h2>
        <span class="text-xs text-gray-500">{{ $products->count() }} items</span>
    </div>

    <div id="product-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach ($products as $product)
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-sm transition-shadow">
            <div class="p-3">
                <!-- Product Header -->
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 leading-tight">{{ $product->name }}</h3>
                    @if($product->remboursable)
                        <span class="bg-green-100 text-green-800 text-xxs px-1.5 py-0.5 rounded-full">RMB</span>
                    @endif
                </div>
                
                <!-- Product Description -->
                <p class="text-gray-600 text-xs mb-3 line-clamp-2">{{ $product->description }}</p>

                @php
                    $soonestLot = $product->lots->sortBy('expiration_date')->first();
                @endphp

                @if($soonestLot)
                <!-- Price and Expiry -->
                <div class="mb-3 space-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="font-bold text-sm text-gray-900">{{ number_format($soonestLot->price, 2) }} DA</span>
                        </div>
                        <div class="flex items-center text-xxs">
                            <span class="{{ \Carbon\Carbon::parse($soonestLot->expiration_date)->isPast() ? 'text-red-500' : 'text-gray-500' }}">
                                {{ \Carbon\Carbon::parse($soonestLot->expiration_date)->format('d/m/y') }}
                            </span>
                        </div>
                    </div>
                    
                    @if($soonestLot->stock <= 5)
                        <div class="text-xxs text-orange-600">
                            {{ $soonestLot->stock }} remaining
                        </div>
                    @endif
                </div>

                <!-- Add to Cart Form -->
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_name" value="{{ $product->name }}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="lot_id" value="{{ $soonestLot->id }}">
                    <input type="hidden" name="price" value="{{ $soonestLot->price }}">
                    <input type="hidden" name="is_insurance" value="{{ $isInsuranceSale ?? false }}">

                    <div class="flex items-center gap-1">
                        <input type="number" 
                               name="quantity" 
                               value="1" 
                               min="1" 
                               max="{{ $soonestLot->stock }}"
                               class="w-12 px-1 py-1 border border-gray-300 rounded text-center text-xs">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add
                        </button>
                    </div>
                </form>
                @else
                <!-- Out of Stock -->
                <div class="bg-red-50 border-l-2 border-red-400 p-2 rounded-r">
                    <div class="flex items-center">
                        <span class="text-red-700 text-xxs font-medium">Out of stock</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>