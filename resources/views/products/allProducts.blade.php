<div class="w-full px-6 py-4">
    <!-- Search Bar -->
    <div class="relative flex items-center mb-6">
        <form action="{{ route('products.search') }}" method="GET" class="flex w-full">
            <input
                type="text"
                id="product_search"
                name="query"
                value="{{ $search ?? '' }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-l-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                placeholder="Search products..."
                autocomplete="off"
            />
            <button 
                type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition-colors duration-200"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
            @if(!empty($search))
                <a href="{{ route('inventory.clearSearch') }}" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Clear</a>
            @endif
        </form>
    </div>

    <div id="allProducts" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @if($products->isEmpty())
            <div class="col-span-full text-center text-gray-500 py-8">
                No products found.
            </div>
        @endif

        @foreach ($products as $product)
        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-5 flex flex-col h-full">
            <!-- Product Info -->
            <div class="flex-grow">
                <h3 class="text-lg font-semibold text-gray-800 text-center mb-2">{{ $product->name }}</h3>
                <p class="text-gray-500 text-sm text-center line-clamp-2">{{ $product->description }}</p>
            </div>
            
            <!-- Actions Dropdown -->
            <div class="mt-4 relative flex justify-center">
                <button onclick="toggleDropdown(event, 'dropdown-{{ $product->id }}')" 
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                    Actions
                    <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="dropdown-{{ $product->id }}" 
                     class="hidden absolute z-50 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-10 focus:outline-none border border-gray-100">
                    <!-- Caret/Arrow -->
                    <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-white border-l border-t border-gray-100 rotate-45 z-10"></div>
                    <div class="py-2 px-2">
                        <a href="{{ route('product.show', $product->id) }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition">
                            <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            View Details
                        </a>
                        <a href="{{ route('lot.index', $product->id) }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition">
                            <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Lot
                        </a>
                        <div class="border-t border-gray-200 my-2"></div>
                        <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="mt-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="flex items-center px-4 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 hover:text-red-800 w-full transition">
                                <svg class="mr-3 h-4 w-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    // Dropdown logic
    function toggleDropdown(event, dropdownId) {
        event.stopPropagation();
        const dropdown = document.getElementById(dropdownId);
        document.querySelectorAll('[id^="dropdown-"]').forEach(dd => {
            if (dd.id !== dropdownId) {
                dd.classList.add('hidden');
            }
        });
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('[id^="dropdown-"]') && !event.target.closest('[onclick^="toggleDropdown"]')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
</script>