<div class="flex justify-between items-start">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
        <div class="mt-2 space-y-1">
            <p class="text-gray-600"><span class="font-medium">Description:</span> {{ $product->description }}</p>
            <p class="text-gray-600"><span class="font-medium">Dosage:</span> {{ $product->dosage }} {{ $product->dosage_unit }}</p>
            <p class="text-gray-600"><span class="font-medium">Low Stock Threshold:</span> {{ $product->low_stock_threshold }} units</p>
            <p class="text-gray-600"><span class="font-medium">Remboursable:</span> {{ $product->remboursable ? 'Yes' : 'No' }}</p>
            <p class="text-gray-600"><span class="font-medium">Prescription Required:</span> {{ $product->prescription ? 'Yes' : 'No' }}</p>
        </div>
    </div>
    <div>
        <a href="{{ route('product.edit', $product->id) }}" 
           class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-md text-sm hover:bg-blue-200">
            Edit Product
        </a>
    </div>
</div>
</div>
