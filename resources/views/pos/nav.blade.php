<div class="container mx-auto p-6">
    <!-- Sale Type Navigation -->
   <div class="bg-white shadow-sm mb-6 rounded-lg overflow-hidden">
    <div class="flex border-b border-gray-200">
        <form action="{{ route('pos.switchSaleType') }}" method="POST" style="display:inline;">
            @csrf
            <input type="hidden" name="sale_type" value="normal">
            <button type="submit" class="px-6 py-3 text-sm font-medium {{ request()->routeIs('pos.normal') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-colors duration-200">
                Normal Sale
            </button>
        </form>
        <form action="{{ route('pos.switchSaleType') }}" method="POST" style="display:inline;">
            @csrf
            <input type="hidden" name="sale_type" value="insurance">
            <button type="submit" class="px-6 py-3 text-sm font-medium {{ request()->routeIs('pos.insurance') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-colors duration-200">
                Insurance Sale
            </button>
        </form>
        <form action="{{ route('pos.switchSaleType') }}" method="POST" style="display:inline;">
            @csrf
            <input type="hidden" name="sale_type" value="prescription">
            <button type="submit" class="px-6 py-3 text-sm font-medium {{ request()->routeIs('pos.prescription') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }} transition-colors duration-200">
                Prescription Sale
            </button>
        </form>
    </div>
</div>