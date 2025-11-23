<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Invoice;

class RecordPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invoice_id' => ['required', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'payment_method' => ['required', 'in:cash,bank_transfer,credit_card,upi,cheque'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'invoice_id.required' => 'Please select an invoice.',
            'invoice_id.exists' => 'The selected invoice does not exist.',
            'amount.required' => 'Payment amount is required.',
            'amount.min' => 'Payment amount must be greater than 0.',
            'payment_date.required' => 'Payment date is required.',
            'payment_date.before_or_equal' => 'Payment date cannot be in the future.',
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in' => 'Invalid payment method selected.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->invoice_id) {
                $invoice = Invoice::with('payments')->find($this->invoice_id);
                
                if ($invoice) {
                    // Check if invoice belongs to user
                    if ($invoice->user_id !== auth()->id()) {
                        $validator->errors()->add('invoice_id', 'Unauthorized action.');
                        return;
                    }

                    // Check if payment exceeds outstanding amount
                    $paidAmount = $invoice->payments->sum('amount');
                    $outstanding = $invoice->total - $paidAmount;

                    if ($this->amount > $outstanding) {
                        $validator->errors()->add(
                            'amount',
                            'Payment amount cannot exceed outstanding amount of $' . number_format($outstanding, 2)
                        );
                    }
                }
            }
        });
    }
}
