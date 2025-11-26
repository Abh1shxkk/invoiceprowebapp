@extends('layouts.user')

@section('page-title', 'Invoices')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button and Filters -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">All Invoices</h3>
            <p class="text-sm text-gray-600 mt-1">Manage your invoices and track payments</p>
        </div>
        <a href="{{ route('user.invoices.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Invoice
        </a>
    </div>

    <!-- Status Filter Tabss -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('user.invoices.index') }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ !request('status') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                All <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-200">{{ $statusCounts['all'] }}</span>
            </a>
            <a href="{{ route('user.invoices.index', ['status' => 'draft']) }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') === 'draft' ? 'bg-gray-50 text-gray-700' : 'text-gray-600 hover:bg-gray-100' }}">
                Draft <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-200">{{ $statusCounts['draft'] }}</span>
            </a>
            <a href="{{ route('user.invoices.index', ['status' => 'sent']) }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') === 'sent' ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                Sent <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-200">{{ $statusCounts['sent'] }}</span>
            </a>
            <a href="{{ route('user.invoices.index', ['status' => 'paid']) }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') === 'paid' ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-100' }}">
                Paid <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-200">{{ $statusCounts['paid'] }}</span>
            </a>
            <a href="{{ route('user.invoices.index', ['status' => 'overdue']) }}" 
               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') === 'overdue' ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-100' }}">
                Overdue <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-200">{{ $statusCounts['overdue'] }}</span>
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form action="{{ route('user.invoices.index') }}" method="GET" class="flex gap-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="flex-1 relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by invoice number or client name..." 
                       class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            @if(request('search'))
                <a href="{{ route('user.invoices.index', request()->except('search')) }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $invoice->client->name }}</p>
                                @if($invoice->client->company)
                                    <p class="text-xs text-gray-500">{{ $invoice->client->company }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $invoice->issue_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $invoice->due_date->format('M d, Y') }}
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
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('user.invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('user.invoices.edit', $invoice) }}" class="text-gray-600 hover:text-gray-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('user.invoices.pdf', $invoice) }}" class="text-green-600 hover:text-green-900" title="Download PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('user.invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-gray-500">
                                    @if(request('search'))
                                        No invoices found matching "{{ request('search') }}"
                                    @elseif(request('status'))
                                        No {{ request('status') }} invoices
                                    @else
                                        No invoices yet
                                    @endif
                                </p>
                                @if(!request('search') && !request('status'))
                                    <a href="{{ route('user.invoices.create') }}" class="mt-2 inline-block text-sm font-medium text-blue-600 hover:text-blue-700">
                                        Create your first invoice →
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $invoices->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
