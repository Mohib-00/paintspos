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
            background-color: #f4f4f9;
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
                        <form class="row g-3 p-4" method="GET" action="{{ route('searchbalancesheet') }}">
                            <div class="col-md-2">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control"
                                    value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control"
                                    value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </form>

                        <div class="card-header">
                            <button class="btn btn-sm btn-outline-primary me-2 print-saletable">Print</button>
                            <button class="btn btn-sm btn-outline-danger export-salepdf">PDF</button>
                        </div>

                        <h1 class="mx-3">Balance Sheet</h1>

                        <div class="card-body" style="margin-top:-40px">
                            <div class="table-responsive">
                                <table class="styled-table">
                                    <thead>
                                        <tr>
                                            <th style="background: #1a2035; color:white; width:35%;text-align:left">Assets</th>
                                            <th style="background: #1a2035; color:white;text-align:left">Amount</th>
                                            <th style="background: #1a2035; color:white;text-align:left">Liabilities & Equity</th>
                                            <th style="background: #1a2035; color:white;text-align:left">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="font-weight:bolder;font-size:20px">Current Assets</td>
                                            <td></td>
                                            <td style="font-weight:bolder;font-size:20px">Current Liabilities</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left">Cash In Hand</td>
                                            <td style="text-align:left">{{ number_format($cashInHandBalance, 2) }}</td>
                                            <td style="text-align:left">Accounts Payable</td>
                                            <td style="text-align:left">{{ number_format($accountsPayableBalance, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left">Cash At Bank</td>
                                            <td style="text-align:left">{{ number_format($cashAtBankBalance, 2) }}</td>
                                            <td style="text-align:left">Tax Payable</td>
                                            <td style="text-align:left">{{number_format($taxPayable, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left">Accounts Receiveable</td>
                                            <td style="text-align:left">{{ number_format($accountsReceivableBalance, 2) }}</td>
                                            <td style="font-weight:bolder;text-align:left">Total Current Liabilities</td>
                                            <td style="text-align:left;font-weight:bolder">{{ number_format($accountsPayableBalance + $taxPayable, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:left">Inventory</td>
                                            <td style="text-align:left">{{ number_format($inventory, 2) }}</td>
                                            <td style="font-weight:bolder;font-size:20px;text-align:left">Non Current Liabilities</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bolder;text-align:left">Total Current Assets</td>
                                            <td style="text-align:left;font-weight:bolder">{{number_format($cashInHandBalance + $cashAtBankBalance + $accountsReceivableBalance + $inventory, 2)}}</td>
                                            <td style="text-align:left">Loan Payable</td>
                                            <td style="text-align:left">{{number_format($loanPayable, 2)}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bolder;font-size:20px;text-align:left">Non Current Assets</td>
                                            <td></td>
                                            <td style="font-weight:bolder;text-align:left">Total Non Current Liabilities</td>
                                            <td style="text-align:left;font-weight:bolder">{{number_format($loanPayable, 2)}}</td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:left">Furniture And Fixtures</td>
                                            <td style="text-align:left">{{ number_format($FurnitureAndFixtures, 2) }}</td>
                                            <td style="font-weight:bolder;text-align:left">Total Liabilities</td>
                                            <td style="text-align:left;font-weight:bolder">{{ number_format($accountsPayableBalance + $taxPayable + $loanPayable, 2) }}</td>
                                        </tr>

                                         <tr>
                                            <td style="text-align:left">Software</td>
                                            <td style="text-align:left">{{ number_format($software, 2) }}</td>
                                            <td style="font-weight:bolder;font-size:20px;text-align:left">Equity</td>
                                            <td style="text-align:left"></td>
                                        </tr>


                                          <tr>
                                            <td style="text-align:left">Office Equipments</td>
                                            <td style="text-align:left">{{ number_format($officeEquipments, 2) }}</td>
                                            <td style="text-align:left">Owner Equity </td>
                                            <td style="text-align:left">{{ number_format($ownerEquity, 2) }}</td>
                                        </tr>

                                         <tr>
                                            <td style="text-align:left">Surveillance Equipments</td>
                                            <td style="text-align:left">{{ number_format($survellianceEquipments, 2) }}</td>
                                            <td style="text-align:left">Drawings</td>
                                            <td style="text-align:left">{{ number_format($drawing, 2) }}</td>
                                        </tr>

                                         <tr>
                                            <td style="text-align:left;font-weight:bolder;">Total Non-Current Assets</td>
                                            <td style="text-align:left;font-weight:bolder">{{number_format($FurnitureAndFixtures + $software + $officeEquipments + $survellianceEquipments, 2)}}</td>
                                            <td style="text-align:left">Net-Profit Loss</td>
                                            <td style="text-align:left">{{number_format ($net_profit, 2)}}</td>
                                        </tr>

                                          <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:left;font-weight:bolder;">Retained earnings</td>
                                            <td style="text-align:left;font-weight:bolder">{{number_format ($totalRetain, 2)}}</td>
                                        </tr>

                                        <tr>
                                            <td style="text-align:left;font-weight:bolder;">Total Assets</td>
                                            <td style="text-align:left;font-weight:bolder;t">{{number_format($FurnitureAndFixtures + $software + $officeEquipments + $survellianceEquipments + $cashInHandBalance + $cashAtBankBalance + $accountsReceivableBalance + $inventory, 2)}}</td>
                                            <td style="text-align:left;font-weight:bolder;">Total Equity</td>
                                            <td style="text-align:left;font-weight:bolder">{{number_format ($total_equity, 2)}}</td>
                                        </tr>

                                          <tr>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:left;font-weight:bolder;font-size:25px">Total Liabilities and Equity</td>
                                            <td style="text-align:left;font-weight:bolder">{{ number_format($accountsPayableBalance + $taxPayable + $loanPayable + $total_equity, 2) }}</td>
                                        </tr>

                                    </tbody>
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
        document.addEventListener('DOMContentLoaded', () => {
            const removeActionColumn = (table) => {
                const headers = table.querySelectorAll('thead th');
                let actionIdx = -1;
                headers.forEach((th, idx) => {
                    if (th.innerText.trim().toLowerCase() === 'action') actionIdx = idx;
                });
                if (actionIdx > -1) {
                    table.querySelectorAll('thead tr').forEach(row => row.deleteCell(actionIdx));
                    table.querySelectorAll('tbody tr').forEach(row => {
                        if (row.cells.length > actionIdx) row.deleteCell(actionIdx);
                    });
                }
            };

            const printBtn = document.querySelector('.print-saletable');
            const pdfBtn = document.querySelector('.export-salepdf');
            const listTitle = "Balance Sheet";

            if (printBtn) {
                printBtn.addEventListener('click', () => {
                    const tables = document.querySelectorAll('.styled-table');
                    if (!tables.length) return alert('Tables not found!');
                    let html = '';
                    tables.forEach(table => {
                        const clone = table.cloneNode(true);
                        removeActionColumn(clone);
                        html += clone.outerHTML;
                    });
                    const newWin = window.open('', '_blank');
                    newWin.document.write(`
                        <html><head><title>${listTitle}</title>
                        <style>
                            table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                            th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                            h1 { text-align: center; margin-bottom: 20px; }
                            @media print {
                                table { table-layout: auto; font-size: 10pt; width: 100%; }
                                th, td { padding: 4px 6px; word-break: break-word; }
                            }
                            thead { display: table-header-group; }
                            tr { page-break-inside: avoid; page-break-after: auto; }
                        </style></head>
                        <body><h1>${listTitle}</h1>${html}</body></html>
                    `);
                    newWin.document.close();
                    newWin.focus();
                    newWin.print();
                    newWin.close();
                });
            }

            if (pdfBtn) {
                pdfBtn.addEventListener('click', () => {
                    const tables = document.querySelectorAll('.styled-table');
                    if (!tables.length) return alert("Tables not found!");
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = `<h1 style="text-align:center">${listTitle}</h1>`;
                    tables.forEach(table => {
                        const clone = table.cloneNode(true);
                        removeActionColumn(clone);
                        wrapper.appendChild(clone);
                    });
                    document.body.appendChild(wrapper);
                    html2canvas(wrapper).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
                        const pdfWidth = pdf.internal.pageSize.getWidth();
                        const pdfHeight = canvas.height * pdfWidth / canvas.width;
                        pdf.addImage(imgData, 'PNG', 0, 10, pdfWidth, pdfHeight);
                        pdf.save(`${listTitle}.pdf`);
                        document.body.removeChild(wrapper);
                    });
                });
            }
        });
    </script>
</body>

</html>
