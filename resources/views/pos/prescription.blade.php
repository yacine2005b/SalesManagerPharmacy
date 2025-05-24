@extends('layout.layout')

@section('content')
@include('pos.nav')
    <!-- Current Prescription Details -->
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

    <!-- Cart -->
    <div class="grid grid-cols-3 gap-6">
        @include('pos.checkout', ['isInsuranceSale' => $isInsuranceSale])
    </div>
@endsection