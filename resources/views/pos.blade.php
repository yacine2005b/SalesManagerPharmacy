@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">
    @if($activeSession)
        <!-- Active Sale Session Details -->
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            <p><strong>Active Sale Session:</strong></p>
            <p>Session ID: {{ $activeSession->id }}</p>
            <p>Started At: {{ $activeSession->start_time->format('Y-m-d H:i:s') }}</p>
        </div>

        @php
            $isInsuranceSale = false; 
        @endphp

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
@endsection
