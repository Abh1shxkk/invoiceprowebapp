@extends('layouts.user')

@section('page-title', 'Add Category')

@section('content')
<div class="max-w-2xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('user.categories.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Categories
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Add New Category</h3>
            <p class="text-sm text-gray-600 mt-1">Create a new expense or income category</p>
        </div>

        <form action="{{ route('user.categories.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       placeholder="e.g., Office Supplies"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                <select name="type" id="type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                    <option value="">Select type</option>
                    <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                    <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>Income</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('user.categories.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
