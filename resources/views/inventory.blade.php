@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Inventory Search</h1>

    <form action="{{ route('inventory.search') }}" method="GET" class="mb-4 flex gap-2">
        <input
            type="text"
            name="query"
            id="barcodeInput"
            placeholder="Scan barcode or type product name..."
            class="border rounded px-3 py-2 w-full"
            autofocus
        >
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
        @if(session('product_search'))
            <a href="{{ route('inventory.clearSearch') }}" class="ml-2 text-red-500">Clear</a>
        @endif
    </form>

    @if($products->isNotEmpty())
        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-2">Search Results:</h2>
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Name</th>
                        <th class="px-4 py-2 border">Barcode</th>
                        <th class="px-4 py-2 border">Quantity</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-2 border">{{ $product->name }}</td>
                        <td class="px-4 py-2 border">
@foreach ($product->lots as $lot)

 <img src="{{ asset('storage/' . $lot->barcode) }}" alt="Barcode for {{ $lot->batch_number }}" class="h-12 w-32 object-contain">

@endforeach

                        </td>

    
                        
                        <td class="px-4 py-2 border">{{ $product->total_quantity }}</td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('product.show', $product->id) }}" class="text-blue-600">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif(session('product_search'))
        <div class="mt-6 text-red-500">No products found for "{{ session('product_search') }}".</div>
    @endif
</div>

<script>
    // Optional: Auto-focus and select input for barcode scanners
    document.getElementById('barcodeInput').focus();
</script>
@endsection