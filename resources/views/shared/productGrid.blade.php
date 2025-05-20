<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
    @forelse ($products as $product)
        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-5 flex flex-col h-full">
            <!-- Product Info -->
            <div class="flex-grow">
                <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">{{ $product->name }}</h3>
                <p class="text-gray-500 text-sm text-center line-clamp-2">{{ $product->description }}</p>
            </div>
            <!-- Actions Dropdown ... (copy from your main card) -->
        </div>
    @empty
        <div class="col-span-4 text-center text-gray-500">No products found.</div>
    @endforelse
</div>