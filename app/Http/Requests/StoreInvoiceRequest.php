<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'client_id' => ['required', 'exists:clients,id'],
            'invoice_number' => ['required', 'string', 'max:50', 'unique:invoices,invoice_number'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:issue_date'],
            'tax' => ['required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:draft,sent,paid,overdue,cancelled'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
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
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'The selected client does not exist.',
            'invoice_number.required' => 'Invoice number is required.',
            'invoice_number.unique' => 'This invoice number already exists.',
            'issue_date.required' => 'Issue date is required.',
            'due_date.required' => 'Due date is required.',
            'due_date.after_or_equal' => 'Due date must be on or after the issue date.',
            'tax.required' => 'Tax rate is required.',
            'tax.min' => 'Tax rate cannot be negative.',
            'tax.max' => 'Tax rate cannot exceed 100%.',
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
            'items.*.description.required' => 'Item description is required.',
            'items.*.quantity.required' => 'Item quantity is required.',
            'items.*.quantity.min' => 'Item quantity must be greater than 0.',
            'items.*.price.required' => 'Item price is required.',
            'items.*.price.min' => 'Item price cannot be negative.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure client belongs to authenticated user
        if ($this->client_id) {
            $client = \App\Models\Client::find($this->client_id);
            if ($client && $client->user_id !== auth()->id()) {
                abort(403, 'Unauthorized action.');
            }
        }
    }
}
