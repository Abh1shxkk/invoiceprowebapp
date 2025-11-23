@extends('layouts.user')

@section('page-title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
        <p class="text-sm text-gray-600 mt-1">Manage your account and business settings</p>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button onclick="switchTab('company')" id="tab-company" class="tab-button active border-b-2 border-blue-500 py-4 px-6 text-sm font-medium text-blue-600">
                    Company Settings
                </button>
                <button onclick="switchTab('invoice')" id="tab-invoice" class="tab-button border-b-2 border-transparent py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Invoice Settings
                </button>
                <button onclick="switchTab('profile')" id="tab-profile" class="tab-button border-b-2 border-transparent py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    User Profile
                </button>
                <button onclick="switchTab('password')" id="tab-password" class="tab-button border-b-2 border-transparent py-4 px-6 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Change Password
                </button>
            </nav>
        </div>

        <!-- Company Settings Tab -->
        <div id="content-company" class="tab-content p-6">
            <form action="{{ route('user.settings.company.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <h4 class="text-base font-semibold text-gray-900 mb-4">Company Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Company Name -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $settings->company_name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company_name') border-red-500 @enderror">
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax ID -->
                        <div>
                            <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-2">Tax ID / Business Number</label>
                            <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $settings->tax_id) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tax_id') border-red-500 @enderror">
                            @error('tax_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Logo -->
                    <div class="mt-6">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Company Logo</label>
                        @if($settings->logo)
                            <div class="mb-3">
                                <img src="{{ Storage::url($settings->logo) }}" alt="Company Logo" class="h-20 rounded border border-gray-200">
                            </div>
                        @endif
                        <input type="file" name="logo" id="logo" accept=".jpg,.jpeg,.png"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('logo') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, PNG (Max: 2MB)</p>
                        @error('logo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $settings->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $settings->phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="company_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="company_email" value="{{ old('email', $settings->email) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website', $settings->website) }}"
                                   placeholder="https://example.com"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('website') border-red-500 @enderror">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                        Save Company Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Invoice Settings Tab -->
        <div id="content-invoice" class="tab-content hidden p-6">
            <form action="{{ route('user.settings.invoice.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <h4 class="text-base font-semibold text-gray-900 mb-4">Invoice Configuration</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Invoice Prefix -->
                        <div>
                            <label for="invoice_prefix" class="block text-sm font-medium text-gray-700 mb-2">Invoice Prefix *</label>
                            <input type="text" name="invoice_prefix" id="invoice_prefix" value="{{ old('invoice_prefix', $settings->invoice_prefix) }}" required
                                   placeholder="INV"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('invoice_prefix') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Example: INV-00001</p>
                            @error('invoice_prefix')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Starting Number -->
                        <div>
                            <label for="invoice_start_number" class="block text-sm font-medium text-gray-700 mb-2">Starting Number *</label>
                            <input type="number" name="invoice_start_number" id="invoice_start_number" value="{{ old('invoice_start_number', $settings->invoice_start_number) }}" required min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('invoice_start_number') border-red-500 @enderror">
                            @error('invoice_start_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Default Tax Rate -->
                        <div>
                            <label for="default_tax_rate" class="block text-sm font-medium text-gray-700 mb-2">Default Tax Rate (%) *</label>
                            <input type="number" name="default_tax_rate" id="default_tax_rate" value="{{ old('default_tax_rate', $settings->default_tax_rate) }}" required min="0" max="100" step="0.01"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('default_tax_rate') border-red-500 @enderror">
                            @error('default_tax_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Terms -->
                    <div class="mt-6">
                        <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">Payment Terms</label>
                        <textarea name="payment_terms" id="payment_terms" rows="3"
                                  placeholder="e.g., Payment due within 30 days"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_terms') border-red-500 @enderror">{{ old('payment_terms', $settings->payment_terms) }}</textarea>
                        @error('payment_terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Invoice Footer -->
                    <div class="mt-6">
                        <label for="invoice_footer" class="block text-sm font-medium text-gray-700 mb-2">Invoice Footer Text</label>
                        <textarea name="invoice_footer" id="invoice_footer" rows="3"
                                  placeholder="Thank you for your business!"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('invoice_footer') border-red-500 @enderror">{{ old('invoice_footer', $settings->invoice_footer) }}</textarea>
                        @error('invoice_footer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                        Save Invoice Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- User Profile Tab -->
        <div id="content-profile" class="tab-content hidden p-6">
            <form action="{{ route('user.settings.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <h4 class="text-base font-semibold text-gray-900 mb-4">Personal Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="user_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="user_email" value="{{ old('email', auth()->user()->email) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="user_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" id="user_phone" value="{{ old('phone', auth()->user()->phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Profile Picture -->
                    <div class="mt-6">
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                        @if(auth()->user()->profile_picture)
                            <div class="mb-3">
                                <img src="{{ Storage::url(auth()->user()->profile_picture) }}" alt="Profile" class="h-20 w-20 rounded-full object-cover border border-gray-200">
                            </div>
                        @endif
                        <input type="file" name="profile_picture" id="profile_picture" accept=".jpg,.jpeg,.png"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('profile_picture') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, PNG (Max: 2MB)</p>
                        @error('profile_picture')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                        Save Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Tab -->
        <div id="content-password" class="tab-content hidden p-6">
            <form action="{{ route('user.settings.password.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <h4 class="text-base font-semibold text-gray-900 mb-4">Change Password</h4>
                    
                    <div class="max-w-md space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                            <input type="password" name="current_password" id="current_password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                            <input type="password" name="password" id="password" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endsection
