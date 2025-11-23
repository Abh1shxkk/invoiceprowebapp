<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments with filters.
     */
    public function index(Request $request)
    {
        $query = Payment::whereHas('invoice', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->with('invoice.client');

        // Filter by payment method
        if ($request->has('method') && $request->method != '') {
            $query->where('payment_method', $request->method);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest('payment_date')->paginate(15);

        // Calculate total
        $total = $query->sum('amount');

        return view('user.payments.index', compact('payments', 'total'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request)
    {
        // Get invoice if specified
        $invoice = null;
        if ($request->has('invoice_id')) {
            $invoice = Invoice::where('id', $request->invoice_id)
                ->where('user_id', auth()->id())
                ->with('client', 'payments')
                ->firstOrFail();
        }

        // Get unpaid/partially paid invoices for dropdown
        $invoices = Invoice::where('user_id', auth()->id())
            ->whereIn('status', ['sent', 'overdue', 'draft'])
            ->with('client')
            ->get();

        return view('user.payments.create', compact('invoice', 'invoices'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,credit_card,upi,cheque'],
            'notes' => ['nullable', 'string'],
        ]);

        // Verify invoice belongs to user
        $invoice = Invoice::where('id', $validated['invoice_id'])
            ->where('user_id', auth()->id())
            ->with('payments')
            ->firstOrFail();

        // Calculate outstanding amount
        $paidAmount = $invoice->payments->sum('amount');
        $outstanding = $invoice->total - $paidAmount;

        // Validate payment amount doesn't exceed outstanding
        if ($validated['amount'] > $outstanding) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Payment amount cannot exceed outstanding amount of $' . number_format($outstanding, 2));
        }

        DB::beginTransaction();
        try {
            // Create payment
            $payment = Payment::create($validated);

            // Recalculate total paid
            $totalPaid = $invoice->payments()->sum('amount') + $validated['amount'];

            // Update invoice status
            if ($totalPaid >= $invoice->total) {
                $invoice->update(['status' => 'paid']);
            }

            // Send payment receipt notification
            if ($invoice->client->email) {
                $invoice->client->notify(new \App\Notifications\PaymentReceivedNotification($payment));
            }

            DB::commit();

            return redirect()->route('user.invoices.show', $invoice)
                ->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to record payment. Please try again.');
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        // Ensure user can only view payments for their own invoices
        if ($payment->invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $payment->load('invoice.client');

        return view('user.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        // Ensure user can only edit payments for their own invoices
        if ($payment->invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $payment->load('invoice.client');

        return view('user.payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // Ensure user can only update payments for their own invoices
        if ($payment->invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,credit_card,upi,cheque'],
            'notes' => ['nullable', 'string'],
        ]);

        $invoice = $payment->invoice;

        // Calculate outstanding amount (excluding this payment)
        $paidAmount = $invoice->payments()->where('id', '!=', $payment->id)->sum('amount');
        $outstanding = $invoice->total - $paidAmount;

        // Validate payment amount doesn't exceed outstanding
        if ($validated['amount'] > $outstanding) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Payment amount cannot exceed outstanding amount of $' . number_format($outstanding, 2));
        }

        DB::beginTransaction();
        try {
            // Update payment
            $payment->update($validated);

            // Recalculate total paid
            $totalPaid = $invoice->payments()->sum('amount');

            // Update invoice status
            if ($totalPaid >= $invoice->total) {
                $invoice->update(['status' => 'paid']);
            } else {
                // If was paid but now isn't, revert status
                if ($invoice->status === 'paid') {
                    $invoice->update(['status' => 'sent']);
                }
            }

            DB::commit();

            return redirect()->route('user.invoices.show', $invoice)
                ->with('success', 'Payment updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update payment. Please try again.');
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        // Ensure user can only delete payments for their own invoices
        if ($payment->invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $invoice = $payment->invoice;

        DB::beginTransaction();
        try {
            $payment->delete();

            // Recalculate total paid
            $totalPaid = $invoice->payments()->sum('amount');

            // Update invoice status
            if ($totalPaid >= $invoice->total) {
                $invoice->update(['status' => 'paid']);
            } else {
                // If was paid but now isn't, revert status
                if ($invoice->status === 'paid') {
                    $invoice->update(['status' => 'sent']);
                }
            }

            DB::commit();

            return redirect()->route('user.invoices.show', $invoice)
                ->with('success', 'Payment deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to delete payment. Please try again.');
        }
    }
}
