@extends('layout.layout')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Prescription Details</h1>

    <!-- Prescription Information -->
    <div class="bg-white shadow-md rounded p-4 mb-6">
        <h2 class="text-xl font-semibold mb-4">Prescription Information</h2>
        <p><strong>Doctor Name:</strong> {{ $prescription->doctor_name }}</p>
        <p><strong>Patient Name:</strong> {{ $prescription->patient_name }}</p>
        <p><strong>Patient Phone:</strong> {{ $prescription->patient_phone ?? 'N/A' }}</p>
        <p><strong>Notes:</strong> {{ $prescription->notes ?? 'N/A' }}</p>
        <p><strong>Duration:</strong> {{ $prescription->duration ?? 'N/A' }} days</p>

        <p><strong>Status:</strong> {{ ucfirst($prescription->status) }}</p>
    </div>

    <!-- Medications -->
    <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-xl font-semibold mb-4">Medications</h2>
        @if($prescription->medications->isEmpty())
            <p>No medications found for this prescription.</p>
        @else
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                    
                        <th class="border border-gray-300 px-4 py-2 text-left">Product Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescription->medications as $medication)
                        <tr>
                         
                            <td class="border border-gray-300 px-4 py-2">{{ $medication->product->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $medication->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('prescription.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Back to Prescriptions</a>
    </div>
</div>
@endsection