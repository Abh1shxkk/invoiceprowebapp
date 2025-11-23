@extends('layouts.user')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Quick Action Buttons -->
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('user.invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Invoice
        </a>
        <a href="{{ route('user.expenses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Expense
        </a>
        <a href="{{ route('user.clients.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Client
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Clients Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Clients</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalClients }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Invoices This Month Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Invoices This Month</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $invoicesThisMonth }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Invoices Amount Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Amount</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($pendingInvoicesAmount, 2) }}</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Expenses This Month Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Expenses This Month</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($expensesThisMonth, 2) }}</p>
                </div>
                <div class="p-3 bg-red-50 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue vs Expenses Comparison -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">This Month Overview</h3>
            
            <div class="space-y-4">
                <!-- Revenue -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Revenue</span>
                        <span class="text-sm font-semibold text-green-600">${{ number_format($revenueThisMonth, 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $maxAmount = max($revenueThisMonth, $expensesThisMonth);
                            $revenuePercentage = $maxAmount > 0 ? ($revenueThisMonth / $maxAmount) * 100 : 0;
                        @endphp
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $revenuePercentage }}%"></div>
                    </div>
                </div>

                <!-- Expenses -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Expenses</span>
                        <span class="text-sm font-semibold text-red-600">${{ number_format($expensesThisMonth, 2) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $expensePercentage = $maxAmount > 0 ? ($expensesThisMonth / $maxAmount) * 100 : 0;
                        @endphp
                        <div class="bg-red-600 h-2 rounded-full" style="width: {{ $expensePercentage }}%"></div>
                    </div>
                </div>

                <!-- Net Profit -->
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Net Profit</span>
                        <span class="text-lg font-bold {{ ($revenueThisMonth - $expensesThisMonth) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($revenueThisMonth - $expensesThisMonth, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Invoices Table -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Invoices</h3>
                <a href="{{ route('user.invoices.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                    View All →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentInvoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm text-gray-600">{{ $invoice->client->name ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-semibold text-gray-900">${{ number_format($invoice->total, 2) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $invoice->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $invoice->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $invoice->issue_date->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="mt-4 text-sm text-gray-500">No invoices yet</p>
                                    <a href="{{ route('user.invoices.index') }}" class="mt-2 inline-block text-sm font-medium text-blue-600 hover:text-blue-700">
                                        Create your first invoice →
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
