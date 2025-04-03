<form action="{{ route('product.store') }}" method="POST" class="space-y-4 w-1/2 border p-6 rounded shadow">
    @csrf

    <div>
        <label for="name" class="block font-medium">Name</label>
        <input type="text" name="name" id="name" class="border border-gray-300 rounded p-2 w-full" placeholder="Product Name" required>
    </div>

    <div>
        <label for="description" class="block font-medium">Description</label>
        <textarea name="description" id="description" class="border border-gray-300 rounded p-2 w-full" placeholder="Product Description" required></textarea>
    </div>

    <div>
        <label for="low_stock_threshold" class="block font-medium">Low Stock Threshold</label>
        <input type="number" name="low_stock_threshold" id="low_stock_threshold" class="border border-gray-300 rounded p-2 w-full" placeholder="Low Stock Threshold" required>
    </div>

    <div>
        <label for="dosage" class="block font-medium">Dosage</label>
        <input type="number" name="dosage" id="dosage" class="border border-gray-300 rounded p-2 w-full" placeholder="Dosage" required>
    </div>

    <div>
        <label for="dosage_unit" class="block font-medium">Dosage Unit</label>
        <select name="dosage_unit" id="dosage_unit" class="border border-gray-300 rounded p-2 w-full" required>
            <option value="" disabled selected>Select a unit</option>
            <option value="mg">mg</option>
            <option value="ml">ml</option>
            <option value="g">g</option>
            <option value="kg">kg</option>
            <option value="L">L</option>
            <option value="IU">IU</option>
            <option value="mcg">mcg</option>
            <option value="mg/mL">mg/mL</option>
            <option value="g/mL">g/mL</option>
        </select>
    </div>

    <div>
        <label for="remboursable" class="block font-medium">Remboursable</label>
        <input type="checkbox" name="remboursable" id="remboursable" value="1">
    </div>

    <div>
      
        <input type="hidden" value="0" name="total_quantity" id="total_quantity" class="border border-gray-300 rounded p-2 w-full" placeholder="Total Quantity" required>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Product</button>
</form>