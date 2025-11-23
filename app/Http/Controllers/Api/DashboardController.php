<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     * 
     * GET /api/dashboard/stats
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        // Total clients
        $totalClients = Client::where('user_id', $user->id)->count();

        // Total invoices
        $totalInvoices = Invoice::where('user_id', $user->id)->count();

        // Invoice status counts
        $paidInvoices = Invoice::where('user_id', $user->id)
            ->where('status', 'paid')
            ->count();

        $pendingInvoices = Invoice::where('user_id', $user->id)
            ->whereIn('status', ['sent', 'draft'])
            ->count();

        $overdueInvoices = Invoice::where('user_id', $user->id)
            ->where('status', 'overdue')
            ->count();

        // Revenue (paid invoices)
        $totalRevenue = Invoice::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('total');

        // Pending amount
        $pendingAmount = Invoice::where('user_id', $user->id)
            ->whereIn('status', ['sent', 'overdue', 'draft'])
            ->sum('total');

        // Total expenses
        $totalExpenses = Expense::where('user_id', $user->id)->sum('amount');

        // Profit
        $profit = $totalRevenue - $totalExpenses;

        // This month revenue
        $thisMonthRevenue = Invoice::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereMonth('issue_date', Carbon::now()->month)
            ->whereYear('issue_date', Carbon::now()->year)
            ->sum('total');

        // This month expenses
        $thisMonthExpenses = Expense::where('user_id', $user->id)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        // Recent invoices
        $recentInvoices = Invoice::where('user_id', $user->id)
            ->with('client')
            ->latest()
            ->limit(5)
            ->get();

        // Recent payments
        $recentPayments = Payment::whereHas('invoice', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with('invoice.client')
            ->latest('payment_date')
            ->limit(5)
            ->get();

        return response()->json([
            'overview' => [
                'total_clients' => $totalClients,
                'total_invoices' => $totalInvoices,
                'paid_invoices' => $paidInvoices,
                'pending_invoices' => $pendingInvoices,
                'overdue_invoices' => $overdueInvoices,
            ],
            'financial' => [
                'total_revenue' => $totalRevenue,
                'pending_amount' => $pendingAmount,
                'total_expenses' => $totalExpenses,
                'profit' => $profit,
                'this_month_revenue' => $thisMonthRevenue,
                'this_month_expenses' => $thisMonthExpenses,
            ],
            'recent_invoices' => $recentInvoices,
            'recent_payments' => $recentPayments,
        ]);
    }
}
