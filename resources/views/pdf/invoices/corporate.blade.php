<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice['invoice_number'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1F2937;
            line-height: 1.6;
            background: #F9FAFB;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 50px 40px;
            background: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 50px;
            padding-bottom: 30px;
            border-bottom: 1px solid #E5E7EB;
        }
        .company-info h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #1F2937;
            letter-spacing: -0.5px;
        }
        .company-info p {
            font-size: 13px;
            color: #6B7280;
            margin: 4px 0;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title .badge {
            display: inline-block;
            background: #6366F1;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .invoice-title h2 {
            font-size: 36px;
            font-weight: 700;
            color: #1F2937;
            margin-bottom: 5px;
        }
        .invoice-title p {
            font-size: 13px;
            color: #6B7280;
        }
        .details {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
            padding: 25px;
            background: #F9FAFB;
            border-radius: 6px;
        }
        .detail-box h3 {
            font-size: 11px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .detail-box p {
            font-size: 13px;
            color: #1F2937;
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
        }
        table thead {
            background: #F3F4F6;
        }
        table th {
            padding: 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #E5E7EB;
        }
        table td {
            padding: 14px;
            font-size: 13px;
            border-bottom: 1px solid #E5E7EB;
            color: #374151;
        }
        table tbody tr:hover {
            background: #F9FAFB;
        }
        .amount-right {
            text-align: right;
        }
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .totals-box {
            width: 260px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 13px;
        }
        .total-row.subtotal {
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px solid #E5E7EB;
        }
        .total-row.total {
            padding-top: 12px;
            border-top: 2px solid #6366F1;
            font-weight: 700;
            font-size: 15px;
            color: #6366F1;
        }
        .notes-section {
            background: #F9FAFB;
            padding: 25px;
            border-radius: 6px;
            margin-top: 30px;
        }
        .notes-section h4 {
            font-size: 11px;
            font-weight: 600;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .notes-section p {
            font-size: 12px;
            color: #4B5563;
            white-space: pre-wrap;
            line-height: 1.5;
        }
        .footer {
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid #E5E7EB;
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>{{ $invoice['sender']['name'] }}</h1>
                @if ($invoice['sender']['address'])
                    <p>{{ str_replace("\n", '<br>', $invoice['sender']['address']) }}</p>
                @endif
                @if ($invoice['sender']['email'])
                    <p>{{ $invoice['sender']['email'] }}</p>
                @endif
                @if ($invoice['sender']['phone'])
                    <p>{{ $invoice['sender']['phone'] }}</p>
                @endif
            </div>
            <div class="invoice-title">
                <div class="badge">INVOICE</div>
                <h2>#{{ $invoice['invoice_number'] }}</h2>
            </div>
        </div>

        <div class="details">
            <div class="detail-box">
                <h3>Bill To</h3>
                <p>{{ $invoice['client']['name'] }}</p>
                @if ($invoice['client']['address'])
                    <p style="font-size: 12px; margin-top: 5px;">{{ str_replace("\n", '<br>', $invoice['client']['address']) }}</p>
                @endif
                @if ($invoice['client']['email'])
                    <p style="font-size: 12px;">{{ $invoice['client']['email'] }}</p>
                @endif
            </div>
            <div class="detail-box">
                <h3>Invoice Date</h3>
                <p>{{ $invoice['invoice_date'] }}</p>
            </div>
            <div class="detail-box">
                <h3>Due Date</h3>
                <p>{{ $invoice['due_date'] }}</p>
            </div>
            <div class="detail-box">
                <h3>Currency</h3>
                <p>{{ $invoice['currency'] }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Qty</th>
                    <th class="amount-right">Unit Price</th>
                    <th class="amount-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice['lines'] as $i => $line)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $line['description'] }}</td>
                        <td>{{ $line['qty'] }}</td>
                        <td class="amount-right">{{ $invoice['currency'] }} {{ number_format($line['unit_price'], 2) }}</td>
                        <td class="amount-right">{{ $invoice['currency'] }} {{ number_format($line['line_total'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-box">
                <div class="total-row subtotal">
                    <span>Subtotal</span>
                    <span>{{ $invoice['currency'] }} {{ number_format($invoice['subtotal'], 2) }}</span>
                </div>
                @if ($invoice['discount_amount'] > 0)
                    <div class="total-row">
                        <span>Discount @if ($invoice['discount_type'] === 'percent')({{ $invoice['discount'] }}%)@endif</span>
                        <span>-{{ $invoice['currency'] }} {{ number_format($invoice['discount_amount'], 2) }}</span>
                    </div>
                @endif
                @if ($invoice['tax_rate'] > 0)
                    <div class="total-row">
                        <span>Tax ({{ $invoice['tax_rate'] }}%)</span>
                        <span>{{ $invoice['currency'] }} {{ number_format($invoice['tax_amount'], 2) }}</span>
                    </div>
                @endif
                <div class="total-row total">
                    <span>Total Due</span>
                    <span>{{ $invoice['currency'] }} {{ number_format($invoice['total'], 2) }}</span>
                </div>
            </div>
        </div>

        @if ($invoice['notes'] || $invoice['terms'])
            <div class="notes-section">
                @if ($invoice['notes'])
                    <h4>Notes</h4>
                    <p>{{ $invoice['notes'] }}</p>
                @endif
                @if ($invoice['terms'])
                    <h4>Payment Terms</h4>
                    <p>{{ $invoice['terms'] }}</p>
                @endif
            </div>
        @endif

        <div class="footer">
            Generated by {{ config('app.name') }} | {{ now()->format('M d, Y H:i') }}
        </div>
    </div>
</body>
</html>
