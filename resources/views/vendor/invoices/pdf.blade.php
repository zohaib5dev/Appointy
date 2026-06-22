<!-- resources/views/vendor/invoices/pdf.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #f8fafc;
            padding: 40px;
            color: #1a202c;
        }
        
        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 48px;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 24px;
            margin-bottom: 32px;
        }
        
        .header-left h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 4px;
        }
        
        .header-left .subtitle {
            color: #718096;
            font-size: 14px;
        }
        
        .header-right {
            text-align: right;
        }
        
        .header-right .invoice-number {
            font-size: 20px;
            font-weight: 600;
            color: #2d3748;
        }
        
        .header-right .invoice-label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .header-right .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }
        
        .status-paid {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .status-pending {
            background: #fefcbf;
            color: #975a16;
        }
        
        .status-draft {
            background: #e2e8f0;
            color: #2d3748;
        }
        
        .status-sent {
            background: #bee3f8;
            color: #2a69ac;
        }
        
        .status-overdue {
            background: #fed7d7;
            color: #9b2c2c;
        }
        
        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }
        
        .info-card {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }
        
        .info-card .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #718096;
            font-weight: 600;
        }
        
        .info-card .value {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-top: 4px;
        }
        
        .info-card .value.amount {
            color: #2b6cb0;
            font-size: 20px;
        }
        
        /* Customer Section */
        .customer-section {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 32px;
        }
        
        .customer-section .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 12px;
        }
        
        .customer-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        
        .customer-item .label {
            font-size: 11px;
            color: #718096;
        }
        
        .customer-item .value {
            font-size: 14px;
            font-weight: 500;
            color: #2d3748;
            margin-top: 2px;
        }
        
        /* Table */
        .table-container {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f7fafc;
        }
        
        thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #718096;
            font-weight: 600;
        }
        
        thead th.text-right {
            text-align: right;
        }
        
        thead th.text-center {
            text-align: center;
        }
        
        tbody tr {
            border-top: 1px solid #e2e8f0;
        }
        
        tbody td {
            padding: 12px 16px;
            font-size: 14px;
            color: #2d3748;
        }
        
        tbody td.text-right {
            text-align: right;
        }
        
        tbody td.text-center {
            text-align: center;
        }
        
        tbody .service-name {
            font-weight: 500;
        }
        
        tbody .service-desc {
            font-size: 12px;
            color: #718096;
            margin-top: 2px;
        }
        
        /* Table Footer */
        tfoot {
            background: #f7fafc;
            border-top: 2px solid #e2e8f0;
        }
        
        tfoot td {
            padding: 10px 16px;
            font-size: 14px;
        }
        
        tfoot .total-label {
            font-weight: 600;
            color: #2d3748;
        }
        
        tfoot .total-amount {
            font-weight: 700;
            color: #2b6cb0;
            font-size: 18px;
        }
        
        /* Notes */
        .notes-section {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 24px;
        }
        
        .notes-section .notes-label {
            font-size: 12px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px;
        }
        
        .notes-section .notes-text {
            font-size: 14px;
            color: #4a5568;
        }
        
        /* Footer */
        .footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer .footer-text {
            font-size: 12px;
            color: #718096;
        }
        
        .footer .footer-text strong {
            color: #2d3748;
        }
        
        .footer .payment-status {
            font-size: 14px;
            font-weight: 600;
        }
        
        .footer .payment-status.paid {
            color: #22543d;
        }
        
        .footer .payment-status.pending {
            color: #975a16;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 20px;
            }
            
            .invoice-container {
                box-shadow: none;
                padding: 24px;
            }
            
            .info-card {
                background: #f7fafc;
                border: 1px solid #e2e8f0;
            }
        }
        
        /* Mobile Responsive */
        @media (max-width: 640px) {
            body {
                padding: 16px;
            }
            
            .invoice-container {
                padding: 24px 16px;
            }
            
            .header {
                flex-direction: column;
                gap: 16px;
            }
            
            .header-right {
                text-align: left;
                width: 100%;
            }
            
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .customer-grid {
                grid-template-columns: 1fr;
            }
            
            .footer {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
        
        @media (max-width: 400px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <h1>Invoice</h1>
                <div class="subtitle">Your customer invoice</div>
            </div>
            <div class="header-right">
                <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
                <div class="invoice-label">Invoice Number</div>
                <span class="status-badge status-{{ $invoice->appointment->payment_status ?? 'pending' }}">
                    {{ ucfirst($invoice->appointment->payment_status ?? 'Pending') }}
                </span>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-card">
                <div class="label">Invoice #</div>
                <div class="value">{{ $invoice->invoice_number }}</div>
            </div>
            <div class="info-card">
                <div class="label">Appointment #</div>
                <div class="value">{{ $invoice->appointment->booking_number ?? 'N/A' }}</div>
            </div>
            <div class="info-card">
                <div class="label">Total Amount</div>
                <div class="value amount">${{ number_format($invoice->total, 2) }}</div>
            </div>
            <div class="info-card">
                <div class="label">Date</div>
                <div class="value">{{ $invoice->created_at ? $invoice->created_at : 'N/A' }}</div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="customer-section">
            <div class="section-title">Customer Information</div>
            <div class="customer-grid">
                <div class="customer-item">
                    <div class="label">Name</div>
                    <div class="value">{{ $invoice->customer->name ?? 'N/A' }}</div>
                </div>
                <div class="customer-item">
                    <div class="label">Email</div>
                    <div class="value">{{ $invoice->customer->email ?? 'N/A' }}</div>
                </div>
                <div class="customer-item">
                    <div class="label">Phone</div>
                    <div class="value">{{ $invoice->customer->phone ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Services Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th class="text-center">Duration</th>
                        <th class="text-right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $items = is_string($invoice->items) ? json_decode($invoice->items, true) : $invoice->items ?? [];
                        $subtotal = 0;
                    @endphp
                    @forelse($items as $item)
                        @php
                            $price = floatval($item['price'] ?? 0);
                            $subtotal += $price;
                        @endphp
                        <tr>
                            <td>
                                <div class="service-name">{{ $item['service_name'] ?? 'N/A' }}</div>
                                @if(!empty($item['description']))
                                    <div class="service-desc">{{ $item['description'] }}</div>
                                @endif
                            </td>
                            <td class="text-center">{{ $item['duration'] ?? 0 }} min</td>
                            <td class="text-right">${{ number_format($price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 24px; color: #718096;">
                                No services found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: 500;">Subtotal</td>
                        <td style="text-align: right; font-weight: 500;">${{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: 500;">Tax</td>
                        <td style="text-align: right; font-weight: 500;">${{ number_format(floatval($invoice->tax ?? 0), 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: 700; font-size: 16px;">Total</td>
                        <td style="text-align: right; font-weight: 700; font-size: 18px; color: #2b6cb0;">
                            ${{ number_format(floatval($invoice->total ?? 0), 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Notes -->
        @if(!empty($invoice->notes))
            <div class="notes-section">
                <div class="notes-label">Notes</div>
                <div class="notes-text">{{ $invoice->notes }}</div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <strong>Invoice #{{ $invoice->invoice_number }}</strong> • 
            </div>
            <div class="payment-status {{ $invoice->appointment->payment_status ?? 'pending' }}">
                Status: {{ ucfirst($invoice->appointment->payment_status ?? 'Pending') }}
            </div>
        </div>
    </div>
</body>
</html>