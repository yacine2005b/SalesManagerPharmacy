
@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Sale Details</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Product</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Batch Number</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Quantity</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Final Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItems as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->product->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->lot->batch_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">${{ number_format($item->final_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('sales.history') }}" class="mt-6 inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        Back to Sales History
    </a>
</div>
@endsection
