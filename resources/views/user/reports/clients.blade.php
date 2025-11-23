@extends('layouts.user')

@section('page-title', 'Client Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <a href="{{ route('user.reports.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-2">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Reports
        </a>
        <h3 class="text-lg font-semibold text-gray-900">Client-wise Report</h3>
        <p class="text-sm text-gray-600 mt-1">Analyze business by client</p>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-base font-semibold text-gray-900 mb-4">Revenue by Client (Top 10)</h4>
        <div class="max-w-md mx-auto">
            <canvas id="clientRevenueChart"></canvas>
        </div>
    </div>

    <!-- Client Table -->
    @if($report->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-base font-semibold text-gray-900">All Clients</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Business</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Invoices</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Paid</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($report as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $data['client']->name }}</p>
                                        @if($data['client']->company)
                                            <p class="text-xs text-gray-500">{{ $data['client']->company }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-semibold text-gray-900">
                                    ${{ number_format($data['total_business'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right text-gray-600">
                                    {{ $data['total_invoices'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right text-green-600">
                                    ${{ number_format($data['total_paid'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right {{ $data['outstanding'] > 0 ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    ${{ number_format($data['outstanding'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                        <tr>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">Total</td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">
                                ${{ number_format($report->sum('total_business'), 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">
                                {{ $report->sum('total_invoices') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-green-600">
                                ${{ number_format($report->sum('total_paid'), 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-red-600">
                                ${{ number_format($report->sum('outstanding'), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="mt-4 text-sm text-gray-500">No client data available</p>
            <a href="{{ route('user.clients.create') }}" class="mt-2 inline-block text-sm font-medium text-blue-600 hover:text-blue-700">
                Create your first client →
            </a>
        </div>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('clientRevenueChart').getContext('2d');
    
    // Generate random colors for pie chart
    const colors = [
        'rgba(59, 130, 246, 0.8)',
        'rgba(16, 185, 129, 0.8)',
        'rgba(249, 115, 22, 0.8)',
        'rgba(139, 92, 246, 0.8)',
        'rgba(236, 72, 153, 0.8)',
        'rgba(14, 165, 233, 0.8)',
        'rgba(34, 197, 94, 0.8)',
        'rgba(251, 146, 60, 0.8)',
        'rgba(168, 85, 247, 0.8)',
        'rgba(244, 63, 94, 0.8)',
    ];
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                data: {!! json_encode($chartData['data']) !!},
                backgroundColor: colors,
                borderColor: colors.map(c => c.replace('0.8', '1')),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += '$' + context.parsed.toFixed(2);
                            
                            // Calculate percentage
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            label += ' (' + percentage + '%)';
                            
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
