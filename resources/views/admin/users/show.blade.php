@extends('layouts.admin')

@section('page-title', 'User Details')

@section('content')
<div class="max-w-4xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Users
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <div class="w-24 h-24 rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl font-bold mx-auto">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $user->email }}</p>
                    <span class="inline-block mt-3 px-3 py-1 text-xs font-medium rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Joined</span>
                        <span class="font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Email Verified</span>
                        <span class="font-medium text-gray-900">
                            @if($user->email_verified_at)
                                <span class="text-green-600">✓ Verified</span>
                            @else
                                <span class="text-red-600">Not Verified</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">
                        Edit User
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                                Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Clients</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $user->clients->count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Invoices</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $user->invoices->count() }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Expenses</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $user->expenses->count() }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Invoices</h3>
                </div>
                <div class="p-6">
                    @if($user->invoices->count() > 0)
                        <div class="space-y-3">
                            @foreach($user->invoices->take(5) as $invoice)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $invoice->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900">${{ number_format($invoice->total, 2) }}</p>
                                        <span class="inline-block mt-1 px-2 py-1 text-xs font-medium rounded-full
                                            {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $invoice->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-8">No invoices yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
