<!-- filepath: c:\Users\HP\Desktop\laformatik\gestionDeVentePharmacy\resources\views\products\edit.blade.php -->
@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-4">Edit Product</h1>

    <form action="{{ route('product.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold">Name</label>
            <input type="text" name="name" id="name" value="{{ $product->name }}" 
                   class="w-full border border-gray-300 rounded px-4 py-2">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold">Description</label>
            <textarea name="description" id="description" rows="4" 
                      class="w-full border border-gray-300 rounded px-4 py-2">{{ $product->description }}</textarea>
        </div>

        <div class="mb-4">
            <label for="low_stock_threshold" class="block text-gray-700 font-bold">Low Stock Threshold</label>
            <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ $product->low_stock_threshold }}" 
                   class="w-full border border-gray-300 rounded px-4 py-2">
        </div>

        <div class="mb-4">
            <label for="dosage" class="block text-gray-700 font-bold">Dosage</label>
            <input type="text" name="dosage" id="dosage" value="{{ $product->dosage }}" 
                   class="w-full border border-gray-300 rounded px-4 py-2">
        </div>

        <div class="mb-4">
            <label for="dosage_unit" class="block text-gray-700 font-bold">Dosage Unit</label>
            <input type="text" name="dosage_unit" id="dosage_unit" value="{{ $product->dosage_unit }}" 
                   class="w-full border border-gray-300 rounded px-4 py-2">
        </div>

        <div class="mb-4">
            <label for="remboursable" class="block text-gray-700 font-bold">Remboursable</label>
            <select name="remboursable" id="remboursable" class="w-full border border-gray-300 rounded px-4 py-2">
                <option value="1" {{ $product->remboursable ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$product->remboursable ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Save Changes
        </button>
    </form>
</div>
@endsection