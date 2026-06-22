<!-- resources/views/pdf/invoice.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #3b82f6;
            margin-bottom: 30px;
        }
        .logo h1 {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            margin: 0;
        }
        .logo p {
            color: #64748b;
            margin: 5px 0 0;
            font-size: 12px;
        }
        .invoice-title h2 {
            font-size: 32px;
            color: #3b82f6;
            margin: 0;
        }
        .invoice-title p {
            margin: 5px 0 0;
            color: #64748b;
            text-align: right;
        }
        .row {
            display: flex;
            margin-bottom: 30px;
        }
        .col-6 {
            width: 50%;
        }
        .bill-to {
            background: #f8fafc;
            padding: 15px 20px;
            border-radius: 8px;
        }
        .bill-to h3 {
            margin: 0 0 10px;
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .bill-to .name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .bill-to .detail {
            margin: 2px 0;
            color: #334155;
        }
        .invoice-details {
            background: #f8fafc;
            padding: 15px 20px;
            border-radius: 8px;
            margin-left: 20px;
        }
        .invoice-details .detail {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .invoice-details .detail:last-child {
            border-bottom: none;
        }
        .invoice-details .label {
            color: #64748b;
        }
        .invoice-details .value {
            font-weight: 600;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        .items-table th {
            background: #f1f5f9;
            text-align: left;
            padding: 12px 15px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
        }
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .items-table tfoot td {
            border-bottom: none;
            font-weight: bold;
        }
        .items-table .text-right {
            text-align: right;
        }
        .grand-total {
            background: #f8fafc;
            padding: 15px 20px;
            border-radius: 8px;
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }
        .grand-total .total-label {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin-right: 40px;
        }
        .grand-total .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-paid { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef9c3; color: #854d0e; }
        .status-failed { background: #fee2e2; color: #991b1b; }
        .status-refunded { background: #f1f5f9; color: #475569; }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
        .text-muted { color: #94a3b8; }
        .text-sm { font-size: 12px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mt-3 { margin-top: 12px; }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="invoice-header">
        <div class="logo">
            <h1>{{ $company['name'] ?? config('app.name') }}</h1>
            <p>{{ $company['address'] ?? 'N/A' }}</p>
            <p>{{ $company['email'] ?? 'N/A' }} | {{ $company['phone'] ?? 'N/A' }}</p>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p>#{{ $invoice->invoice_number }}</p>
        </div>
    </div>

    <!-- Status Badge -->
    <div style="margin-bottom: 20px;">
        <span class="status-badge status-{{ $invoice->status }}">
            {{ strtoupper($invoice->status) }}
        </span>
    </div>

    <!-- Bill To & Details -->
    <div class="row">
        <div class="col-6">
            <div class="bill-to">
                <h3>Bill To</h3>
                <p class="name">{{ $vendor->business_name ?? 'N/A' }}</p>
                <p class="detail">{{ $vendor->user->name ?? 'N/A' }}</p>
                <p class="detail">{{ $vendor->user->email ?? 'N/A' }}</p>
                <p class="detail">{{ $vendor->address ?? '' }}</p>
                <p class="detail">{{ $vendor->city ?? '' }}{{ $vendor->state ? ', ' . $vendor->state : '' }} {{ $vendor->zip ?? '' }}</p>
                <p class="detail">{{ $vendor->phone ?? '' }}</p>
            </div>
        </div>
        <div class="col-6">
            <div class="invoice-details">
                <div class="detail">
                    <span class="label">Invoice Date</span>
                    <span class="value">{{ $invoice->created_at ? date('M d, Y', strtotime($invoice->created_at)) : 'N/A' }}</span>
                </div>
                <div class="detail">
                    <span class="label">Billing Period</span>
                    <span class="value text-sm">{{ date('M d, Y', strtotime($invoice->billing_period_start)) }} - {{ date('M d, Y', strtotime($invoice->billing_period_end)) }}</span>
                </div>
                <div class="detail">
                    <span class="label">Currency</span>
                    <span class="value">{{ $invoice->currency ?? 'USD' }}</span>
                </div>
                @if($invoice->paid_at)
                <div class="detail">
                    <span class="label">Paid At</span>
                    <span class="value">{{ date('M d, Y H:i', strtotime($invoice->paid_at)) }}</span>
                </div>
                @endif
                @if($invoice->stripe_invoice_id)
                <div class="detail">
                    <span class="label">Stripe Invoice</span>
                    <span class="value text-sm">{{ $invoice->stripe_invoice_id }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Invoice Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 60%;">Description</th>
                <th style="width: 40%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['description'] ?? 'N/A' }}</td>
                <td class="text-right">${{ number_format($item['amount'] ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="text-right">Subtotal</td>
                <td class="text-right">${{ number_format($invoice->amount, 2) }}</td>
            </tr>
            <tr>
                <td class="text-right">Tax</td>
                <td class="text-right">${{ number_format($invoice->tax, 2) }}</td>
            </tr>
            <tr style="border-top: 2px solid #3b82f6;">
                <td class="text-right" style="font-size: 16px;">Total</td>
                <td class="text-right" style="font-size: 20px; font-weight: bold; color: #3b82f6;">${{ number_format($invoice->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Payment Details -->
    @if($invoice->payment_details)
    <div style="margin-top: 20px; padding: 15px; background: #f8fafc; border-radius: 8px;">
        <h4 style="margin: 0 0 10px; font-size: 14px; color: #475569;">Payment Details</h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px;">
            <div>
                <span style="color: #64748b;">Method:</span>
                <span style="font-weight: 600;">{{ $invoice->payment_details['method'] ?? 'N/A' }}</span>
            </div>
            <div>
                <span style="color: #64748b;">Transaction ID:</span>
                <span style="font-weight: 600; font-size: 12px;">{{ $invoice->payment_details['transaction_id'] ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business!</p>
        <p style="margin: 0; color: #94a3b8;">This is a system-generated invoice.</p>
        <p style="margin: 5px 0 0; font-size: 11px; color: #cbd5e1;">{{ $company['website'] ?? config('app.url') }}</p>
    </div>
</body>
</html>