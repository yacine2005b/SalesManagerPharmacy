@extends('layout.layout')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Product Information Section -->
        <div class="px-6 py-4 border-b border-gray-200">
      @include('products.productData')
        <!-- Lots Management Section -->
        @include('lots.lotData')

        <!-- Summary Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Total Quantity:</span> {{ $product->total_quantity }} units
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Active Lots:</span> {{ $product->lots->where('expiration_date', '>=', now())->count() }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('inventory.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Back to Inventory
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection