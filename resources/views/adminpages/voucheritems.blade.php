<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Voucher Details</title>
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/main.css') }}">
    <link rel="stylesheet" href="{{ asset('purchase_invoice_files/color_skins.css') }}">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .invoice-container {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table th {
            background-color: #ffc107;
            color: #212529;
        }
        .btn {
            min-width: 100px;
            margin: 5px;
        }
    </style>
</head>

<body>

<div class="container mt-4">
    <div class="invoice-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Invoice #{{ $voucher->id }}</h4>
            <p>Created By: {{ $voucher->user->name ?? 'N/A' }}</p>
        </div>
        <p>Created Date: {{ $voucher->created_at->format('Y-m-d') }}</p>
        <p>Branch: Head Office</p>

        <div class="table-responsive mt-3">
           <table class="table table-hover">
    <thead>
        <tr>
            <th>Sr #</th>
            <th>Narration</th>
            <th>Payment Type</th>
            <th>Account</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>Created Date</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $counter = 1; 
            $totalAmount = 0; 
            $grnAccounts = DB::table('grn_accounts')
                                ->where('voucher_id', $voucher->id)
                                ->get();

        @endphp

        @foreach ($grnAccounts as $grnAccount)
            @php
                $accountName = DB::table('add_accounts')
                                ->where('id', $grnAccount->vendor_account_id)
                                ->value('sub_head_name');
                $debitAmount = $grnAccount->debit ? number_format($grnAccount->debit, 2) : '-';
                $creditAmount = $grnAccount->vendor_net_amount ? number_format($grnAccount->vendor_net_amount, 2) : '-';
                $totalAmount += $grnAccount->debit;
            @endphp

            <tr>
                <td>{{ $counter++ }}</td>
                <td>{{ $voucher->remarks ?? 'N/A' }}</td>
                <td>{{ $voucher->voucher_type ?? 'N/A' }}</td>
                <td>{{ $accountName }}</td>
                <td>{{ $debitAmount }}</td>
                <td>{{ $creditAmount }}</td>
                <td>{{ $voucher->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="4"><strong>Total</strong></td>
            <td colspan="2"><strong>{{ number_format($totalAmount, 2) }}</strong></td>
        </tr>
    </tbody>
</table>

        </div>
    </div>

    <div class="text-center mt-4">
        <button class="btn btn-info" onclick="window.print();">Print</button>
        <a href="/admin/voucher" onclick="loadvoucher(); return false;" class="btn btn-danger">Back</a>
    </div>
</div>

<script src="{{ asset('purchase_invoice_files/libscripts.bundle.js') }}"></script>
<script src="{{ asset('purchase_invoice_files/vendorscripts.bundle.js') }}"></script>
<script src="{{ asset('purchase_invoice_files/mainscripts.bundle.js') }}"></script>

</body>
</html>
