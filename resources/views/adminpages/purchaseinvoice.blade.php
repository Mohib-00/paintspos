<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Purchase Invoice</title>
    <link rel="icon" href="https://admin.logix199.com/favicon.ico" type="image/x-icon">
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/font-awesome.min.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/main.css') }}">
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/color_skins.css') }}">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .invoice1 {
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .card-header {
            border-bottom: 1px solid #dee2e6 !important;
        }

        .invoice-top h4 {
            font-size: 24px;
            margin-bottom: 0.5rem;
        }

        .invoice-top p {
            margin: 0;
            color: #6c757d;
        }

        .table thead th {
            background-color: #ffc107;
            color: #212529;
            border-bottom: 2px solid #dee2e6;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table-info strong,
        .table-danger strong,
        .table-success strong {
            display: inline-block;
            width: 100%;
            padding: 6px;
            border-radius: 5px;
        }

        .table-info strong {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .table-danger strong {
            background-color: #f8d7da;
            color: #721c24;
        }

        .table-success strong {
            background-color: #d4edda;
            color: #155724;
        }

        .btn {
            min-width: 100px;
            margin: 5px;
        }

        .nodisplay {
            display: none;
        }

        @media print {
            .nodisplay {
                display: none !important;
            }

            body {
                background-color: #fff;
            }

            .card.invoice1 {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="card invoice1">
            <div class="body">
                <div class="invoice-top d-flex justify-content-between align-items-center">
                    <div class="title">
                        <h4>Invoice #{{$purchase->id}}</h4>
                        <p>Created Date:{{ $purchase->created_at->format('Y-m-d') }}</p>
                        <p>Created By: {{$purchase->user->name}}</p>
                        <p>Branch: Head Office</p>
                    </div>
                </div>
                <hr>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-warning">
                            <tr>
                                <th>Sr #</th>
                                <th>Brand Name</th>
                                <th>Product</th>
                                <th>Shade</th>
                                <th>Item Serial</th>
                                <th>Item Barcode</th>
                                <th>Qty</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedProductIds as $index => $productId)
                                @php
                                    $product = $products->firstWhere('id', $productId);
                                    $shade = \App\Models\Product::where('item_name', $product->item_name)->value('shade') ?? 'No Shade';
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->brand_name ?? 'N/A' }}</td>
                                    <td>{{ $product->item_name ?? 'Unknown Product' }}</td>
                                    <td>{{ $shade ?? 'Unknown Product' }}</td>
                                    <td>{{ $product->id ?? 'Unknown id' }}</td>
                                    <td>{{ $product->barcode ?? 'N/A' }}</td>
                                    <td>{{ $quantities[$index] ?? 'N/A' }}</td>
                                    <td>{{ $retailRates[$index] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        
                            <tr>
                                <td colspan="5"></td>
                                <td class="table-info"><strong>Gross Amount</strong></td>
                                <td class="table-info"><strong>{{ $purchase->gross_amount }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="table-danger"><strong>Discount</strong></td>
                                <td class="table-danger"><strong>{{ $purchase->discount }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="table-success"><strong>Net Amount</strong></td>
                                <td class="table-success"><strong>{{ $purchase->net_amount }}</strong></td>
                            </tr>
                        </tbody>
                        
                    </table>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h5>Note</h5>
                        <p>Purchased Items Conditions.</p>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-12 text-center mt-4">
                        <a href="/admin/purchase_list" onclick="loadpurchasePage(); return false;">
                            <button type="button" class="btn btn-danger">Back</button>
                        </a>
                        <button class="btn btn-info" id="printPageButton" onclick="window.print();">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('purchase_invoice_files/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('purchase_invoice_files/vendorscripts.bundle.js') }}"></script>
    <script src="{{ asset('purchase_invoice_files/mainscripts.bundle.js') }}"></script>
    @include('adminpages.ajax')
</body>

</html>
