<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
        
        // Calculate total revenue this month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthlyRevenue = Invoice::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'paid')
            ->sum('total');
        
        // Get pending invoices count
        $pendingInvoices = Invoice::whereIn('status', ['draft', 'sent'])->count();
        
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
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalInvoices',
            'monthlyRevenue',
            'pendingInvoices',
            'recentUsers',
            'monthlyRevenueData'
        ));
    }
}
