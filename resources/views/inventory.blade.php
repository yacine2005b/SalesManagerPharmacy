@extends('layout.layout')

@section('content')

{{-- Persistent Stock Threshold Alert Section --}}
<div class="mb-4">
    <div class="@if($lowStockProducts->count()) bg-yellow-50 border-l-4 border-yellow-400 @else bg-green-50 border-l-4 border-green-400 @endif p-4 rounded-md shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                @if($lowStockProducts->count())
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                @else
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                @endif
            </div>
            <div class="ml-3">
                @if($lowStockProducts->count())
                   
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul role="list" class="list-disc pl-5 space-y-1">
                            @foreach($lowStockProducts as $product)
                                <li>
                                    {{ $product->name }} - 
                                    <span class="font-semibold">{{ $product->total_quantity }} left</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    
                    <div class="mt-2 text-sm text-green-700">
                        <p>All products are above the low stock threshold.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="flex gap-4">
    @include('products.allProducts')
    @include('products.create')
</div>

@endsection