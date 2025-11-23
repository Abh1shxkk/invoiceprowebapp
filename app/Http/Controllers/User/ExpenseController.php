<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses with filters.
     */
    public function index(Request $request)
    {
        $query = Expense::where('user_id', auth()->id())
            ->with('category');

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Search by description
        if ($request->has('search') && $request->search != '') {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $expenses = $query->latest('date')->paginate(15);

        // Calculate total
        $total = $query->sum('amount');

        // Get categories for filter dropdown
        $categories = Category::where('type', 'expense')->orderBy('name')->get();

        // Monthly summary (this month)
        $monthlyExpenses = Expense::where('user_id', auth()->id())
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($group) {
                return [
                    'category' => $group->first()->category->name,
                    'total' => $group->sum('amount'),
                    'count' => $group->count(),
                ];
            });

        return view('user.expenses.index', compact('expenses', 'total', 'categories', 'monthlyExpenses'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        $categories = Category::where('type', 'expense')->orderBy('name')->get();
        
        return view('user.expenses.create', compact('categories'));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $validated['user_id'] = auth()->id();

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt_path'] = $path;
        }

        Expense::create($validated);

        return redirect()->route('user.expenses.index')
            ->with('success', 'Expense added successfully.');
    }

    /**
     * Display the specified expense.
     */
    public function show(Expense $expense)
    {
        // Ensure user can only view their own expenses
        if ($expense->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $expense->load('category');

        return view('user.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense)
    {
        // Ensure user can only edit their own expenses
        if ($expense->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::where('type', 'expense')->orderBy('name')->get();

        return view('user.expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        // Ensure user can only update their own expenses
        if ($expense->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // Handle receipt upload
        if ($request->hasFile('receipt')) {
            // Delete old receipt if exists
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }

            $path = $request->file('receipt')->store('receipts', 'public');
            $validated['receipt_path'] = $path;
        }

        $expense->update($validated);

        return redirect()->route('user.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense)
    {
        // Ensure user can only delete their own expenses
        if ($expense->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete receipt file if exists
        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->route('user.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
