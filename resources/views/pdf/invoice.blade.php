<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 3px solid #1E40AF;
            padding-bottom: 20px;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .header-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1E40AF;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #1E40AF;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 16px;
            color: #666;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .info-label {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .info-content {
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }

        .info-content strong {
            font-weight: bold;
            color: #000;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table thead {
            background-color: #F3F4F6;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            border-bottom: 2px solid #E5E7EB;
        }

        .items-table th.text-right {
            text-align: right;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 13px;
        }

        .items-table td.text-right {
            text-align: right;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #E5E7EB;
        }

        .totals {
            margin-left: auto;
            width: 300px;
        }

        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .total-label {
            display: table-cell;
            font-size: 13px;
            color: #666;
            padding-right: 20px;
        }

        .total-value {
            display: table-cell;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .grand-total {
            border-top: 2px solid #E5E7EB;
            padding-top: 12px;
            margin-top: 12px;
        }

        .grand-total .total-label {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }

        .grand-total .total-value {
            font-size: 20px;
            color: #1E40AF;
        }

        .notes {
            margin-top: 40px;
            padding: 20px;
            background-color: #F9FAFB;
            border-left: 4px solid #1E40AF;
        }

        .notes-title {
            font-size: 12px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .notes-content {
            font-size: 12px;
            color: #666;
            line-height: 1.6;
            white-space: pre-line;
        }

        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 11px;
            color: #999;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .status-sent {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .status-draft {
            background-color: #F3F4F6;
            color: #374151;
        }

        .status-overdue {
            background-color: #FEE2E2;
            color: #991B1B;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="header-left">
                    <div class="company-name">{{ $settings->company_name ?? 'InvoicePro' }}</div>
                    <div class="company-details">
                        @if($settings && $settings->address)
                            {{ $settings->address }}<br>
                        @endif
                        {{ $invoice->user->email }}
                    </div>
                </div>
                <div class="header-right">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
                    <div style="margin-top: 10px;">
                        <span class="status-badge status-{{ $invoice->status }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Info -->
        <div class="info-section">
            <div class="info-grid">
                <div class="info-column">
                    <div class="info-label">Bill To</div>
                    <div class="info-content">
                        <strong>{{ $invoice->client->name }}</strong><br>
                        @if($invoice->client->company)
                            {{ $invoice->client->company }}<br>
                        @endif
                        @if($invoice->client->email)
                            {{ $invoice->client->email }}<br>
                        @endif
                        @if($invoice->client->phone)
                            {{ $invoice->client->phone }}<br>
                        @endif
                        @if($invoice->client->address)
                            <br>{{ $invoice->client->address }}<br>
                            @if($invoice->client->city || $invoice->client->state || $invoice->client->zip)
                                {{ $invoice->client->city }}{{ $invoice->client->city && ($invoice->client->state || $invoice->client->zip) ? ', ' : '' }}
                                {{ $invoice->client->state }} {{ $invoice->client->zip }}<br>
                            @endif
                            @if($invoice->client->country)
                                {{ $invoice->client->country }}
                            @endif
                        @endif
                    </div>
                </div>
                <div class="info-column">
                    <div class="info-label">Invoice Details</div>
                    <div class="info-content">
                        <strong>Issue Date:</strong> {{ $invoice->issue_date->format('M d, Y') }}<br>
                        <strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}<br>
                        @if($invoice->client->tax_number)
                            <strong>Tax ID:</strong> {{ $invoice->client->tax_number }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="text-right">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="text-right">${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <div class="total-label">Subtotal:</div>
                <div class="total-value">${{ number_format($invoice->subtotal, 2) }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Tax ({{ number_format($invoice->tax, 2) }}%):</div>
                <div class="total-value">${{ number_format(($invoice->subtotal * $invoice->tax) / 100, 2) }}</div>
            </div>
            <div class="total-row grand-total">
                <div class="total-label">Total:</div>
                <div class="total-value">${{ number_format($invoice->total, 2) }}</div>
            </div>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
            <div class="notes">
                <div class="notes-title">Notes</div>
                <div class="notes-content">{{ $invoice->notes }}</div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This invoice was generated by InvoicePro</p>
        </div>
    </div>
</body>
</html>
