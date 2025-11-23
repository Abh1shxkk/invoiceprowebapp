@extends('layouts.user')

@section('page-title', 'Revenue Report')

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
            <h3 class="text-lg font-semibold text-gray-900">Revenue Report</h3>
            <p class="text-sm text-gray-600 mt-1">Track your income and paid invoices</p>
        </div>
        <a href="{{ route('user.reports.revenue.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export PDF
        </a>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form action="{{ route('user.reports.revenue') }}" method="GET" class="flex flex-wrap gap-4 items-end">
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
            <p class="text-sm font-medium text-gray-600 mb-1">Paid Invoices</p>
            <p class="text-2xl font-bold text-blue-600">{{ $report['total_paid_invoices'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600 mb-1">Pending Invoices</p>
            <p class="text-2xl font-bold text-orange-600">{{ $report['total_pending_invoices'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600 mb-1">Pending Amount</p>
            <p class="text-2xl font-bold text-red-600">${{ number_format($report['pending_amount'], 2) }}</p>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    @if($report['monthly_data']->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-base font-semibold text-gray-900">Monthly Revenue Breakdown</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Revenue</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Invoices</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($report['monthly_data'] as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $data['month'] }}</td>
                                <td class="px-6 py-4 text-sm text-right text-green-600 font-semibold">${{ number_format($data['revenue'], 2) }}</td>
                                <td class="px-6 py-4 text-sm text-right text-gray-600">{{ $data['count'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                        <tr>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">Total</td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-green-600">${{ number_format($report['total_revenue'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">{{ $report['total_paid_invoices'] }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="mt-4 text-sm text-gray-500">No revenue data for the selected period</p>
        </div>
    @endif
</div>
@endsection
