<!DOCTYPE html>
<html lang="en">
<head>
    @include('adminpages.css')

    <style>
        /* ---------- General Card & Button Styling ---------- */
        .card-header {
            display: flex;
            align-items: center;
        }

        .addemployee {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-left: auto;
        }

        .addemployee:hover {
            background-color: #45a049;
        }

        /* ---------- Modal Styling ---------- */
        .custom-modal.employee, .custom-modal.employeeedit {
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-dialog {
            max-width: 800px;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
        }

        /* ---------- Table Styling ---------- */
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <!-- ---------- Filter Form ---------- -->
                            <form method="GET" action="{{ route('vendorreportsearch') }}" class="row g-3 p-4">
                                <div class="col-md-2">
                                    <label for="from_date">From Date</label>
                                    <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="to_date">To Date</label>
                                    <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="name">All Vendors</label>
                                    <select class="form-select" name="name">
                                    <option value="">Select</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->name }}"
                                        {{ request('vendor_name') == $vendor->name ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Search</button>
                                </div>
                            </form>

                            <div class="card-header">
                                <div>
                                    <button class="btn btn-sm btn-outline-primary me-2 print-saletable">Print</button>
                                    <button class="btn btn-sm btn-outline-danger export-salepdf">PDF</button>
                                </div>
                            </div>

                            <h1 class="mx-3">Vendor Report</h1>

                            <div class="card-body" style="margin-top:-40px">
                                <div class="table-responsive">
                                    <table class="styled-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th style="text-align:left;width:680px;">Vendor</th>
                                               
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1; 
                                                $debitTotal = 0; 
                                                $creditTotal = 0; 
                                                $differenceTotal = 0; 
                                            @endphp

                                            @foreach ($vendorData as $data)
                                                @php
                                                    $debitTotal += ($data['total_debit'] ?? 0);
                                                    $creditTotal += ($data['total_net_amount'] ?? 0);
                                                    $differenceTotal += (($data['total_debit'] ?? 0) - ($data['total_net_amount'] ?? 0));
                                                @endphp
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td style="text-align:left">{{ $data['vendor']->name }}</td>
                                                    <td>{{ number_format($data['total_debit'], 2) }}</td>
                                                    <td>{{ number_format($data['total_net_amount'], 2) }}</td>
                                                    <td>{{ number_format($data['total_debit'] - $data['total_net_amount'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">Total</th>
                                                <th>{{ number_format($debitTotal, 2) }}</th>
                                                <th>{{ number_format($creditTotal, 2) }}</th>
                                                <th>{{ number_format($differenceTotal, 2) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('adminpages.footer')
    </div>
</div>

@include('adminpages.js')
@include('adminpages.ajax')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const printBtn = document.querySelector('.print-saletable');
        const pdfBtn = document.querySelector('.export-salepdf');

        function removeActionColumn(clonedTable) {
            const headerCells = clonedTable.querySelectorAll('thead th');
            let actionIndex = -1;
            headerCells.forEach((th, index) => {
                if (th.innerText.trim().toLowerCase() === 'action') {
                    actionIndex = index;
                }
            });
            if (actionIndex > -1) {
                clonedTable.querySelectorAll('thead tr').forEach(row => row.deleteCell(actionIndex));
                clonedTable.querySelectorAll('tbody tr').forEach(row => {
                    if (row.cells.length > actionIndex) {
                        row.deleteCell(actionIndex);
                    }
                });
            }
        }

        if (printBtn) {
            printBtn.addEventListener('click', function () {
                const table = document.querySelector('.styled-table');
                if (!table) {
                    alert('Table not found!');
                    return;
                }
                const clonedTable = table.cloneNode(true);
                removeActionColumn(clonedTable);

                const newWin = window.open('', '_blank');
                newWin.document.write(`
                    <html>
                    <head>
                        <title>Vendor Report</title>
                        <style>
                            table { border-collapse: collapse; width: 100%; }
                            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                            h1 { text-align: center; }
                        </style>
                    </head>
                    <body>
                        <h1>Vendor Report</h1>
                        ${clonedTable.outerHTML}
                    </body>
                    </html>
                `);
                newWin.document.close();
                newWin.print();
            });
        }

        if (pdfBtn) {
            pdfBtn.addEventListener('click', function () {
                const table = document.querySelector('.styled-table');
                if (!table) {
                    alert('Table not found!');
                    return;
                }

                const wrapper = document.createElement('div');
                const heading = document.createElement('h1');
                heading.innerText = 'Vendor Report';
                heading.style.textAlign = 'center';
                wrapper.appendChild(heading);

                const clonedTable = table.cloneNode(true);
                removeActionColumn(clonedTable);
                wrapper.appendChild(clonedTable);

                document.body.appendChild(wrapper);

                html2pdf(wrapper, {
                    margin: 1,
                    filename: 'Vendor_Report.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                }).then(() => {
                    document.body.removeChild(wrapper);
                });
            });
        }
    });
</script>
</body>
</html>
