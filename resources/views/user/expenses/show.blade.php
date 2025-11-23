@extends('layouts.user')

@section('page-title', 'Expense Details')

@section('content')
<div class="max-w-2xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('user.expenses.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Expenses
        </a>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3 mb-6">
        <a href="{{ route('user.expenses.edit', $expense) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </a>
        <form action="{{ route('user.expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense?');">
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

    <!-- Expense Details Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Expense Details</h3>
        </div>

        <div class="p-6 space-y-6">
            <!-- Amount -->
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Amount</p>
                <p class="text-3xl font-bold text-gray-900">${{ number_format($expense->amount, 2) }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date -->
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Date</p>
                    <p class="text-base text-gray-900">{{ $expense->date->format('M d, Y') }}</p>
                </div>

                <!-- Category -->
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Category</p>
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                        {{ $expense->category->name }}
                    </span>
                </div>
            </div>

            <!-- Description -->
            @if($expense->description)
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Description</p>
                    <p class="text-base text-gray-900 whitespace-pre-line">{{ $expense->description }}</p>
                </div>
            @endif

            <!-- Receipt -->
            @if($expense->receipt_path)
                <div class="pt-6 border-t border-gray-200">
                    <p class="text-sm font-medium text-gray-500 mb-3">Receipt</p>
                    <div class="flex items-center space-x-3">
                        <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            View Receipt
                        </a>
                    </div>
                </div>
            @endif

            <!-- Metadata -->
            <div class="pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Created</p>
                        <p class="text-gray-900">{{ $expense->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Last Updated</p>
                        <p class="text-gray-900">{{ $expense->updated_at->format('M d, Y g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
