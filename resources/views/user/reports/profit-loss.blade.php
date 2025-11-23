@extends('layouts.user')

@section('page-title', 'Profit/Loss Report')

@section('content')
<div class="space-y-6">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('user.reports.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-2">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Reports
            </a>
            <h3 class="text-lg font-semibold text-gray-900">Profit/Loss Report</h3>
            <p class="text-sm text-gray-600 mt-1">Revenue vs Expenses analysis</p>
        </div>
        <a href="{{ route('user.reports.profit-loss.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export PDF
        </a>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form action="{{ route('user.reports.profit-loss') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                       class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                Apply Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($report['total_revenue'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600 mb-1">Total Expenses</p>
            <p class="text-2xl font-bold text-red-600">${{ number_format($report['total_expenses'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600 mb-1">Net {{ $report['profit'] >= 0 ? 'Profit' : 'Loss' }}</p>
            <p class="text-2xl font-bold {{ $report['profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                ${{ number_format(abs($report['profit']), 2) }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600 mb-1">Profit Margin</p>
            <p class="text-2xl font-bold {{ $report['profit_margin'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ number_format($report['profit_margin'], 2) }}%
            </p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-base font-semibold text-gray-900 mb-4">Revenue vs Expenses (Last 6 Months)</h4>
        <canvas id="revenueExpenseChart" height="80"></canvas>
    </div>

    <!-- Monthly Breakdown -->
    @if($report['monthly_data']->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-base font-semibold text-gray-900">Monthly Breakdown</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Revenue</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Expenses</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Profit/Loss</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($report['monthly_data'] as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $data['month'] }}</td>
                                <td class="px-6 py-4 text-sm text-right text-green-600">${{ number_format($data['revenue'], 2) }}</td>
                                <td class="px-6 py-4 text-sm text-right text-red-600">${{ number_format($data['expenses'], 2) }}</td>
                                <td class="px-6 py-4 text-sm text-right font-semibold {{ $data['profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    ${{ number_format(abs($data['profit']), 2) }}
                                    @if($data['profit'] < 0)
                                        <span class="text-xs">(Loss)</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueExpenseChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Revenue',
                    data: {!! json_encode($chartData['revenue']) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Expenses',
                    data: {!! json_encode($chartData['expenses']) !!},
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += '$' + context.parsed.y.toFixed(2);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toFixed(0);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
