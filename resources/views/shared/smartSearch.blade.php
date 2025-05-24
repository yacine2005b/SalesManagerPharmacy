<div class="mb-6">
    <form action="{{ route('cart.smartAdd') }}" method="POST" class="relative">
        @csrf
        <div class="flex rounded-md shadow-sm">
            <input
                type="text"
                name="barcode_or_search"
                autocomplete="off"
                autocorrect="off"
                spellcheck="false"
                class="block w-full rounded-l-md border-0 py-3 px-4 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:outline-none sm:text-sm"
                placeholder="Scan barcode, search product name, or batch number..."
                required
                autofocus
                x-ref="searchInput"
            >
            <button 
                type="submit" 
                class="inline-flex items-center rounded-r-md bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add
            </button>
        </div>
    </form>
</div>

@if(session('success'))
<div class="fixed top-4 right-4 px-4 py-2 bg-green-500 text-white rounded-md shadow-lg z-50">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="fixed top-4 right-4 px-4 py-2 bg-red-500 text-white rounded-md shadow-lg z-50">
    {{ session('error') }}
</div>
@endif

<script>
// Auto-hide flash messages after 3 seconds
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('[class*="fixed top-4 right-4"]');
    messages.forEach(message => {
        setTimeout(() => {
            message.remove();
        }, 3000);
    });
    
    // Keep input focused
    const input = document.querySelector('[name="barcode_or_search"]');
    input.focus();
    document.addEventListener('click', function() {
        input.focus();
    });
});
</script>