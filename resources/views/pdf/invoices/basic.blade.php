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
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 20px;
        }
        .company-info h1 {
            font-size: 28px;
            margin-bottom: 5px;
            color: #1F2937;
        }
        .company-info p {
            font-size: 12px;
            color: #666;
            margin: 2px 0;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 32px;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .invoice-title p {
            font-size: 12px;
            color: #666;
        }
        .details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background: #F3F4F6;
            padding: 20px;
            border-radius: 5px;
        }
        .detail-box {
            flex: 1;
        }
        .detail-box h3 {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .detail-box p {
            font-size: 12px;
            color: #1F2937;
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table thead {
            background: #F3F4F6;
            border-top: 2px solid #E5E7EB;
            border-bottom: 2px solid #E5E7EB;
        }
        table th {
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
        }
        table td {
            padding: 12px;
            font-size: 12px;
            border-bottom: 1px solid #E5E7EB;
        }
        table tr:last-child td {
            border-bottom: none;
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
            width: 250px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 12px;
        }
        .total-row.subtotal {
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .total-row.total {
            border-top: 2px solid #E5E7EB;
            padding-top: 10px;
            font-weight: bold;
            font-size: 14px;
            color: #4F46E5;
        }
        .notes-section {
            border-top: 1px solid #E5E7EB;
            padding-top: 20px;
            margin-top: 20px;
        }
        .notes-section h4 {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .notes-section p {
            font-size: 11px;
            color: #4B5563;
            white-space: pre-wrap;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
            white-space: nowrap;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            font-size: 10px;
            color: #999;
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
                <h2>INVOICE</h2>
                <p>#{{ $invoice['invoice_number'] }}</p>
            </div>
        </div>

        <div class="details">
            <div class="detail-box">
                <h3>Bill To</h3>
                <p>{{ $invoice['client']['name'] }}</p>
                @if ($invoice['client']['address'])
                    <p style="font-size: 10px; margin-top: 5px;">{{ str_replace("\n", '<br>', $invoice['client']['address']) }}</p>
                @endif
                @if ($invoice['client']['email'])
                    <p style="font-size: 10px;">{{ $invoice['client']['email'] }}</p>
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

    @php
        $user = auth()->user();
        $hasWatermark = $user ? (! $user->subscription || $user->subscription->plan->slug === 'free') : false;
    @endphp
    @if ($hasWatermark)
        <div class="watermark">FREE PLAN</div>
    @endif
</body>
</html>
