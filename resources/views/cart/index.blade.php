@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">🛒 Current Cart</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('cart') && count(session('cart')) > 0)
        <div class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="w-full table-auto text-sm text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Product</th>
                        <th class="p-3">Lot ID</th>
                        <th class="p-3">Expiration</th>
                        <th class="p-3 text-center">Quantity</th>
                        <th class="p-3 text-right">Price</th>
                        <th class="p-3 text-right">Subtotal</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach(session('cart') as $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $item['name'] }}</td>
                            <td class="p-3">#{{ $item['lot_id'] }}</td>
                            <td class="p-3">{{ $item['lot_expiration'] }}</td>
                            <td class="p-3 text-center">
                                <form method="POST" action="{{ route('cart.update') }}" class="flex justify-center items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                    <input type="hidden" name="lot_id" value="{{ $item['lot_id'] }}">
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 text-center border rounded p-1">
                                    <button type="submit" class="text-blue-600 hover:underline">✔️</button>
                                </form>
                            </td>
                            <td class="p-3 text-right">{{ $item['price'] }} DA</td>
                            <td class="p-3 text-right">{{ $subtotal }} DA</td>
                            <td class="p-3 text-center">
                                <form method="POST" action="{{ route('cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                    <input type="hidden" name="lot_id" value="{{ $item['lot_id'] }}">
                                    <button type="submit" class="text-red-600 hover:underline">🗑 Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="border-t bg-gray-50 font-bold">
                        <td colspan="5" class="p-3 text-right">Total:</td>
                        <td class="p-3 text-right">{{ $total }} DA</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-right">
            <a href="{{ route('pos') }}"
               class="inline-block bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                ✅ Proceed to Checkout
            </a>
        </div>
    @else
        <p class="text-gray-500">Your cart is empty.</p>
    @endif
</div>
@endsection
