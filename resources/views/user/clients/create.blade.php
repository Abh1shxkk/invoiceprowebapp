@extends('layouts.user')

@section('page-title', 'Add New Client')

@section('content')
<div class="max-w-3xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('user.clients.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Clients
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Client Information</h3>
            <p class="text-sm text-gray-600 mt-1">Add a new client to your database</p>
        </div>

        <form action="{{ route('user.clients.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Basic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company -->
                    <div class="md:col-span-2">
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                        <input type="text" name="company" id="company" value="{{ old('company') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company') border-red-500 @enderror">
                        @error('company')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Contact Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Address Information</h4>
                <div class="space-y-4">
                    <!-- Street Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                        <textarea name="address" id="address" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- City -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('city') border-red-500 @enderror">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                            <input type="text" name="state" id="state" value="{{ old('state') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('state') border-red-500 @enderror">
                            @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ZIP -->
                        <div>
                            <label for="zip" class="block text-sm font-medium text-gray-700 mb-2">ZIP/Postal Code</label>
                            <input type="text" name="zip" id="zip" value="{{ old('zip') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('zip') border-red-500 @enderror">
                            @error('zip')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('country') border-red-500 @enderror">
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tax Information -->
            <div class="pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Tax Information</h4>
                <div>
                    <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">Tax ID / VAT Number</label>
                    <input type="text" name="tax_number" id="tax_number" value="{{ old('tax_number') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tax_number') border-red-500 @enderror">
                    @error('tax_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('user.clients.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Create Client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
