@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">
    <!-- Sale Type Navigation -->
    <div class="flex space-x-4 mb-6">
        <a href="{{route("pos.normal")}}" class="px-4 py-2  rounded transition-colors">
            normal Sale
        </a>
        <a  href="{{route("pos.insurance")}}" class="px-4 py-2 rounded transition-colors">
            Insurance Sale
        </a>
        <a href="{{route("pos.prescription")}}" class="px-4 py-2 rounded transition-colors">prescription sale</a>
    </div>

    @if($activeSession)
        <div class="grid grid-cols-3 gap-6">
            <!-- Product List -->
            @include('pos.listProducts', ['isInsuranceSale' => $isInsuranceSale])

            <!-- Cart -->
            @include('pos.checkout', ['isInsuranceSale' => $isInsuranceSale])
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

<script>
    // Load prescription medications into the cart
    document.getElementById('prescription_id').addEventListener('change', function () {
        const prescriptionId = this.value;

        if (prescriptionId) {
            fetch(`/load-prescription-to-cart`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ prescription_id: prescriptionId }),
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