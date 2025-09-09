<!DOCTYPE html>
<html lang="en">
<head>
    @include('adminpages.css')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            width:fit-content;
            margin: 40px auto;
            background: #fff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .invoice-header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }
        .invoice-header h1 {
            margin: 0;
            color: #007bff;
            font-size: 28px;
            font-weight: bold;
        }

        .info-block {
            margin-bottom: 20px;
            font-size: 14px;
        }
        .info-block p {
            margin: 5px 0;
        }
        .info-block strong {
            color: #333;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .invoice-table th, .invoice-table td {
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #e0e0e0;
        }
        .invoice-table thead {
            background-color: #f1f3f5;
        }

        .totals-block {
            display: flex;
            justify-content: flex-end;
        }
        .totals-table {
            border-collapse: collapse;
            width: 300px;
            font-size: 14px;
            background: #f9fbfd;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .totals-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }
        .totals-table tr:last-child td {
            border-bottom: none;
        }
        .totals-table td:first-child {
            font-weight: bold;
            color: #333;
        }
        .totals-table td:last-child {
            text-align: right;
            font-weight: bold;
            color: #000;
        }
        .totals-table .grand-total td {
            font-size: 15px;
        }

        .thank-you-note {
            text-align: center;
            font-size: 15px;
            margin-top: 40px;
            font-style: italic;
            color: #555;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
        }
        .signature {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }

        .print-btn {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            gap: 10px;
        }
        .print-btn button,
        .print-btn a {
            padding: 10px 18px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }
        .print-btn a {
            background-color: #6c757d;
        }

        @media print {
            body * { visibility: hidden; }
            #invoicePrintArea, #invoicePrintArea * { visibility: visible; }
            #invoicePrintArea {
                position: absolute;
                top: 0; left: 0; right: 0;
                margin: auto;
                width: 500px;
                padding: 20px;
                box-shadow: none;
            }
            .print-btn { display: none !important; }
        }
    </style>
</head>
<body>
<div class="container">
    <div id="invoicePrintArea" class="invoice-container">
        <div class="invoice-header">
            <h1>SALE INVOICE</h1>
        </div>

        <div class="info-block">
            <p><strong>Invoice No:</strong> INV-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Date:</strong> {{ $sale->created_at->format('Y-m-d') }}</p>
        </div>

        <div class="info-block" style="margin-top:-10px">
            <p><strong>Customer Name:</strong> {{ $sale->customer_name ?? 'N/A' }}</p>
        </div>

        @php
            $hasReturn = $sale->saleItems->contains(fn($item) => !empty($item->return_qty) || !empty($item->return_amount));
            $total = $sale->saleItems->sum('product_subtotal');
            $returnTotal = $sale->saleItems->sum('return_amount');
            $netTotal = $total - $returnTotal;

            $discount = $sale->discount ?? 0;
            $fixedDiscount = $sale->fixed_discount ?? 0;
            $grandTotal = $netTotal - $discount - $fixedDiscount;
        @endphp

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Shade</th>
                    <th>Qty</th>
                    @if ($hasReturn)
                        <th>R.Qty</th>
                        <th>R.Amount</th>
                    @endif
                    <th>Total</th>
                    @if ($hasReturn)
                        <th>Net Qty</th>
                        <th>Net Total</th>
                    @endif
                </tr>
            </thead>
          @php
    $groupedItems = [];

    foreach ($sale->saleItems as $item) {
        $shade = \App\Models\Product::where('item_name', $item->product_name)->value('shade') ?? 'No Shade';

        $returnQty = $item->return_qty ?? 0;
        $returnAmount = $item->return_amount ?? 0;
        $netQty = $item->product_quantity - $returnQty;
        $netLineTotal = $item->product_subtotal - $returnAmount;

        if (!isset($groupedItems[$shade])) {
            $groupedItems[$shade] = [
                'product_name' => $item->product_name,
                'shade' => $shade,
                'quantity' => 0,
                'return_qty' => 0,
                'return_amount' => 0,
                'subtotal' => 0,
                'net_qty' => 0,
                'net_line_total' => 0,
            ];
        }

        $groupedItems[$shade]['quantity'] += $item->product_quantity;
        $groupedItems[$shade]['return_qty'] += $returnQty;
        $groupedItems[$shade]['return_amount'] += $returnAmount;
        $groupedItems[$shade]['subtotal'] += $item->product_subtotal;
        $groupedItems[$shade]['net_qty'] += $netQty;
        $groupedItems[$shade]['net_line_total'] += $netLineTotal;
    }
@endphp

<tbody>
    @foreach ($groupedItems as $group)
        <tr>
            <td>{{ $group['product_name'] }}</td>
            <td>{{ $group['shade'] }}</td>
            <td>{{ number_format($group['quantity'], 2) }}</td>

            @if ($hasReturn)
                <td>{{ $group['return_qty'] > 0 ? number_format($group['return_qty'], 2) : '-' }}</td>
                <td>{{ $group['return_amount'] > 0 ? 'Rs:' . number_format($group['return_amount'], 2) : '-' }}</td>
            @endif

            <td>Rs:{{ number_format($group['subtotal'], 2) }}</td>

            @if ($hasReturn)
                <td>{{ number_format($group['net_qty'], 2) }}</td>
                <td>Rs:{{ number_format($group['net_line_total'], 2) }}</td>
            @endif
        </tr>
    @endforeach
</tbody>

        </table>

        <div class="totals-block">
            <table class="totals-table">
                <tbody>
                    <tr>
                        <td>Total</td>
                        <td>Rs: {{ number_format($total, 2) }}</td>
                    </tr>
                    @if ($hasReturn)
                    <tr>
                        <td>Return Amount</td>
                        <td>- Rs: {{ number_format($returnTotal, 2) }}</td>
                    </tr>
                    @endif
                    @if ($discount > 0)
                    <tr>
                        <td>Discount</td>
                        <td>- Rs: {{ number_format($discount, 2) }}</td>
                    </tr>
                    @endif
                    @if ($fixedDiscount > 0)
                    <tr>
                        <td>Fix Discount</td>
                        <td>- Rs: {{ number_format($fixedDiscount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="grand-total">
                        <td>Net Subtotal</td>
                        <td>Rs: {{ number_format($grandTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="thank-you-note">
            <p>Thank you for your purchase! We appreciate your business and look forward to serving you again.</p>
        </div>
        <div class="signature">
            System Created By Mohib<br>3078086914<br>03464551923
        </div>

        <div class="print-btn">
            <button onclick="printInvoice()" type="button">Print Invoice</button>
            <a href="/admin/sale_list" onclick="loadsalelistPage(); return false;">Back</a>
        </div>
    </div>
</div>

@include('adminpages.footer')
@include('adminpages.js')
@include('adminpages.ajax')

<script>
    function printInvoice() {
        let content = document.getElementById('invoicePrintArea').cloneNode(true);

        const printBtns = content.querySelectorAll('.print-btn');
        printBtns.forEach(btn => btn.remove());

        const win = window.open('', '', 'height=800,width=700');
        win.document.write(`
            <html>
            <head>
                <title>Sale Invoice</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 40px; }
                    h1 { text-align: center; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                    .totals-table { width: 300px; float: right; margin-top: 20px; }
                    .totals-table td { padding: 6px; font-weight: bold; }
                    .thank-you-note { margin-top: 30px; font-style: italic; text-align: center; }
                    .signature { margin-top: 20px; text-align: center; }
                </style>
            </head>
            <body>
                ${content.innerHTML}
            </body>
            </html>
        `);
        win.document.close();
        win.focus();
        setTimeout(() => win.print(), 400);
    }
</script>

</body>
</html>
