@extends('layout.layout')

@section('content')
<div class="container mx-auto p-6">

    <div class="grid grid-cols-3 gap-6">
      <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Point of Sale</h1>
    
        <!-- Start Sale Session Button -->
        @if(!$activeSession)
            <form action="{{ route('sales.session.start') }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Start Sale Session
                </button>
            </form>
        @else
            <!-- End Sale Session Button -->
            <form action="{{ route('sales.session.end', $activeSession->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    End Sale Session
                </button>
            </form>
        @endif
      </div>
      <!-- Add more dashboard cards here if needed -->
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded shadow p-6 mt-8">
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
