@extends('layout.layout')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Prescription Management</h1>
    <div class="flex gap-4">
        @include('perscription.create')
   
        <!-- Right Side - List -->
        @include('perscription.allPrescription')
    </div>
  
     
</div>

<script>
let medicationIndex = 1; // For dynamic medication fields

// Live product search
document.getElementById('product_search').addEventListener('input', function() {
    const query = this.value.trim();
    const resultsDiv = document.getElementById('product_results');
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        resultsDiv.innerHTML = '';
        return;
    }
    fetch(`/products/search?q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(products => {
            if (products.length === 0) {
                resultsDiv.innerHTML = '<div class="p-2 text-gray-500">No products found</div>';
            } else {
                resultsDiv.innerHTML = products.map(p =>
                    `<div class="p-2 hover:bg-blue-100 cursor-pointer" onclick="addMedicationFromSearch(${p.id}, '${p.name.replace(/'/g, "\\'")}')">${p.name}</div>`
                ).join('');
            }
            resultsDiv.classList.remove('hidden');
        });
});

// Hide results when clicking outside
document.addEventListener('click', function(e) {
    if (!document.getElementById('product_search').contains(e.target) &&
        !document.getElementById('product_results').contains(e.target)) {
        document.getElementById('product_results').classList.add('hidden');
    }
});

// Add medication from search
function addMedicationFromSearch(productId, productName) {
    const medicationsDiv = document.getElementById('medications');
    const html = `
        <div class="flex items-center gap-2">
            <input type="hidden" name="medications[${medicationIndex}][product_id]" value="${productId}">
            <span class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50">${productName}</span>
            <input type="number" name="medications[${medicationIndex}][quantity]" placeholder="Qty *" min="1" value="1" required
                   class="w-20 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    `;
    medicationsDiv.insertAdjacentHTML('beforeend', html);
    medicationIndex++;
    document.getElementById('product_search').value = '';
    document.getElementById('product_results').classList.add('hidden');
}
</script>
@endsection