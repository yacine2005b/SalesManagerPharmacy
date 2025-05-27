@extends('layout.layout')

@section('content')
@include('pos.nav')
@php
  
    $isInsuranceSale = true; // Set this to true for insurance sales
@endphp
<div class="container mx-auto p-6">
    @if ($activeSession)
        @if (session('current_prescription'))
            <div class="mb-6 p-4 bg-gray-100 border border-gray-300 rounded-md">
                <h3 class="text-lg font-semibold text-gray-800">Current Prescription</h3>
                <p class="text-sm text-gray-600">
                    <strong>Doctor:</strong> {{ session('current_prescription')['doctor_name'] }}<br>
                    <strong>Patient:</strong> {{ session('current_prescription')['patient_name'] }}
                </p>
            </div>
        @endif
        <!-- Prescription Selection -->
        <div class="mb-6">
            <form action="{{ route('pos.loadPrescriptionToCart') }}" method="POST">
                @csrf
                    <input type="hidden" name="sale_type" value="{{ $isInsuranceSale ? 'insurance' : 'prescription' }}">
                <label for="prescription_id" class="block text-sm font-medium text-gray-700">Select Prescription</label>
                <select id="prescription_id" name="prescription_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="">-- Select Prescription --</option>
                    @foreach($prescriptions as $prescription)
                        <option value="{{ $prescription->id }}">
                            {{ $prescription->doctor->name }} - {{ $prescription->patient->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Cart Section -->
            <div>
                @include('pos.checkout')
            </div>
            <!-- Insurance Form Section -->
            <div>
                @include('cart.insuranceForm')
            </div>
        </div>
    @else
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
            <p><strong>No Active Sale Session:</strong></p>
            <p>Please start a sale session to use the POS system.</p>
        </div>
        <form action="{{ route('sales.session.start') }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Start Sale Session
            </button>
        </form>
    @endif
</div>
@endsection
