<form action="{{route('products.search')}}" class="w-full p-4 relative">
    @csrf
    <div class="relative">
        <input type="search" name="search" id="search" 
               class="border border-gray-300 rounded-lg p-2 pl-4 pr-12 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
               placeholder="Search products...">
        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-md transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </div>
</form>