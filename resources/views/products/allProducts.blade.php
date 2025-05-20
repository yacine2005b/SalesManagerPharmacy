<div class="w-full px-6 py-4">
    @include('shared.search')
    <div id="allProducts" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
       
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
                     class="hidden absolute z-50 mt-10 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1">
                        <a href="{{ route('product.show', $product->id) }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            View Details
                        </a>
                        <a href="{{ route('lot.index', $product->id) }}" 
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Lot
                        </a>
                        <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-100 hover:text-red-800 w-full">
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
    // Improved dropdown toggle function
    function toggleDropdown(event, dropdownId) {
        event.stopPropagation();
        const dropdown = document.getElementById(dropdownId);
        
        // Close all other dropdowns first
        document.querySelectorAll('[id^="dropdown-"]').forEach(dd => {
            if (dd.id !== dropdownId) {
                dd.classList.add('hidden');
            }
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('hidden');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[id^="dropdown-"]') && !event.target.closest('[onclick^="toggleDropdown"]')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });
    document.getElementById('product_search').addEventListener('input', function() {
    const query = this.value.trim();
    fetch(`/products/search?query=${encodeURIComponent(query)}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('allProducts').innerHTML = html;
        });
});
document.getElementById('search').addEventListener('input', function() {
    const query = this.value.trim();
    fetch(`/products/search?query=${encodeURIComponent(query)}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('allProducts').innerHTML = html;
        });
});
</script>