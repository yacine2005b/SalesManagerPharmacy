<div class="px-6 py-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Inventory Lots</h2>
        <a href="{{ route('lot.index', $product->id) }}" 
           class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-md text-sm hover:bg-green-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Lot
        </a>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barcode</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Batch #</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Quantity</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Expiration</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Price</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($product->lots as $lot)
                <tr class="{{ $lot->expiration_date < now() ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="{{ asset('storage/' . $lot->barcode) }}" alt="Barcode for {{ $lot->batch_number }}" class="h-12 w-32 object-contain">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $lot->batch_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lot->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($lot->expiration_date)->format('M d, Y') }}
                        @if($lot->expiration_date < now())
                        <span class="ml-2 px-2 py-1 text-xs text-red-800 bg-red-100 rounded-full">Expired</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($lot->price, 2) }}DA</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($lot->quantity <= $product->low_stock_threshold)
                            <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">Low Stock</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">In Stock</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('lot.edit', $lot->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('lot.destroy', $lot->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            <!-- Print Barcode Button -->
                            <a href="{{ route('lot.printBarcode', $lot->id) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-900">Print Barcode</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No inventory lots found for this product</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>