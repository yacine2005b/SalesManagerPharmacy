@extends('layout.layout')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Lot #{{ $lot->batch_number }}</h1>
            <p class="text-gray-600">Editing inventory for: <span class="font-medium">{{ $lot->product->name }}</span></p>
        </div>
        <span class="px-3 py-1 text-sm rounded-full 
              @if($lot->expiration_date < now()) bg-red-100 text-red-800
              @elseif($lot->quantity <= $lot->product->low_stock_threshold) bg-yellow-100 text-yellow-800
              @else bg-green-100 text-green-800 @endif">
            @if($lot->expiration_date < now()) Expired
            @elseif($lot->quantity <= $lot->product->low_stock_threshold) Low Stock
            @else In Stock @endif
        </span>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('lot.update', $lot->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="batch_number" id="batch_number" value="{{ $lot->batch_number }}">

       
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
           
            <div class="space-y-4">
              
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" name="quantity" id="quantity" value="{{ $lot->quantity }}"
                               class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                               min="1" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">units</span>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Low stock threshold: {{ $lot->product->low_stock_threshold }} units</p>
                </div>
                @error('quantity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>   
@enderror
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Expiration Date Field -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <label for="expiration_date" class="block text-sm font-medium text-gray-700 mb-2">Expiration Date</label>
                    <input type="date" name="expiration_date" id="expiration_date" value="{{ $lot->expiration_date }}"
                           class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                           required>
                           @error('expiration_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>   
                           @enderror
                    @if($lot->expiration_date < now())
                    <p class="mt-1 text-xs text-red-600">This lot has expired</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Full Width Price Field -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price</label>
            <div class="relative rounded-md shadow-sm">
            
                <input type="number" step="0.01" name="price" id="price" value="{{ $lot->price }}"
                       class="block w-full pl-7 pr-12 py-2 bg-white border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                       min="0.01" required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 text-sm">DA</span>
                </div>
            </div>
            @error('price')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Last updated: {{ $lot->updated_at->format('M d, Y') }}
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('product.show', $lot->product->id) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection