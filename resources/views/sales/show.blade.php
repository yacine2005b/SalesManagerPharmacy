@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Sale Invoice</h1>

        <!-- Sale Details -->
        <div class="mb-6">
            <p><strong>Sale ID:</strong> {{ $sale->id }}</p>
            <p><strong>Date:</strong> {{ $sale->created_at->format('M d, Y H:i') }}</p>
            <p><strong>Type:</strong> {{ ucfirst($sale->type) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($sale->status) }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($sale->total_amount, 2) }}</p>
            <p><strong>Covered Amount:</strong> ${{ number_format($sale->covered_amount, 2) }}</p>
            <p><strong>Patient Pays:</strong> ${{ number_format($sale->patient_pays, 2) }}</p>
        </div>

        <!-- Sale Items -->
        <h2 class="text-xl font-semibold mb-4">Items</h2>
        <table class="min-w-full divide-y divide-gray-200 border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($sale->saleItems as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($item->original_price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($item->quantity * $item->original_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Print Invoice Button -->
        <div class="mt-6">
            <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Print Invoice
            </button>
        </div>
    </div>
</div>
@endsection