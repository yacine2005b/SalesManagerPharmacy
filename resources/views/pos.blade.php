@extends('layout.layout')

@section('content')
    @include('pos.nav')

    <div class="container mx-auto p-6">
        {{-- Unified Input for Barcode, Name, or Batch --}}
       

<div>
  @if ($activeSession)
   @include('shared.smartSearch')
            <div class="flex">
                <!-- Cart -->
                <div class="w-full">
                    @include('pos.checkout')
                </div>

            </div>
        @else
            <!-- No Active Sale Session -->
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                <p><strong>No Active Sale Session:</strong></p>
                <p>Please start a sale session to use the POS system.</p>
            </div>

            <!-- Start Sale Session Button -->
            <form action="{{ route('sales.session.start') }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Start Sale Session
                </button>
            </form>
        @endif
</div>
      
    </div>

    <script>
        // Always keep the input focused
        function focusInput() {
            document.getElementById('barcode_or_search_input').focus();
        }
        window.onload = focusInput;
        document.addEventListener('click', focusInput);

        // Unified input logic: add product to cart by barcode, name, or batch number
        document.getElementById('barcode_or_search_input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                this.value = '';
                if (query) {
                    fetch('{{ route('cart.smartAdd') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                query: query
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload(); // Or update cart section dynamically
                            } else {
                                alert(data.message || 'Product not found.');
                            }
                        });
                }
            }
        });

        // Load prescription medications into the cart
        document.getElementById('prescription_id')?.addEventListener('change', function() {
            const prescriptionId = this.value;

            if (prescriptionId) {
                fetch(`/load-prescription-to-cart`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            prescription_id: prescriptionId
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    });
            }
        });
    </script>
@endsection
