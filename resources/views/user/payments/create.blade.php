@extends('layouts.user')

@section('page-title', 'Record Payment')

@section('content')
<div class="max-w-2xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ $invoice ? route('user.invoices.show', $invoice) : route('user.payments.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Record Payment</h3>
            <p class="text-sm text-gray-600 mt-1">Record a payment against an invoice</p>
        </div>

        <form action="{{ route('user.payments.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Invoice Selection -->
            @if($invoice)
                <!-- Pre-selected invoice -->
                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-900">Invoice: {{ $invoice->invoice_number }}</p>
                            <p class="text-sm text-blue-700 mt-1">Client: {{ $invoice->client->name }}</p>
                            <p class="text-sm text-blue-700">Total: ${{ number_format($invoice->total, 2) }}</p>
                            <p class="text-sm text-blue-700">Paid: ${{ number_format($invoice->payments->sum('amount'), 2) }}</p>
                            <p class="text-sm font-semibold text-blue-900 mt-2">
                                Outstanding: ${{ number_format($invoice->total - $invoice->payments->sum('amount'), 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Invoice dropdown -->
                <div>
                    <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-2">Select Invoice *</label>
                    <select name="invoice_id" id="invoice_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('invoice_id') border-red-500 @enderror"
                            onchange="updateOutstanding(this)">
                        <option value="">Select an invoice</option>
                        @foreach($invoices as $inv)
                            @php
                                $outstanding = $inv->total - $inv->payments->sum('amount');
                            @endphp
                            <option value="{{ $inv->id }}" 
                                    data-total="{{ $inv->total }}" 
                                    data-paid="{{ $inv->payments->sum('amount') }}"
                                    data-outstanding="{{ $outstanding }}"
                                    {{ old('invoice_id') == $inv->id ? 'selected' : '' }}>
                                {{ $inv->invoice_number }} - {{ $inv->client->name }} (Outstanding: ${{ number_format($outstanding, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="invoice-details" class="mt-3 p-3 bg-gray-50 rounded-lg hidden">
                        <p class="text-sm text-gray-700">Total: $<span id="invoice-total">0.00</span></p>
                        <p class="text-sm text-gray-700">Paid: $<span id="invoice-paid">0.00</span></p>
                        <p class="text-sm font-semibold text-gray-900">Outstanding: $<span id="invoice-outstanding">0.00</span></p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" name="amount" id="amount" 
                               value="{{ old('amount', $invoice ? $invoice->total - $invoice->payments->sum('amount') : '') }}" 
                               required min="0.01" step="0.01"
                               max="{{ $invoice ? $invoice->total - $invoice->payments->sum('amount') : '' }}"
                               class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($invoice)
                        <p class="mt-1 text-xs text-gray-500">Max: ${{ number_format($invoice->total - $invoice->payments->sum('amount'), 2) }}</p>
                    @endif
                </div>

                <!-- Payment Date -->
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Payment Date *</label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required
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
                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="upi" {{ old('payment_method') === 'upi' ? 'selected' : '' }}>UPI</option>
                    <option value="cheque" {{ old('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
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
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ $invoice ? route('user.invoices.show', $invoice) : route('user.payments.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>

@if(!$invoice)
<script>
function updateOutstanding(select) {
    const option = select.options[select.selectedIndex];
    const details = document.getElementById('invoice-details');
    
    if (option.value) {
        const total = parseFloat(option.dataset.total);
        const paid = parseFloat(option.dataset.paid);
        const outstanding = parseFloat(option.dataset.outstanding);
        
        document.getElementById('invoice-total').textContent = total.toFixed(2);
        document.getElementById('invoice-paid').textContent = paid.toFixed(2);
        document.getElementById('invoice-outstanding').textContent = outstanding.toFixed(2);
        
        // Update amount field max and value
        const amountField = document.getElementById('amount');
        amountField.max = outstanding;
        amountField.value = outstanding.toFixed(2);
        
        details.classList.remove('hidden');
    } else {
        details.classList.add('hidden');
        document.getElementById('amount').value = '';
        document.getElementById('amount').max = '';
    }
}
</script>
@endif
@endsection
