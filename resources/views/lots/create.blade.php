@extends('layout.layout')

@section('content')
    <div class="grid grid-cols-2 mx-auto p-6 gap-x-4 h-full">
        <div class="border p-6 rounded shadow h-fit">
            <h1 class="text-2xl font-bold mb-4">Add New Lot for {{ $product->name }}</h1>

            <!-- Product Details -->
            <div class="mb-6 border p-4 rounded shadow">
                <h2 class="text-xl font-bold">Product Details</h2>
                <p><strong>Name:</strong> {{ $product->name }}</p>
                <p><strong>Description:</strong> {{ $product->description }}</p>
                <p><strong>Total Quantity:</strong> {{ $product->total_quantity }}</p>
            </div>
    
            <!-- Existing Lots -->
            <div class="mb-6 border p-4 rounded shadow">
                <h2 class="text-xl font-bold">Existing Lots</h2>
                @if ($product->lots->isEmpty())
                    <p class="text-gray-500">No lots available for this product.</p>
                @else
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2">Batch Number</th>
                                <th class="border border-gray-300 px-4 py-2">Quantity</th>
                                <th class="border border-gray-300 px-4 py-2">Price</th>
                                <th class="border border-gray-300 px-4 py-2">Expiration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->lots as $lot)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2">{{ $lot->batch_number }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $lot->quantity }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $lot->price }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $lot->expiration_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
    
        </div>
       
        <!-- Add Lot Form -->
        <div class="flex flex-col py-5 px-3 border rounded shadow h-fit">
            <h2 class="text-xl font-bold mb-4">Add New Lot</h2>
            <form action="{{ route('lot.store', $product->id) }}" method="POST" class="space-y-6 w-full">
                @csrf

                <!-- Quantity -->
              <label for="quantity" class="block font-medium mb-2">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="border border-gray-300 rounded p-2 w-full" placeholder="Enter quantity" required>
                @error('quantity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
    
@enderror

                <!-- Price -->
                
                    <label for="price" class="block font-medium mb-2">Price</label>
                    <input type="number" step="0.01" name="price" id="price" class="border border-gray-300 rounded p-2 w-full" placeholder="Enter price" required>
                @error('price')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
    
@enderror

                <!-- Expiration Date -->
                <label for="expiration_date" class="block font-medium mb-2">Expiration Date</label>
                <input type="date" name="expiration_date" id="expiration_date"
                       class="border border-gray-300 rounded p-2 w-48"
                       required min="{{ \Carbon\Carbon::today()->toDateString() }}">
@error('expiration_date')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
    
@enderror
                <!-- Hidden Product ID -->
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Add Lot
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection