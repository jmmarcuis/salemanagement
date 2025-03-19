<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sale #{{ $sale->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-box {
            width: 48%;
        }
        .info-box h2 {
            font-size: 14px;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .summary {
            width: 300px;
            margin-left: auto;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .total-row {
            font-weight: bold;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 5px;
        }
        .notes {
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 20px;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
        }
        .status-complete {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-canceled {
            background-color: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sale Invoice #{{ $sale->id }}</h1>
        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="info-container">
        <div class="info-box">
            <h2>Sale Information</h2>
            <p><strong>Status:</strong> 
                <span class="status {{ 'status-' . $sale->status->value }}">
                    {{ ucfirst($sale->status->value) }}
                </span>
            </p>
            <p><strong>Date Created:</strong> {{ $sale->created_at->format('F d, Y h:i A') }}</p>
            <p><strong>Deadline:</strong> {{ $sale->deadline ? $sale->deadline->format('F d, Y') : 'N/A' }}</p>
            <p><strong>Authorized by:</strong> {{ $sale->user->name }}</p>
        </div>
        
        <div class="info-box">
            <h2>Customer Information</h2>
            <p><strong>Name:</strong> {{ $sale->customer->name }}</p>
            <p><strong>Email:</strong> {{ $sale->customer->email }}</p>
            <p><strong>Phone:</strong> {{ $sale->customer->phone }}</p>
            <p><strong>Address:</strong> {{ $sale->customer->address }}</p>
        </div>
    </div>

    <h2>Sale Items</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th style="text-align: right;">Unit Price</th>
                <th style="text-align: right;">Quantity</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>
                        <div>
                            <p><strong>{{ $item->product->name }}</strong></p>
                            <p style="color: #666;">Code: {{ $item->product->code }}</p>
                        </div>
                    </td>
                    <td style="text-align: right;">₱ {{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">₱ {{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (count($sale->discounts) > 0)
        <h2>Discount Applied</h2>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Type</th>
                    <th style="text-align: right;">Value</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->discounts as $discount)
                    <tr>
                        <td>{{ $discount->description }}</td>
                        <td>{{ $discount->discount_type->value }}</td>
                        <td style="text-align: right;">
                            @if ($discount->discount_type->value == 'PERCENTAGE')
                                {{ $discount->value }}%
                            @else
                                ₱ {{ number_format($discount->value, 2) }}
                            @endif
                        </td>
                        <td style="text-align: right;">₱ {{ number_format($discount->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="summary">
        <h2>Sale Summary</h2>
        <div class="summary-row">
            <span>Total Amount:</span>
            <span>₱ {{ number_format($sale->total_amount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span style="color: #dc3545;">Discount Amount:</span>
            <span style="color: #dc3545;">- ₱ {{ number_format($sale->discount_amount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>VAT Amount:</span>
            <span>₱ {{ number_format($sale->vat_amount, 2) }}</span>
        </div>
        <div class="summary-row total-row">
            <span>Grand Total:</span>
            <span>₱ {{ number_format($sale->grand_total, 2) }}</span>
        </div>
    </div>

    @if ($sale->notes)
        <div class="notes">
            <h2>Notes</h2>
            <p>{{ $sale->notes }}</p>
        </div>
    @endif
</body>
</html>