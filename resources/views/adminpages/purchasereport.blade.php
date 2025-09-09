<!DOCTYPE html>
<html lang="en">

<head>
    @include('adminpages.css')
    <style>
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .styled-table th {
            background-color: #1a2035;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        .styled-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .styled-table tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        @include('adminpages.sidebar')

        <div class="main-panel">
            @include('adminpages.header')

            <div class="container">
                <div class="page-inner">
                    <div class="card">
                        <form method="GET" action="{{ route('purchasesssssss.search') }}" class="row g-3 p-4">
                            <div class="col-md-2">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>

                           <div class="col-md-3">
    <label for="" class="form-label">Select Product</label>
   <select name="product_id" class="form-select">
    <option value="">All Products</option>
    @foreach($products as $product)
        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
            {{ $product->item_name }}
        </option>
    @endforeach
</select>

</div>

 <div class="col-md-3">
    <label for="vendors" class="form-label">Select Vendor</label>
   <select name="vendors" class="form-select">
    <option value="">All Vendors</option>
    @foreach($purchasesvendors as $purchasesvendor)
        <option value="{{ $purchasesvendor->id }}" {{ request('vendors') == $purchasesvendor->id ? 'selected' : '' }}>
            {{ $purchasesvendor->vendors }}
        </option>
    @endforeach
</select>

</div>


                           

                           

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Detail</button>
                            </div>
                             
                        </form>

                        <div class="card-header">
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2 print-saletable">Print</button>
                                <button class="btn btn-sm btn-outline-danger export-salepdf">PDF</button>
                            </div>
                        </div>

                        <h1 class="mx-3">Purchase Report</h1>

                        <div class="card-body" style="margin-top:-40px">
                            <div class="table-responsive">
                                <table class="styled-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Shade</th>
                                            <th>Purchase Rate</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th>Retail Rate</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                   <tbody>
    @php
        $counter = 1;
        $grandPurchaseTotal = 0;
        $grandRetailTotal = 0;
        $selectedProductId = request('product_id'); 
        $quantityTotal = 0;
    @endphp

    @foreach($purchases as $purchase)
        @php
            $productIds     = json_decode($purchase['products'], true);
            $purchaseRates  = json_decode($purchase['single_purchase_rate'], true);
            $retailRates    = json_decode($purchase['single_retail_rate'], true);
            $quantities     = json_decode($purchase['original_qty'], true);
            $returnquantities     = json_decode($purchase['return_quantity'], true);
        @endphp

      @if($productIds)
    @foreach($productIds as $index => $productId)
        @if(!$selectedProductId || $productId == $selectedProductId) 
            @php
                $product = \App\Models\Product::find($productId);
                $itemName = $product ? $product->item_name : 'N/A';
                $shade = $product ? $product->shade : 'N/A';

                $qty          = isset($quantities[$index]) ? (float)$quantities[$index] : 0;
                $returnqty    = isset($returnquantities[$index]) ? (float)$returnquantities[$index] : 0;
                $purchaseRate = isset($purchaseRates[$index]) ? (float)$purchaseRates[$index] : 0;
                $retailRate   = isset($retailRates[$index]) ? (float)$retailRates[$index] : 0;

                $adjustedQty = $qty - $returnqty;

                $purchaseTotal = $purchaseRate * $adjustedQty;
                $retailTotal   = $retailRate * $adjustedQty;

                $grandPurchaseTotal += $purchaseTotal;
                $grandRetailTotal   += $retailTotal;
                $quantityTotal      += $adjustedQty;
            @endphp

            <tr>
                <td>{{ $counter++ }}</td>
                <td>{{ $productId }}</td>
                <td>{{ $itemName }}</td>
                <td>{{ $shade }}</td>
                <td>{{ number_format($purchaseRate, 2) }}</td>
                <td>{{ $adjustedQty }}</td>
                <td>{{ number_format($purchaseTotal, 2) }}</td>
                <td>{{ number_format($retailRate, 2) }}</td>
                <td>{{ $adjustedQty }}</td>
                <td>{{ number_format($retailTotal, 2) }}</td>
            </tr>
        @endif
    @endforeach
@endif

    @endforeach
</tbody>

                                    <tfoot>
                                        <tr>
                                            <td  style="white-space: nowrap;background: #1a2035;color:white"  colspan="5"><strong>Grand Purchase Total</strong></td>
                                            <td  style="white-space: nowrap;background: #1a2035;color:white" ><strong>{{ number_format($quantityTotal, 2) }}</strong></td>
                                            <td  style="white-space: nowrap;background: #1a2035;color:white" ><strong>{{ number_format($grandPurchaseTotal, 2) }}</strong></td>
                                            <td  style="white-space: nowrap;background: #1a2035;color:white"  colspan="1"><strong>Grand Retail Total</strong></td>
                                            <td  style="white-space: nowrap;background: #1a2035;color:white" ><strong>{{ number_format($quantityTotal, 2) }}</strong></td>
                                            <td  style="white-space: nowrap;background: #1a2035;color:white" ><strong>{{ number_format($grandRetailTotal, 2) }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('adminpages.footer')
        </div>
    </div>

    @include('adminpages.ajax')
    @include('adminpages.js')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const printBtn = document.querySelector('.print-saletable');
            const pdfBtn = document.querySelector('.export-salepdf');

            function removeActionColumn(clonedTable) {
                clonedTable.querySelectorAll('thead th').forEach((th, index) => {
                    if (th.innerText.trim().toLowerCase() === 'action') {
                        clonedTable.querySelectorAll('tr').forEach(row => {
                            if (row.cells.length > index) row.deleteCell(index);
                        });
                    }
                });
            }

            if (printBtn) {
                printBtn.addEventListener('click', function () {
                    const tables = document.querySelectorAll('.styled-table');
                    if (!tables.length) return alert('Tables not found!');

                    const title = "Purchase Report";
                    let clonedTables = '';
                    tables.forEach(table => {
                        const cloned = table.cloneNode(true);
                        removeActionColumn(cloned);
                        clonedTables += cloned.outerHTML;
                    });

                    const win = window.open('', '_blank');
                    win.document.write(`
                        <html>
                            <head>
                                <title>${title}</title>
                                <style>
                                    table {
                                        border-collapse: collapse;
                                        width: 100%;
                                        margin-bottom: 20px;
                                    }
                                    th, td {
                                        border: 1px solid #000;
                                        padding: 6px;
                                        text-align: center;
                                    }
                                    h1 {
                                        text-align: center;
                                    }
                                    @media print {
                                        table {
                                            font-size: 10pt;
                                        }
                                        th, td {
                                            white-space: nowrap;
                                            word-break: break-word;
                                        }
                                    }
                                </style>
                            </head>
                            <body>
                                <h1>${title}</h1>
                                ${clonedTables}
                            </body>
                        </html>
                    `);
                    win.document.close();
                    win.focus();
                    win.print();
                    win.close();
                });
            }

            if (pdfBtn) {
                pdfBtn.addEventListener('click', function () {
                    const tables = document.querySelectorAll('.styled-table');
                    if (!tables.length) return alert('Tables not found!');

                    const wrapper = document.createElement('div');
                    const title = "Purchase Report";

                    const heading = document.createElement('h1');
                    heading.innerText = title;
                    heading.style.textAlign = 'center';
                    wrapper.appendChild(heading);

                    tables.forEach(table => {
                        const cloned = table.cloneNode(true);
                        removeActionColumn(cloned);
                        wrapper.appendChild(cloned);
                    });

                    document.body.appendChild(wrapper);

                    html2canvas(wrapper).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
                        const pdfWidth = pdf.internal.pageSize.getWidth();
                        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                        pdf.addImage(imgData, 'PNG', 0, 10, pdfWidth, pdfHeight);
                        pdf.save(`${title}.pdf`);
                        document.body.removeChild(wrapper);
                    });
                });
            }
        });
    </script>
</body>

</html>
