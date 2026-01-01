@extends('layouts.admin')

@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* Modern Minimal Dashboard Styles */
    .dashboard-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--card-gradient-start), var(--card-gradient-end));
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-card.blue {
        --card-gradient-start: #4F46E5;
        --card-gradient-end: #7C3AED;
    }

    .stat-card.green {
        --card-gradient-start: #059669;
        --card-gradient-end: #10B981;
    }

    .stat-card.purple {
        --card-gradient-start: #7C3AED;
        --card-gradient-end: #A855F7;
    }

    .stat-card.orange {
        --card-gradient-start: #EA580C;
        --card-gradient-end: #F59E0B;
    }

    .stat-card.teal {
        --card-gradient-start: #0D9488;
        --card-gradient-end: #14B8A6;
    }

    .stat-card.rose {
        --card-gradient-start: #E11D48;
        --card-gradient-end: #F43F5E;
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--card-gradient-start), var(--card-gradient-end));
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: rotate(10deg) scale(1.1);
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--card-gradient-start), var(--card-gradient-end));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-top: 1rem;
        animation: countUp 1s ease-out;
    }

    @keyframes countUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .stat-change {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        margin-top: 0.5rem;
        display: inline-block;
    }

    .stat-change.positive {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .chart-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        height: 100%;
        border: 1px solid rgba(255, 255, 255, 0.18);
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #F1F5F9;
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1E293B;
    }

    .chart-subtitle {
        font-size: 0.875rem;
        color: #94A3B8;
        margin-top: 0.25rem;
    }

    .table-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .table-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        border-bottom: 2px solid #E2E8F0;
    }

    .activity-item {
        padding: 1rem 1.5rem;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
        position: relative;
    }

    .activity-item:hover {
        background: #F8FAFC;
        border-left-color: #7C3AED;
        transform: translateX(4px);
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .client-row {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .client-row:hover {
        background: #F8FAFC;
        border-left-color: #10B981;
        transform: translateX(4px);
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-block;
    }

    .badge-admin {
        background: linear-gradient(135deg, #7C3AED 0%, #A855F7 100%);
        color: white;
    }

    .badge-user {
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        color: white;
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        height: 320px;
    }

    .chart-container-small {
        position: relative;
        height: 280px;
    }

    /* Loading Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Progress Bar */
    .progress-bar {
        height: 8px;
        background: #E2E8F0;
        border-radius: 999px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--card-gradient-start), var(--card-gradient-end));
        border-radius: 999px;
        transition: width 1s ease-out;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .stat-value {
            font-size: 1.75rem;
        }
        
        .chart-container {
            height: 250px;
        }

        .chart-container-small {
            height: 220px;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 fade-in-up">
        <!-- Total Users Card -->
        <div class="stat-card blue">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="stat-label">Users</p>
                    <p class="stat-value">{{ $totalUsers }}</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Clients Card -->
        <div class="stat-card green">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="stat-label">Clients</p>
                    <p class="stat-value">{{ $totalClients }}</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Invoices Card -->
        <div class="stat-card purple">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="stat-label">Invoices</p>
                    <p class="stat-value">{{ $totalInvoices }}</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Card -->
        <div class="stat-card orange">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="stat-label">Revenue</p>
                    <p class="stat-value">${{ number_format($monthlyRevenue, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">This Month</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Expenses Card -->
        <div class="stat-card teal">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="stat-label">Expenses</p>
                    <p class="stat-value">${{ number_format($totalExpenses, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Invoices Card -->
        <div class="stat-card rose">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="stat-label">Pending</p>
                    <p class="stat-value">{{ $pendingInvoices }}</p>
                    <p class="text-xs text-gray-500 mt-1">Invoices</p>
                </div>
                <div class="stat-icon">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-in-up" style="animation-delay: 0.1s;">
        <!-- Revenue vs Expenses Chart (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Revenue vs Expenses</h3>
                        <p class="chart-subtitle">Last 6 months comparison</p>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueExpenseChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Invoice Status Distribution (1/3 width) -->
        <div class="lg:col-span-1">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Invoice Status</h3>
                        <p class="chart-subtitle">Current distribution</p>
                    </div>
                </div>
                <div class="chart-container-small">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Breakdown & Top Clients -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 fade-in-up" style="animation-delay: 0.2s;">
        <!-- Expense Breakdown by Category -->
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Expense Breakdown</h3>
                    <p class="chart-subtitle">By category</p>
                </div>
            </div>
            <div class="chart-container-small">
                <canvas id="expenseChart"></canvas>
            </div>
        </div>

        <!-- Top Clients -->
        <div class="table-card">
            <div class="table-header">
                <h3 class="text-lg font-bold text-gray-900">Top Clients</h3>
                <p class="text-sm text-gray-500 mt-1">By total revenue</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($topClients as $index => $client)
                    <div class="client-row p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $client->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $client->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">${{ number_format($client->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="mt-2">No clients found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activities & Recent Payments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 fade-in-up" style="animation-delay: 0.3s;">
        <!-- Recent Activities -->
        <div class="table-card">
            <div class="table-header">
                <h3 class="text-lg font-bold text-gray-900">Recent Activities</h3>
                <p class="text-sm text-gray-500 mt-1">Latest system events</p>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="flex items-start space-x-3">
                            <div class="activity-icon bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $activity->user ? $activity->user->name : 'System' }} • {{ $activity->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <p class="mt-2">No recent activities</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="table-card">
            <div class="table-header">
                <h3 class="text-lg font-bold text-gray-900">Recent Payments</h3>
                <p class="text-sm text-gray-500 mt-1">Latest transactions</p>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentPayments as $payment)
                    <div class="activity-item">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="activity-icon bg-gradient-to-br from-green-500 to-teal-600 text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $payment->invoice && $payment->invoice->client ? $payment->invoice->client->name : 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $payment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">${{ number_format($payment->amount, 2) }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($payment->method ?? 'N/A') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="mt-2">No recent payments</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart.js default settings
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748B';

    // Revenue vs Expenses Chart
    const revenueExpenseCtx = document.getElementById('revenueExpenseChart').getContext('2d');
    const revenueData = @json($monthlyRevenueData);
    const expenseData = @json($monthlyExpenseData);
    
    const revenueExpenseChart = new Chart(revenueExpenseCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.month),
            datasets: [
                {
                    label: 'Revenue',
                    data: revenueData.map(item => item.revenue),
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                },
                {
                    label: 'Expenses',
                    data: expenseData.map(item => item.expense),
                    borderColor: '#F59E0B',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#F59E0B',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 16,
                    borderRadius: 12,
                    titleFont: {
                        size: 15,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 14
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': $' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        },
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Invoice Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($invoiceStatusData);
    
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Paid', 'Pending', 'Draft', 'Sent'],
            datasets: [{
                data: [statusData.paid, statusData.pending, statusData.draft, statusData.sent],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(148, 163, 184, 0.8)',
                    'rgba(59, 130, 246, 0.8)'
                ],
                borderColor: [
                    '#10B981',
                    '#F59E0B',
                    '#94A3B8',
                    '#3B82F6'
                ],
                borderWidth: 3,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 16,
                    borderRadius: 12,
                    titleFont: {
                        size: 15,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 14
                    },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Expense Breakdown Chart
    const expenseCtx = document.getElementById('expenseChart').getContext('2d');
    const expenseCategoryData = @json($expenseByCategory);
    
    const expenseChart = new Chart(expenseCtx, {
        type: 'bar',
        data: {
            labels: expenseCategoryData.map(item => item.category ? item.category.name : 'Uncategorized'),
            datasets: [{
                label: 'Expenses',
                data: expenseCategoryData.map(item => item.total),
                backgroundColor: [
                    'rgba(124, 58, 237, 0.8)',
                    'rgba(14, 165, 233, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    '#7C3AED',
                    '#0EA5E9',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444'
                ],
                borderWidth: 2,
                borderRadius: 8,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 16,
                    borderRadius: 12,
                    titleFont: {
                        size: 15,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 14
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Amount: $' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        },
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
