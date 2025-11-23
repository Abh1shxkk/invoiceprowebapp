<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Total clients for this user
        $totalClients = Client::where('user_id', $user->id)->count();
        
        // Total invoices this month for this user
        $invoicesThisMonth = Invoice::where('user_id', $user->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        
        // Pending invoices amount (draft + sent)
        $pendingInvoicesAmount = Invoice::where('user_id', $user->id)
            ->whereIn('status', ['draft', 'sent'])
            ->sum('total');
        
        // Total expenses this month
        $expensesThisMonth = Expense::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');
        
        // Revenue this month (paid invoices)
        $revenueThisMonth = Invoice::where('user_id', $user->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'paid')
            ->sum('total');
        
        // Recent invoices (last 10)
        $recentInvoices = Invoice::where('user_id', $user->id)
            ->with('client')
            ->latest()
            ->take(10)
            ->get();
        
        // Total invoices count
        $totalInvoices = Invoice::where('user_id', $user->id)->count();
        
        // Total revenue (all time)
        $totalRevenue = Invoice::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('total');
        
        return view('user.dashboard', compact(
            'totalClients',
            'invoicesThisMonth',
            'pendingInvoicesAmount',
            'expensesThisMonth',
            'revenueThisMonth',
            'recentInvoices',
            'totalInvoices',
            'totalRevenue'
        ));
    }
}
