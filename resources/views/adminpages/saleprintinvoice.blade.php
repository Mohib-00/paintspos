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
            max-width: 500px; 
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

        .invoice-info, .customer-info {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .invoice-info p, .customer-info p {
            margin: 5px 0;
        }

        .invoice-info strong, .customer-info strong {
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
            padding: 10px;
            border: 1px solid #e0e0e0;
        }

        .invoice-table thead {
            background-color: #f1f3f5;
        }

        /* ✅ totals block */
        .totals-block {
            margin-top: 20px;
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
           
            color: black;
            font-size: 15px;
        }

        .thank-you-note {
            text-align: center;
            font-size: 15px;
            margin-top: 50px;
            font-style: italic;
            color: #555;
            border-top: 1px dashed #ccc;
            padding-top: 20px;
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
            display: inline-block;
            text-align: center;
        }

        .print-btn a {
            background-color: #6c757d;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #invoicePrintArea, #invoicePrintArea * {
                visibility: visible;
            }

            #invoicePrintArea {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                margin: auto;
                width: 500px;
                padding: 20px;
                box-shadow: none;
            }

            button {
                display: none !important;
            }

            .thank-you-note, .signature {
                text-align: center !important;
                color: #000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="invoicePrintArea" class="invoice-container">
            <div class="invoice-header">
                <h1>SALE INVOICE</h1>
            </div>

            <div class="invoice-info">
                <p><strong>Invoice No:</strong> INV-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->created_at)->format('Y-m-d') }}</p>
            </div>

            <div class="customer-info" style="margin-top:-10px">
                <p><strong>Customer Name:</strong> {{ $sale->customer_name ?? 'N/A' }}</p>
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Shade</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->saleItems as $item)
                     @php
                        $shade = \App\Models\Product::where('item_name', $item->product_name)->value('shade') ?? 'No Shade';
                    @endphp
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $shade }}</td>
                            <td>{{ rtrim(rtrim(number_format($item->product_quantity, 2, '.', ''), '0'), '.') }}</td>
                            <td>Rs:{{ number_format($item->product_rate, 2) }}</td>
                            <td>Rs:{{ number_format($item->product_subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- ✅ totals block -->
            <div class="totals-block">
                <table class="totals-table">
                    <tbody>
                        <tr>
                            <td>Total</td>
                            <td>Rs: {{ number_format($sale->total, 2) }}</td>
                        </tr>
                        @if ($sale->discount && $sale->discount > 0)
                        <tr>
                            <td>Discount</td>
                            <td>- Rs: {{ number_format($sale->discount, 2) }}</td>
                        </tr>
                        @elseif ($sale->fixed_discount && $sale->fixed_discount > 0)
                        <tr>
                            <td>Fix Discount</td>
                            <td>- Rs: {{ number_format($sale->fixed_discount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="grand-total">
                            <td>Subtotal</td>
                            <td>Rs: {{ number_format($sale->subtotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Thank You Note -->
            <div class="thank-you-note">
                <p>Thank you for your purchase! We appreciate your business and look forward to serving you again.</p>
            </div>
            <div class="signature">
                 System Created By Mohib<br>03078086914<br>03464551923
            </div>

            <div class="print-btn">
                <button onclick="printInvoice()" type="button">
                    Print Invoice
                </button>

                <a href="/admin/sale_list" onclick="loadsalelistPage(); return false;">
                    Back
                </a>
            </div>
        </div>
    </div>

    @include('adminpages.footer')
    @include('adminpages.js')
    @include('adminpages.ajax')

    <script>
        function printInvoice() {
            const printContent = document.getElementById('invoicePrintArea').cloneNode(true);

            const printBtn = printContent.querySelector('.print-btn');
            if (printBtn) {
                printBtn.remove();
            }

            const today = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = today.toLocaleDateString('en-US', options);

            const printWindow = window.open('', '', 'height=800,width=700');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Sale Invoice</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 40px; }
                        h1, .invoice-header h1 { text-align: center; }
                        .date-center {
                            text-align: center;
                            font-size: 18px;
                            font-weight: bold;
                            margin-bottom: 20px;
                        }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: left; }

                        .totals-block {
                            margin-top: 20px;
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
                            color: black;
                            font-size: 15px;
                        }
                        .thank-you-note { margin-top: 30px; font-style: italic; text-align: center; }
                        .signature { margin-top: 20px; text-align: center; }
                    </style>
                </head>
                <body>
                    <div class="date-center">${formattedDate}</div>
                    ${printContent.innerHTML}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => printWindow.print(), 500);
        }
    </script>

</body>
</html>
