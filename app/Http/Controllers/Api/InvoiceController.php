<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices
     * 
     * GET /api/invoices?status=paid&search=INV-001
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Invoice::where('user_id', $request->user()->id)
            ->with('client', 'items');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json($invoices);
    }

    /**
     * Store a newly created invoice
     * 
     * POST /api/invoices
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'invoice_number' => ['required', 'string', 'unique:invoices,invoice_number'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'tax' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,sent,paid,overdue,cancelled'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $taxAmount = ($subtotal * $validated['tax']) / 100;
        $total = $subtotal + $taxAmount;

        DB::beginTransaction();
        try {
            $invoice = $request->user()->invoices()->create([
                'client_id' => $validated['client_id'],
                'invoice_number' => $validated['invoice_number'],
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'tax' => $validated['tax'],
                'total' => $total,
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();

            $invoice->load('client', 'items');

            return response()->json([
                'message' => 'Invoice created successfully',
                'invoice' => $invoice,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified invoice
     * 
     * GET /api/invoices/{id}
     * 
     * @param Request $request
     * @param Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Invoice $invoice)
    {
        if ($invoice->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $invoice->load('client', 'items', 'payments');

        return response()->json($invoice);
    }

    /**
     * Update the specified invoice
     * 
     * PUT /api/invoices/{id}
     * 
     * @param Request $request
     * @param Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'tax' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,sent,paid,overdue,cancelled'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $taxAmount = ($subtotal * $validated['tax']) / 100;
        $total = $subtotal + $taxAmount;

        DB::beginTransaction();
        try {
            $invoice->update([
                'client_id' => $validated['client_id'],
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'tax' => $validated['tax'],
                'total' => $total,
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            // Delete old items and create new ones
            $invoice->items()->delete();

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();

            $invoice->load('client', 'items');

            return response()->json([
                'message' => 'Invoice updated successfully',
                'invoice' => $invoice,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified invoice
     * 
     * DELETE /api/invoices/{id}
     * 
     * @param Request $request
     * @param Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Invoice $invoice)
    {
        if ($invoice->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $invoice->delete();

        return response()->json([
            'message' => 'Invoice deleted successfully',
        ]);
    }

    /**
     * Download invoice PDF
     * 
     * GET /api/invoices/{id}/pdf
     * 
     * @param Request $request
     * @param Invoice $invoice
     * @param PdfService $pdfService
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(Request $request, Invoice $invoice, PdfService $pdfService)
    {
        if ($invoice->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pdf = $pdfService->generateInvoicePdf($invoice);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $invoice->invoice_number . '.pdf"');
    }
}
