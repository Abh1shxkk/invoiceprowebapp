@extends('layouts.user')

@section('page-title', 'Invoice Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('user.invoices.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Invoices
        </a>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('user.invoices.edit', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
        <a href="{{ route('user.invoices.pdf', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Download PDF
        </a>
        @if($invoice->status !== 'paid')
            <form action="{{ route('user.invoices.mark-paid', $invoice) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mark as Paid
                </button>
            </form>
        @endif
        @if($invoice->status === 'draft')
            <form action="{{ route('user.invoices.mark-sent', $invoice) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Mark as Sent
                </button>
            </form>
        @endif
        @if($invoice->status !== 'paid')
            <a href="{{ route('user.payments.create', ['invoice_id' => $invoice->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Record Payment
            </a>
        @endif
        @if($invoice->client->email)
            <form action="{{ route('user.invoices.send-email', $invoice) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send Email
                </button>
            </form>
        @endif
        <form action="{{ route('user.invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete
            </button>
        </form>
    </div>

    <!-- Invoice Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Invoice Header -->
        <div class="px-8 py-6 border-b border-gray-200">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $invoice->invoice_number }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Status: 
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $invoice->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Issue Date</p>
                    <p class="text-base font-semibold text-gray-900">{{ $invoice->issue_date->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-600 mt-2">Due Date</p>
                    <p class="text-base font-semibold text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Client Details -->
        <div class="px-8 py-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Bill To</h3>
                    <p class="text-base font-semibold text-gray-900">{{ $invoice->client->name }}</p>
                    @if($invoice->client->company)
                        <p class="text-sm text-gray-600">{{ $invoice->client->company }}</p>
                    @endif
                    @if($invoice->client->email)
                        <p class="text-sm text-gray-600">{{ $invoice->client->email }}</p>
                    @endif
                    @if($invoice->client->phone)
                        <p class="text-sm text-gray-600">{{ $invoice->client->phone }}</p>
                    @endif
                    @if($invoice->client->address)
                        <p class="text-sm text-gray-600 mt-2">{{ $invoice->client->address }}</p>
                        @if($invoice->client->city || $invoice->client->state || $invoice->client->zip)
                            <p class="text-sm text-gray-600">
                                {{ $invoice->client->city }}{{ $invoice->client->city && ($invoice->client->state || $invoice->client->zip) ? ', ' : '' }}
                                {{ $invoice->client->state }} {{ $invoice->client->zip }}
                            </p>
                        @endif
                        @if($invoice->client->country)
                            <p class="text-sm text-gray-600">{{ $invoice->client->country }}</p>
                        @endif
                    @endif
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">From</h3>
                    <p class="text-base font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="px-8 py-6">
            <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Invoice Items</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($invoice->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->description }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 text-right">{{ number_format($item->quantity, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 text-right">${{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">${{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals -->
        <div class="px-8 py-6 border-t border-gray-200">
            <div class="flex justify-end">
                <div class="w-full md:w-1/2 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                        <span class="text-base font-semibold text-gray-900">${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">Tax ({{ number_format($invoice->tax, 2) }}%):</span>
                        <span class="text-base font-semibold text-gray-900">${{ number_format(($invoice->subtotal * $invoice->tax) / 100, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                        <span class="text-base font-semibold text-gray-900">Total:</span>
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($invoice->total, 2) }}</span>
                    </div>
                    @php
                        $totalPaid = $invoice->payments->sum('amount');
                        $outstanding = $invoice->total - $totalPaid;
                    @endphp
                    @if($totalPaid > 0)
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-sm font-medium text-green-700">Paid:</span>
                            <span class="text-base font-semibold text-green-700">${{ number_format($totalPaid, 2) }}</span>
                        </div>
                    @endif
                    @if($outstanding > 0)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-red-700">Outstanding:</span>
                            <span class="text-base font-semibold text-red-700">${{ number_format($outstanding, 2) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
            <div class="px-8 py-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 uppercase mb-2">Notes</h3>
                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $invoice->notes }}</p>
            </div>
        @endif

        <!-- Payment History -->
        @if($invoice->payments->count() > 0)
            <div class="px-8 py-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 uppercase mb-4">Payment History</h3>
                <div class="space-y-3">
                    @foreach($invoice->payments as $payment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</p>
                                <p class="text-xs text-gray-600">{{ $payment->payment_method }} - {{ $payment->payment_date->format('M d, Y') }}</p>
                                @if($payment->notes)
                                    <p class="text-xs text-gray-500 mt-1">{{ $payment->notes }}</p>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Paid</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
