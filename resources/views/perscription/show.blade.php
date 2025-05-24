@extends('layout.layout')

@section('content')
<div class="container mx-auto p-4 max-w-6xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Prescription Details</h1>
        <a href="{{ route('prescription.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Prescriptions
        </a>
    </div>

    <!-- Prescription Information -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6 border border-gray-100">
        <h2 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">Prescription Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Doctor Name</p>
                    <p class="text-gray-800">{{ $prescription->doctor->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Doctor Phone</p>
                    <p class="text-gray-800">{{ $prescription->doctor->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Prescription Status</p>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $prescription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($prescription->status) }}
                    </span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Patient Name</p>
                    <p class="text-gray-800">{{ $prescription->patient->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Patient Phone</p>
                    <p class="text-gray-800">{{ $prescription->patient->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Duration</p>
                    <p class="text-gray-800">{{ $prescription->duration ?? 'N/A' }} days</p>
                </div>
            </div>
        </div>
        
        @if($prescription->notes)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm font-medium text-gray-500 mb-1">Notes</p>
            <p class="text-gray-800 bg-gray-50 p-3 rounded-md">{{ $prescription->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Medications -->
    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Medications</h2>
            <span class="text-sm text-gray-500">{{ $prescription->medications->count() }} items</span>
        </div>
        
        @if($prescription->medications->isEmpty())
            <div class="bg-gray-50 p-4 rounded-md text-center text-gray-500">
                No medications found for this prescription.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($prescription->medications as $medication)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $medication->product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $medication->quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection