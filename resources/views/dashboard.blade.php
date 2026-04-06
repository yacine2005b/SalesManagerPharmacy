@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">

    <!-- Top Stats -->
    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-4 rounded shadow text-center">
            <h3 class="text-gray-600 text-sm">Total Sales Today</h3>
            <p class="text-2xl font-bold text-green-600">
                {{ number_format($totalSalesToday, 2) }} DA
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h3 class="text-gray-600 text-sm">Active Sessions</h3>
            <p class="text-2xl font-bold text-blue-600">
                {{ $activeSessionsCount }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h3 class="text-gray-600 text-sm">My Transactions</h3>
            <p class="text-2xl font-bold text-purple-600">
                {{ $totalTransactions }}
            </p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h3 class="text-gray-600 text-sm">Recent Transactions</h3>
            <p class="text-2xl font-bold text-orange-600">
                {{ $recentTransactions->count() }}
            </p>
        </div>
    </div>

    <!-- Sale Session Controls -->
    <div class="bg-white p-6 rounded shadow mb-8">
        <h2 class="text-xl font-bold mb-4">Point of Sale</h2>
        @if(!$activeSession)
            <form action="{{ route('sales.session.start') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Start Sale Session
                </button>
            </form>
        @else
            <form action="{{ route('sales.session.end', $activeSession->id) }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    End Sale Session
                </button>
            </form>
        @endif
    </div>

    <!-- Recent Transactions Table -->
    <div class="bg-white p-6 rounded shadow mb-8">
        <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-2">#</th>
                    <th class="p-2">Type</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Total Amount</th>
                    <th class="p-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $transaction)
                    <tr class="border-t">
                        <td class="p-2">{{ $transaction->id }}</td>
                        <td class="p-2 capitalize">{{ $transaction->type }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 rounded text-xs
                                @if($transaction->status === 'completed') bg-green-100 text-green-700
                                @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="p-2">{{ number_format($transaction->total_amount, 2) }} DA</td>
                        <td class="p-2">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-bold mb-4">Sale Sessions & Revenue This Month</h2>
        <canvas id="sessionsChart" height="100"></canvas>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('sessionsChart').getContext('2d');
    const sessionsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Sessions per Day',
                    data: @json($sessionCounts),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y',
                },
                {
                    label: 'Total Money (DA)',
                    data: @json($moneyTotals),
                    type: 'line',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    fill: false,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: { display: true, text: 'Sessions' }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: { display: true, text: 'Money (DA)' },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
</script>
@endpush
