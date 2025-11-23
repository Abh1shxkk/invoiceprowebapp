@extends('layouts.user')

@section('page-title', 'Create Invoice')

@section('content')
<div class="max-w-5xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('user.invoices.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Invoices
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Create New Invoice</h3>
            <p class="text-sm text-gray-600 mt-1">Fill in the invoice details and add line items</p>
        </div>

        <form action="{{ route('user.invoices.store') }}" method="POST" id="invoiceForm" class="p-6 space-y-6">
            @csrf

            <!-- Invoice Header -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Client Selection -->
                <div class="md:col-span-2">
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client *</label>
                    <select name="client_id" id="client_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('client_id') border-red-500 @enderror">
                        <option value="">Select a client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}{{ $client->company ? ' - ' . $client->company : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($clients->count() === 0)
                        <p class="mt-2 text-sm text-gray-600">
                            No clients found. <a href="{{ route('user.clients.create') }}" class="text-blue-600 hover:text-blue-700">Create a client first</a>
                        </p>
                    @endif
                </div>

                <!-- Invoice Number -->
                <div>
                    <label for="invoice_number" class="block text-sm font-medium text-gray-700 mb-2">Invoice Number *</label>
                    <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $invoiceNumber) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('invoice_number') border-red-500 @enderror">
                    @error('invoice_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ old('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Issue Date -->
                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">Issue Date *</label>
                    <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('issue_date') border-red-500 @enderror">
                    @error('issue_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-semibold text-gray-900">Invoice Items</h4>
                    <button type="button" id="addItemBtn" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-700">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Item
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="itemsTable">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-24">Quantity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase w-32">Total</th>
                                <th class="px-4 py-2 w-12"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <!-- Items will be added here dynamically -->
                        </tbody>
                    </table>
                </div>

                @error('items')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Totals Section -->
            <div class="pt-6 border-t border-gray-200">
                <div class="flex justify-end">
                    <div class="w-full md:w-1/2 space-y-3">
                        <!-- Subtotal -->
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                            <span class="text-base font-semibold text-gray-900" id="subtotalDisplay">$0.00</span>
                        </div>

                        <!-- Tax -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <label for="tax" class="text-sm font-medium text-gray-700">Tax Rate (%):</label>
                                <input type="number" name="tax" id="tax" value="{{ old('tax', '0') }}" min="0" max="100" step="0.01"
                                       class="w-20 px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('tax') border-red-500 @enderror">
                            </div>
                            <span class="text-base font-semibold text-gray-900" id="taxDisplay">$0.00</span>
                        </div>
                        @error('tax')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Grand Total -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <span class="text-base font-semibold text-gray-900">Total:</span>
                            <span class="text-xl font-bold text-blue-600" id="totalDisplay">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="pt-6 border-t border-gray-200">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('user.invoices.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Create Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Dynamic Items and Calculations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemCount = 0;

    // Add first item on load
    addItem();

    // Add Item Button
    document.getElementById('addItemBtn').addEventListener('click', function() {
        addItem();
    });

    // Tax input change
    document.getElementById('tax').addEventListener('input', calculateTotals);

    function addItem() {
        itemCount++;
        const tbody = document.getElementById('itemsBody');
        const row = document.createElement('tr');
        row.className = 'border-b border-gray-200';
        row.innerHTML = `
            <td class="px-4 py-2">
                <input type="text" name="items[${itemCount}][description]" required
                       placeholder="Item description"
                       class="w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </td>
            <td class="px-4 py-2">
                <input type="number" name="items[${itemCount}][quantity]" required
                       min="0.01" step="0.01" value="1"
                       class="item-quantity w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </td>
            <td class="px-4 py-2">
                <input type="number" name="items[${itemCount}][price]" required
                       min="0" step="0.01" value="0"
                       class="item-price w-full px-2 py-1.5 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </td>
            <td class="px-4 py-2">
                <span class="item-total text-sm font-semibold text-gray-900">$0.00</span>
            </td>
            <td class="px-4 py-2 text-center">
                <button type="button" class="remove-item text-red-600 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </td>
        `;

        tbody.appendChild(row);

        // Add event listeners for this row
        const quantityInput = row.querySelector('.item-quantity');
        const priceInput = row.querySelector('.item-price');
        const removeBtn = row.querySelector('.remove-item');

        quantityInput.addEventListener('input', function() {
            updateRowTotal(row);
        });

        priceInput.addEventListener('input', function() {
            updateRowTotal(row);
        });

        removeBtn.addEventListener('click', function() {
            if (tbody.children.length > 1) {
                row.remove();
                calculateTotals();
            } else {
                alert('At least one item is required');
            }
        });

        calculateTotals();
    }

    function updateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const total = quantity * price;
        
        row.querySelector('.item-total').textContent = '$' + total.toFixed(2);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        
        // Calculate subtotal from all items
        document.querySelectorAll('#itemsBody tr').forEach(function(row) {
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            subtotal += quantity * price;
        });

        // Get tax rate
        const taxRate = parseFloat(document.getElementById('tax').value) || 0;
        const taxAmount = (subtotal * taxRate) / 100;
        const total = subtotal + taxAmount;

        // Update displays
        document.getElementById('subtotalDisplay').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('taxDisplay').textContent = '$' + taxAmount.toFixed(2);
        document.getElementById('totalDisplay').textContent = '$' + total.toFixed(2);
    }
});
</script>
@endsection
