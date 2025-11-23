<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\PdfService;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices with filters and search.
     */
    public function index(Request $request)
    {
        $query = Invoice::where('user_id', auth()->id())
            ->with('client');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by invoice number or client name
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->latest()->paginate(15);

        // Get status counts for filter badges
        $statusCounts = [
            'all' => Invoice::where('user_id', auth()->id())->count(),
            'draft' => Invoice::where('user_id', auth()->id())->where('status', 'draft')->count(),
            'sent' => Invoice::where('user_id', auth()->id())->where('status', 'sent')->count(),
            'paid' => Invoice::where('user_id', auth()->id())->where('status', 'paid')->count(),
            'overdue' => Invoice::where('user_id', auth()->id())->where('status', 'overdue')->count(),
        ];

        return view('user.invoices.index', compact('invoices', 'statusCounts'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        // Get user's clients for dropdown
        $clients = Client::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        // Generate next invoice number
        $lastInvoice = Invoice::where('user_id', auth()->id())
            ->latest()
            ->first();

        if ($lastInvoice) {
            // Extract number from last invoice and increment
            $lastNumber = (int) filter_var($lastInvoice->invoice_number, FILTER_SANITIZE_NUMBER_INT);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $invoiceNumber = 'INV-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return view('user.invoices.create', compact('clients', 'invoiceNumber'));
    }

    /**
     * Store a newly created invoice in storage.
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

        // Verify client belongs to user
        $client = Client::where('id', $validated['client_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $taxAmount = ($subtotal * $validated['tax']) / 100;
        $total = $subtotal + $taxAmount;

        DB::beginTransaction();
        try {
            // Create invoice
            $invoice = Invoice::create([
                'user_id' => auth()->id(),
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

            // Create invoice items
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['price'];
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();

            return redirect()->route('user.invoices.index')
                ->with('success', 'Invoice created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create invoice. Please try again.');
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        // Ensure user can only view their own invoices
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $invoice->load('client', 'items', 'payments');

        return view('user.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice)
    {
        // Ensure user can only edit their own invoices
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $clients = Client::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $invoice->load('items');

        return view('user.invoices.edit', compact('invoice', 'clients'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        // Ensure user can only update their own invoices
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
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

        // Verify client belongs to user
        $client = Client::where('id', $validated['client_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Calculate totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $taxAmount = ($subtotal * $validated['tax']) / 100;
        $total = $subtotal + $taxAmount;

        DB::beginTransaction();
        try {
            // Update invoice
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

            // Delete existing items
            $invoice->items()->delete();

            // Create new items
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['price'];
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();

            return redirect()->route('user.invoices.index')
                ->with('success', 'Invoice updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update invoice. Please try again.');
        }
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        // Ensure user can only delete their own invoices
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if invoice has payments
        if ($invoice->payments()->count() > 0) {
            return redirect()->route('user.invoices.index')
                ->with('error', 'Cannot delete invoice with existing payments.');
        }

        $invoice->delete();

        return redirect()->route('user.invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Download invoice as PDF.
     */
    public function downloadPdf(Invoice $invoice, PdfService $pdfService)
    {
        // Ensure user can only download their own invoices
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return $pdfService->downloadInvoicePdf($invoice);
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(Invoice $invoice, InvoiceService $invoiceService)
    {
        // Ensure user can only update their own invoices
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $invoiceService->markAsPaid($invoice);

        return redirect()->back()
            ->with('success', 'Invoice marked as paid.');
    }

    /**
     * Mark invoice as sent.
     */
    public function markAsSent(Invoice $invoice, InvoiceService $invoiceService)
    {
        // Ensure user can only update their own invoices
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $invoiceService->markAsSent($invoice);

        return redirect()->back()
            ->with('success', 'Invoice marked as sent.');
    }

    /**
     * Send invoice email to client.
     */
    public function sendEmail(Invoice $invoice)
    {
        // Ensure user can only send emails for their own invoices
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if client has email
        if (!$invoice->client->email) {
            return redirect()->back()
                ->with('error', 'Client does not have an email address.');
        }

        // Send notification
        $invoice->client->notify(new \App\Notifications\InvoiceCreatedNotification($invoice));

        return redirect()->back()
            ->with('success', 'Invoice email sent to ' . $invoice->client->email);
    }
}
