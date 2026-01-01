<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get statistics
        $totalUsers = User::count();
        $totalInvoices = Invoice::count();
        $totalClients = Client::count();
        $totalExpenses = Expense::sum('amount');
        
        // Calculate total revenue this month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthlyRevenue = Invoice::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'paid')
            ->sum('total');
        
        // Get pending invoices count
        $pendingInvoices = Invoice::whereIn('status', ['draft', 'sent'])->count();
        
        // Get paid invoices count
        $paidInvoices = Invoice::where('status', 'paid')->count();
        
        // Get recent users (last 5)
        $recentUsers = User::latest()->take(5)->get();
        
        // Get monthly revenue data for chart (last 6 months)
        $monthlyRevenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Invoice::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->where('status', 'paid')
                ->sum('total');
            
            $monthlyRevenueData[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }
        
        // Get monthly expense data for chart (last 6 months)
        $monthlyExpenseData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $expense = Expense::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');
            
            $monthlyExpenseData[] = [
                'month' => $month->format('M Y'),
                'expense' => $expense
            ];
        }
        
        // Get top 5 clients by total invoice amount
        $topClients = Client::select('clients.*', DB::raw('SUM(invoices.total) as total_amount'))
            ->leftJoin('invoices', 'clients.id', '=', 'invoices.client_id')
            ->where('invoices.status', 'paid')
            ->groupBy('clients.id')
            ->orderByDesc('total_amount')
            ->take(5)
            ->get();
        
        // Get recent activities (last 10)
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        // Get expense breakdown by category
        $expenseByCategory = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        
        // Get recent payments (last 5)
        $recentPayments = Payment::with(['invoice', 'invoice.client'])
            ->latest()
            ->take(5)
            ->get();
        
        // Calculate total payments this month
        $monthlyPayments = Payment::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('amount');
        
        // Get invoice status distribution
        $invoiceStatusData = [
            'paid' => $paidInvoices,
            'pending' => $pendingInvoices,
            'draft' => Invoice::where('status', 'draft')->count(),
            'sent' => Invoice::where('status', 'sent')->count(),
        ];
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalInvoices',
            'totalClients',
            'totalExpenses',
            'monthlyRevenue',
            'monthlyPayments',
            'pendingInvoices',
            'paidInvoices',
            'recentUsers',
            'monthlyRevenueData',
            'monthlyExpenseData',
            'topClients',
            'recentActivities',
            'expenseByCategory',
            'recentPayments',
            'invoiceStatusData'
        ));
    }
}
