@extends('layout.layout')

@section('content')

@include('products.allProducts')

<button id="add-product" class="text-2xl">Add product</button>
<div id="form" class="flex-col justify-center items-center border-2 border-black hidden">
    <div class="flex justify-between w-full p-4">
        <h3 class="text-2xl">Add product</h3>
        <p id="delete-btn" class="cursor-pointer">X</p>
    </div>
    
    <form method="post" class="flex flex-col pt-2" action="{{ route('product.store') }}">
        @csrf
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" placeholder="Name" required>
        </div>
        <div>
            <label for="low_stock_threshold">Low stock threshold</label>
            <input type="text" name="low_stock_threshold" placeholder="Low stock threshold" required>
        </div>
        <div>
            <label for="dosage">Dosage</label>
            <input type="text" name="dosage" placeholder="Dosage" required>
            <label for="dosage_unit">Dosage unit:</label>
            <select name="dosage_unit" required>
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
        <div class="mb-4">
            <label for="description">Description</label>
            <input type="text" name="description" placeholder="Description" required>
        </div>
        <div class="mb-4">
            <label for="remboursable">Remboursable</label>
            <input type="checkbox" name="remboursable" value="1">
        </div>
        <div class="mb-4">
          
            <input type="hidden" name="total_quantity" value="0" >
        </div>
        <button type="submit" class="py-1 border-t-2 border-black">Submit</button>
    </form>
</div>
<script>
    let addProduct = document.getElementById('add-product');
    let deleteBtn = document.getElementById("delete-btn");
    let form = document.getElementById('form');
    
    addProduct.addEventListener('click', () => {
        form.classList.remove('hidden');
        form.classList.add('flex');
        addProduct.classList.add('hidden');
    });
    deleteBtn.addEventListener("click", () => {
        form.classList.add("hidden");
        form.classList.remove('flex');
        addProduct.classList.remove('hidden');
    });
</script>

@endsection