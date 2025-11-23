<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses
     * 
     * GET /api/expenses?category_id=1&date_from=2025-01-01
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Expense::where('user_id', $request->user()->id)
            ->with('category');

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Search by description
        if ($request->has('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $expenses = $query->latest('date')->paginate($request->per_page ?? 15);

        return response()->json($expenses);
    }

    /**
     * Store a newly created expense
     * 
     * POST /api/expenses
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $expense = $request->user()->expenses()->create($validated);
        $expense->load('category');

        return response()->json([
            'message' => 'Expense created successfully',
            'expense' => $expense,
        ], 201);
    }

    /**
     * Display the specified expense
     * 
     * GET /api/expenses/{id}
     * 
     * @param Request $request
     * @param Expense $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Expense $expense)
    {
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $expense->load('category');

        return response()->json($expense);
    }

    /**
     * Update the specified expense
     * 
     * PUT /api/expenses/{id}
     * 
     * @param Request $request
     * @param Expense $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $expense->update($validated);
        $expense->load('category');

        return response()->json([
            'message' => 'Expense updated successfully',
            'expense' => $expense,
        ]);
    }

    /**
     * Remove the specified expense
     * 
     * DELETE /api/expenses/{id}
     * 
     * @param Request $request
     * @param Expense $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Expense $expense)
    {
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $expense->delete();

        return response()->json([
            'message' => 'Expense deleted successfully',
        ]);
    }
}
