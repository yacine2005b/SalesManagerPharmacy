@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">
    @php
        $isInsuranceSale = true; 
    @endphp

    <div class="grid grid-cols-3 gap-6">
        <!-- Product List -->
        @include('pos.listProducts', ['isInsuranceSale' => $isInsuranceSale])

        <!-- Cart -->
        @include('pos.checkout', ['isInsuranceSale' => $isInsuranceSale])
    </div>
</div>
@endsection
