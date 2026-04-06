@if($products->isEmpty())
    <div class="col-span-full text-center text-gray-500 py-8">
        No products found.
    </div>
@endif

@foreach ($products as $product)
<div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-5 flex flex-col h-full">
    <div class="flex-grow">
        <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">{{ $product->name }}</h3>
        <p class="text-gray-500 text-sm text-center line-clamp-2">{{ $product->description }}</p>
    </div>

    <div class="mt-4 relative flex justify-center">
        <button onclick="toggleDropdown(event, 'dropdown-{{ $product->id }}')" 
                class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
            Actions
            <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>

        <div id="dropdown-{{ $product->id }}" 
             class="hidden absolute z-50 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-10 focus:outline-none border border-gray-100">
            <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-white border-l border-t border-gray-100 rotate-45 z-10"></div>
            <div class="py-2 px-2">
                <a href="{{ route('product.show', $product->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition">
                    View Details
                </a>
                <a href="{{ route('lot.index', $product->id) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition">
                    Add Lot
                </a>
                <div class="border-t border-gray-200 my-2"></div>
                <form action="{{ route('product.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center px-4 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 hover:text-red-800 w-full transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
