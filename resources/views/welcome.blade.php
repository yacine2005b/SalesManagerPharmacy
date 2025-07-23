@extends('layout.layout')

@section('content')
@auth
    <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <!-- Sale Session Section -->
    <div class="mb-6">
        @if(!$activeSession)
            <form action="{{ route('sales.session.start') }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Start Sale Session
                </button>
            </form>
        @else
            <form action="{{ route('sales.session.end', $activeSession->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    End Sale Session
                </button>
            </form>
        @endif
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Sales Today</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSalesToday, 2) }} DA</p>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Total Transactions</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalTransactions }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <p class="text-sm font-medium text-gray-500">Active Sale Sessions</p>
            <p class="text-2xl font-bold text-gray-800">{{ $activeSessionsCount }}</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Sale Sessions & Revenue This Month</h2>
        <canvas id="sessionsChart" height="100"></canvas>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h2 class="font-semibold text-gray-800">Recent Transactions</h2>
        </div>
        @if($recentTransactions->isEmpty())
            <div class="p-6 text-center">
                <p class="text-sm text-gray-500">No recent transactions available.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentTransactions as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $transaction->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                {{ number_format($transaction->total_amount, 2) }} DA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Chart.js Script -->
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

@endauth
@guest
   <h1 class="text-center text-2xl text-bold mt-7">Please log in to access the dashboard.</h1> 
@endguest
@endsection
