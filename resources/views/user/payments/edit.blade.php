@extends('layouts.user')

@section('page-title', 'Edit Payment')

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

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Edit Payment</h3>
            <p class="text-sm text-gray-600 mt-1">Update payment details</p>
        </div>

        <form action="{{ route('user.payments.update', $payment) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Invoice Info (Read-only) -->
            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div>
                    <p class="text-sm font-medium text-blue-900">Invoice: {{ $payment->invoice->invoice_number }}</p>
                    <p class="text-sm text-blue-700 mt-1">Client: {{ $payment->invoice->client->name }}</p>
                    <p class="text-sm text-blue-700">Total: ${{ number_format($payment->invoice->total, 2) }}</p>
                    @php
                        $otherPayments = $payment->invoice->payments()->where('id', '!=', $payment->id)->sum('amount');
                        $outstanding = $payment->invoice->total - $otherPayments;
                    @endphp
                    <p class="text-sm font-semibold text-blue-900 mt-2">
                        Available to allocate: ${{ number_format($outstanding, 2) }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" name="amount" id="amount" 
                               value="{{ old('amount', $payment->amount) }}" 
                               required min="0.01" step="0.01"
                               max="{{ $outstanding }}"
                               class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Max: ${{ number_format($outstanding, 2) }}</p>
                </div>

                <!-- Payment Date -->
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_date') border-red-500 @enderror">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                <select name="payment_method" id="payment_method" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_method') border-red-500 @enderror">
                    <option value="">Select payment method</option>
                    <option value="cash" {{ old('payment_method', $payment->payment_method) === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="credit_card" {{ old('payment_method', $payment->payment_method) === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="upi" {{ old('payment_method', $payment->payment_method) === 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="cheque" {{ old('payment_method', $payment->payment_method) === 'cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Reference / Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          placeholder="Transaction reference, cheque number, etc..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes', $payment->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('user.invoices.show', $payment->invoice) }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
