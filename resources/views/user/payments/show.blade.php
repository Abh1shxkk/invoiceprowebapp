@extends('layouts.user')

@section('page-title', 'Payment Details')

@section('content')
<div class="max-w-2xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('user.invoices.show', $payment->invoice) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Invoice
        </a>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3 mb-6">
        <a href="{{ route('user.payments.edit', $payment) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
        <form action="{{ route('user.payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this payment?');">
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

    <!-- Payment Details Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
        </div>

        <div class="p-6 space-y-6">
            <!-- Amount -->
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Amount Paid</p>
                <p class="text-3xl font-bold text-green-600">${{ number_format($payment->amount, 2) }}</p>
            </div>

            <!-- Invoice Info -->
            <div class="pt-6 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-500 mb-3">Invoice Information</p>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Invoice Number:</span>
                        <a href="{{ route('user.invoices.show', $payment->invoice) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                            {{ $payment->invoice->invoice_number }}
                        </a>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Client:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->invoice->client->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Invoice Total:</span>
                        <span class="text-sm font-medium text-gray-900">${{ number_format($payment->invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Date -->
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Payment Date</p>
                        <p class="text-base text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</p>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Payment Method</p>
                        <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                            {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($payment->notes)
                <div class="pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-500 mb-2">Reference / Notes</p>
                    <p class="text-base text-gray-900 whitespace-pre-line">{{ $payment->notes }}</p>
                </div>
            @endif

            <!-- Metadata -->
            <div class="pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Recorded On</p>
                        <p class="text-gray-900">{{ $payment->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Last Updated</p>
                        <p class="text-gray-900">{{ $payment->updated_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
