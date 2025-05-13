@extends('layout.layout')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Prescription Management</h1>
    <div class="flex">
        @include('perscription.create')
   
        <!-- Right Side - List -->
        @include('perscription.allPrescription')
    </div>
  
     
</div>

<script>
    // Product search functionality
    document.getElementById('product_search').addEventListener('input', function() {
        const query = this.value.trim();
        const resultsDiv = document.getElementById('product_results');
        
        if (query.length > 1) {
            fetch(`/search-products?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    
                    if (data.length > 0) {
                        data.forEach(product => {
                            const li = document.createElement('li');
                            li.className = 'px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm';
                            li.innerHTML = product.name;
                            li.addEventListener('click', () => selectProduct(product));
                            resultsDiv.appendChild(li);
                        });
                        resultsDiv.classList.remove('hidden');
                    } else {
                        resultsDiv.innerHTML = '<li class="px-3 py-2 text-gray-500 text-sm">No products found</li>';
                        resultsDiv.classList.remove('hidden');
                    }
                });
        } else {
            resultsDiv.classList.add('hidden');
        }
    });

    // Add new medication row
    function addMedication() {
        const medicationsDiv = document.getElementById('medications');
        const medicationIndex = medicationsDiv.children.length;
        
        const newMedication = document.createElement('div');
        newMedication.className = 'flex items-center gap-2';
        newMedication.innerHTML = `
            <select name="medications[${medicationIndex}][product_id]" required
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <input type="number" name="medications[${medicationIndex}][quantity]" placeholder="Qty" min="1" value="1" required
                   class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <button type="button" onclick="removeMedication(this)" class="text-red-500 hover:text-red-700 p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        `;
        medicationsDiv.appendChild(newMedication);
    }

    // Add selected product to medications list
    function selectProduct(product) {
        const medicationsDiv = document.getElementById('medications');
        const existingMedication = Array.from(medicationsDiv.children).find(medication => {
            const productIdInput = medication.querySelector('input[name*="[product_id]"]');
            return productIdInput && productIdInput.value == product.id;
        });

        if (existingMedication) {
            // If the product already exists, increase its quantity
            const quantityInput = existingMedication.querySelector('input[name*="[quantity]"]');
            quantityInput.value = parseInt(quantityInput.value) + 1;
        } else {
            // If the product does not exist, create a new div
            const medicationIndex = medicationsDiv.children.length;

            const newMedication = document.createElement('div');
            newMedication.className = 'flex items-center gap-2';
            newMedication.innerHTML = `
                <input type="hidden" name="medications[${medicationIndex}][product_id]" value="${product.id}">
                <span class="flex-1 px-3 py-2 text-sm bg-white border border-gray-300 rounded-md">${product.name}</span>
                <input type="number" name="medications[${medicationIndex}][quantity]" placeholder="Qty" min="1" value="1" required
                       class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <button type="button" onclick="removeMedication(this)" class="text-red-500 hover:text-red-700 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            medicationsDiv.appendChild(newMedication);
        }

        // Clear search
        document.getElementById('product_search').value = '';
        document.getElementById('product_results').classList.add('hidden');
    }

    // Remove medication from list
    function removeMedication(button) {
        button.closest('div').remove();
    }
</script>
@endsection