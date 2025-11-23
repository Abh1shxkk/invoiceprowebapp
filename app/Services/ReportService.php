<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Client;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get revenue report for date range.
     */
    public function getRevenueReport($userId, $startDate = null, $endDate = null)
    {
        $query = Invoice::where('user_id', $userId);

        if ($startDate) {
            $query->whereDate('issue_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('issue_date', '<=', $endDate);
        }

        $invoices = $query->get();

        $totalRevenue = $invoices->where('status', 'paid')->sum('total');
        $totalPaidInvoices = $invoices->where('status', 'paid')->count();
        $totalPendingInvoices = $invoices->whereIn('status', ['sent', 'overdue', 'draft'])->count();
        $pendingAmount = $invoices->whereIn('status', ['sent', 'overdue', 'draft'])->sum('total');

        // Month-wise breakdown
        $monthlyData = $invoices->where('status', 'paid')
            ->groupBy(function($invoice) {
                return $invoice->issue_date->format('Y-m');
            })
            ->map(function($group) {
                return [
                    'month' => $group->first()->issue_date->format('M Y'),
                    'revenue' => $group->sum('total'),
                    'count' => $group->count(),
                ];
            });

        return [
            'total_revenue' => $totalRevenue,
            'total_paid_invoices' => $totalPaidInvoices,
            'total_pending_invoices' => $totalPendingInvoices,
            'pending_amount' => $pendingAmount,
            'monthly_data' => $monthlyData,
        ];
    }

    /**
     * Get expense report for date range.
     */
    public function getExpenseReport($userId, $startDate = null, $endDate = null)
    {
        $query = Expense::where('user_id', $userId)->with('category');

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        $expenses = $query->get();

        $totalExpenses = $expenses->sum('amount');

        // Category-wise breakdown
        $categoryData = $expenses->groupBy('category_id')
            ->map(function($group) {
                return [
                    'category' => $group->first()->category->name,
                    'total' => $group->sum('amount'),
                    'count' => $group->count(),
                ];
            });

        // Month-wise breakdown
        $monthlyData = $expenses->groupBy(function($expense) {
                return $expense->date->format('Y-m');
            })
            ->map(function($group) {
                return [
                    'month' => $group->first()->date->format('M Y'),
                    'expenses' => $group->sum('amount'),
                    'count' => $group->count(),
                ];
            });

        return [
            'total_expenses' => $totalExpenses,
            'category_data' => $categoryData,
            'monthly_data' => $monthlyData,
        ];
    }

    /**
     * Get profit/loss report.
     */
    public function getProfitLossReport($userId, $startDate = null, $endDate = null)
    {
        $revenueReport = $this->getRevenueReport($userId, $startDate, $endDate);
        $expenseReport = $this->getExpenseReport($userId, $startDate, $endDate);

        $profit = $revenueReport['total_revenue'] - $expenseReport['total_expenses'];
        $profitMargin = $revenueReport['total_revenue'] > 0 
            ? ($profit / $revenueReport['total_revenue']) * 100 
            : 0;

        // Monthly profit/loss
        $monthlyData = [];
        
        // Merge revenue and expense monthly data
        $allMonths = collect($revenueReport['monthly_data']->keys())
            ->merge($expenseReport['monthly_data']->keys())
            ->unique()
            ->sort();

        foreach ($allMonths as $month) {
            $revenue = $revenueReport['monthly_data'][$month]['revenue'] ?? 0;
            $expenses = $expenseReport['monthly_data'][$month]['expenses'] ?? 0;
            
            $monthlyData[$month] = [
                'month' => Carbon::createFromFormat('Y-m', $month)->format('M Y'),
                'revenue' => $revenue,
                'expenses' => $expenses,
                'profit' => $revenue - $expenses,
            ];
        }

        return [
            'total_revenue' => $revenueReport['total_revenue'],
            'total_expenses' => $expenseReport['total_expenses'],
            'profit' => $profit,
            'profit_margin' => $profitMargin,
            'monthly_data' => collect($monthlyData),
        ];
    }

    /**
     * Get client-wise report.
     */
    public function getClientReport($userId)
    {
        $clients = Client::where('user_id', $userId)
            ->with(['invoices' => function($query) {
                $query->with('payments');
            }])
            ->get();

        $clientData = $clients->map(function($client) {
            $totalBusiness = $client->invoices->sum('total');
            $totalPaid = $client->invoices->sum(function($invoice) {
                return $invoice->payments->sum('amount');
            });
            $outstanding = $totalBusiness - $totalPaid;

            return [
                'client' => $client,
                'total_business' => $totalBusiness,
                'total_invoices' => $client->invoices->count(),
                'total_paid' => $totalPaid,
                'outstanding' => $outstanding,
            ];
        })->sortByDesc('total_business');

        return $clientData;
    }

    /**
     * Get chart data for revenue vs expenses.
     */
    public function getRevenueVsExpensesChartData($userId, $months = 6)
    {
        $startDate = Carbon::now()->subMonths($months)->startOfMonth();
        
        $labels = [];
        $revenueData = [];
        $expenseData = [];

        for ($i = 0; $i < $months; $i++) {
            $month = Carbon::now()->subMonths($months - $i - 1);
            $labels[] = $month->format('M Y');

            // Get revenue for this month
            $revenue = Invoice::where('user_id', $userId)
                ->where('status', 'paid')
                ->whereYear('issue_date', $month->year)
                ->whereMonth('issue_date', $month->month)
                ->sum('total');

            // Get expenses for this month
            $expenses = Expense::where('user_id', $userId)
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum('amount');

            $revenueData[] = $revenue;
            $expenseData[] = $expenses;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'expenses' => $expenseData,
        ];
    }

    /**
     * Get chart data for revenue by client.
     */
    public function getRevenueByClientChartData($userId, $limit = 10)
    {
        $clients = Client::where('user_id', $userId)
            ->with(['invoices' => function($query) {
                $query->where('status', 'paid');
            }])
            ->get()
            ->map(function($client) {
                return [
                    'name' => $client->name,
                    'revenue' => $client->invoices->sum('total'),
                ];
            })
            ->sortByDesc('revenue')
            ->take($limit);

        return [
            'labels' => $clients->pluck('name')->toArray(),
            'data' => $clients->pluck('revenue')->toArray(),
        ];
    }
}
