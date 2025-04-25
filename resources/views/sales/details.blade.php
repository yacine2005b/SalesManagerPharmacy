@extends('layout.layout')

@section('content')
<div class="container mx-auto p-4 md:p-6">
    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Sale Session Details</h1>
            <p class="text-sm text-gray-500 mt-1">Detailed information for Session #{{ $saleSession->id }}</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full text-xs font-medium 
                {{ $saleSession->end_time ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                {{ $saleSession->end_time ? 'Closed' : 'Active' }}
            </span>
        </div>
    </div>

    <!-- Session Summary Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-gray-800">Session Summary</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Session ID</p>
                <p class="font-medium">#{{ $saleSession->id }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Cashier</p>
                <p class="font-medium">{{ $saleSession->user->name }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Duration</p>
                <p class="font-medium">
                    @if($saleSession->end_time)
                        {{ $saleSession->start_time->diff($saleSession->end_time)->format('%Hh %Im') }}
                    @else
                        {{ $saleSession->start_time->diffInHours(now()) }}h {{ $saleSession->start_time->diffInMinutes(now()) % 60 }}m
                    @endif
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Started At</p>
                <p class="font-medium">{{ $saleSession->start_time->format('M d, Y h:i A') }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Ended At</p>
                <p class="font-medium">{{ $saleSession->end_time ? $saleSession->end_time->format('M d, Y h:i A') : 'Still Active' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Total Sales</p>
                <p class="font-medium">{{ $sales->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Sales Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($sales->sum('total_amount'), 2) }} DA</p>
                </div>
                <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Patient Payments</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($sales->sum('patient_pays'), 2) }} DA</p>
                </div>
                <div class="p-3 rounded-full bg-green-50 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Completed Sales</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $sales->where('status', 'completed')->count() }}</p>
                </div>
                <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
        <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="font-semibold text-gray-800">Transaction List</h2>
            <p class="text-sm text-gray-500">{{ $sales->count() }} transactions</p>
        </div>
        
        @if($sales->isEmpty())
            <div class="p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No transactions recorded</h3>
                <p class="mt-1 text-sm text-gray-500">This session doesn't have any sales yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Paid</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ $sale->id }}</div>
                                <div class="text-xs text-gray-500">Invoice {{ $sale->invoice_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $sale->created_at->format('h:i A') }}<br>
                                <span class="text-xs text-gray-400">{{ $sale->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ number_format($sale->total_amount, 2) }} DA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                                {{ number_format($sale->patient_pays, 2) }} DA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1.5 text-xs rounded-full 
                                    {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($sale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/" class="text-blue-600 hover:text-blue-900">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <!-- Pagination would go here if needed -->
            </div>
        @endif
    </div>
</div>
@endsection