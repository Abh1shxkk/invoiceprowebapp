@extends('layouts.user')

@section('page-title', 'Client Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('user.clients.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Clients
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Information Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Client Information</h3>
                    <a href="{{ route('user.clients.edit', $client) }}" class="text-blue-600 hover:text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Name</p>
                        <p class="text-base text-gray-900 mt-1">{{ $client->name }}</p>
                    </div>

                    @if($client->company)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Company</p>
                            <p class="text-base text-gray-900 mt-1">{{ $client->company }}</p>
                        </div>
                    @endif

                    @if($client->email)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <a href="mailto:{{ $client->email }}" class="text-base text-blue-600 hover:text-blue-700 mt-1 block">
                                {{ $client->email }}
                            </a>
                        </div>
                    @endif

                    @if($client->phone)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Phone</p>
                            <a href="tel:{{ $client->phone }}" class="text-base text-blue-600 hover:text-blue-700 mt-1 block">
                                {{ $client->phone }}
                            </a>
                        </div>
                    @endif

                    @if($client->address || $client->city || $client->state || $client->zip || $client->country)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Address</p>
                            <div class="text-base text-gray-900 mt-1">
                                @if($client->address)
                                    <p>{{ $client->address }}</p>
                                @endif
                                @if($client->city || $client->state || $client->zip)
                                    <p>{{ $client->city }}{{ $client->city && ($client->state || $client->zip) ? ', ' : '' }}{{ $client->state }} {{ $client->zip }}</p>
                                @endif
                                @if($client->country)
                                    <p>{{ $client->country }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($client->tax_number)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tax ID / VAT</p>
                            <p class="text-base text-gray-900 mt-1">{{ $client->tax_number }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm font-medium text-gray-500">Client Since</p>
                        <p class="text-base text-gray-900 mt-1">{{ $client->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                    <a href="{{ route('user.clients.edit', $client) }}" class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                        Edit Client
                    </a>
                    <form action="{{ route('user.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                            Delete Client
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics and Invoices -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Invoices</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalInvoices }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Business</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($totalBusiness, 2) }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Outstanding</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($outstandingAmount, 2) }}</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices List -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Invoices</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($client->invoices as $invoice)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $invoice->issue_date->format('M d, Y') }}
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="mt-4 text-sm text-gray-500">No invoices yet for this client</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
