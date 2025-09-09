<!DOCTYPE html>
<html lang="en">
<head>
    @include('adminpages.css')
    <style>
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

        .editable {
            cursor: pointer;
            font-weight: bold;
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
                                <form class="row g-3 p-4" method="GET" action="{{ route('dayclose.route') }}">
    <div class="col-md-2">
        <label for="from_date">From Date</label>
        <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date', \Carbon\Carbon::today()->toDateString()) }}">
    </div>
    <div class="col-md-2">
        <label for="to_date">To Date</label>
        <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date', \Carbon\Carbon::today()->toDateString()) }}">
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

                                <h1 class="mx-3 list">Day Close Report</h1>

                                <div class="card-body" style="margin-top:-40px">
                                    <div class="table-responsive">
                                        <table class="styled-table ">
                                            <thead>
                                                <tr>
                                                    <th style="background: #1a2035;color:white">#</th>
                                                    <th style="background: #1a2035;color:white">User</th>
                                                    <th style="background: #1a2035;color:white">Qty</th>
                                                    <th style="background: #1a2035;color:white">Sale</th>
                                                    <th style="background: #1a2035;color:white">Discount</th>
                                                    <th style="background: #1a2035;color:white">Sale Return</th>
                                                    <th style="background: #1a2035;color:white">Net Sale</th>
                                                    <th style="background: #1a2035;color:white">Cash Sale</th>
                                                    <th style="background: #1a2035;color:white">Credit Sale</th>
                                                    <th style="background: #1a2035;color:white">Recovery</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php 
                                                    $counter = 1; 
                                                    $totalItems = 0;
                                                    $totalAmount = 0;
                                                    $totalDiscount = 0;
                                                    $totalReturn = 0;
                                                    $totalSubtotal = 0;
                                                    $totalCashSubtotal = 0;
                                                    $totalCreditSubtotal = 0;
                                                    $totalCashReceipt = 0;
                                                @endphp

                                                @foreach($users as $user)
                                                @php
                                                $totalItems += $user->total_items_today ?? 0;
                                                $totalAmount += $user->total_today ?? 0;
                                                $totalDiscount += $user->discount_today ?? 0;
                                                $totalReturn += $user->sale_return_today ?? 0;
                                                $cashSubtotal = $user->cash_subtotal ?? 0;
                                                $creditSubtotal = $user->credit_subtotal ?? 0;
                                                $totalSubtotal += ($cashSubtotal + $creditSubtotal);
                                                $totalCashSubtotal += $cashSubtotal;
                                                $totalCreditSubtotal += $creditSubtotal;
                                                $totalCashReceipt += $user->cash_receipt_total ?? 0;
                                                @endphp
                                                    <tr>
                                                        <td>{{ $counter++ }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ number_format($user->total_items_today) }}</td>
                                                        <td>{{ number_format($user->total_today) }}</td>
                                                        <td>{{ number_format($user->discount_today) }}</td>
                                                        <td>{{ number_format($user->sale_return_today) }}</td>
                                                        <td>{{ number_format(($user->cash_subtotal + $user->credit_subtotal) ?? 0, 2) }}</td>
                                                        <td>{{ number_format($user->cash_subtotal ?? 0, 2) }}</td>
                                                        <td>{{ number_format($user->credit_subtotal ?? 0, 2) }}</td>
                                                        <td>{{ number_format($user->cash_receipt_total ?? 0, 2) }}</td>
                                                    </tr>
                                                  
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                            <th style="background: #1a2035; color: white;" colspan="2">Total</th>
                                            <th style="background: #1a2035; color: white;">{{ number_format($totalItems) }}</th>
                                            <th style="background: #1a2035; color: white;">{{ number_format($totalAmount, 2) }}</th>
                                            <th style="background: #1a2035; color: white;">{{ number_format($totalDiscount, 2) }}</th>
                                            <th style="background: #1a2035; color: white;">{{ number_format($totalReturn, 2) }}</th>
                                            <th style="background: #1a2035; color: white;">{{ number_format($totalSubtotal, 2) }}</th>
                                            <th style="background: #1a2035; color: white;">{{ number_format($totalCashSubtotal, 2) }}</th>
                                            <th style="background: #1a2035; color: white;">{{ number_format($totalCreditSubtotal, 2) }}</th>
                                            <th style="background: #1a2035; color: white;">{{ number_format($totalCashReceipt, 2) }}</th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                        <table class="styled-table">
                                            <thead>
                                                <tr>
                                                    <th style="background: #1a2035;color:white">Report</th>
                                                    <th style="background: #1a2035;color:white">Amount</th>
                                                    <th style="background: #1a2035;color:white">Net Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>SALE</td>
                                                    <td>Rs:{{ number_format($totalSale, 2) }}</td>
                                                    <td>Rs:{{ number_format($totalSale, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>DISCOUNT</td>
                                                    <td>Rs:{{ number_format($totalDiscount, 2) }}</td>
                                                    <td>Rs:{{ number_format($totalSale - $totalDiscount, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Sale Return</td>
                                                    <td>
                                                        {{ number_format($totalSaleReturn, 2) }}
                                                    </td>
                                                    <td>
                                                      Rs:{{ number_format($netTotal, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Fix Discount</td>
                                                    <td>
                                                       Rs:{{ number_format($totalFixedDiscount, 2) }}
                                                    </td>
                                                    <td>
                                                       Rs:{{ number_format($netTotal - $totalFixedDiscount, 2) }}
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>CREDIT SALE</td>
                                                    <td>
                                                        Rs:{{ number_format($totalCreditSubtotal, 2) }}

                                                    </td>
                                                    <td>
                                                     Rs:{{ number_format(($netTotal - $totalFixedDiscount) - $totalCreditSubtotal, 2) }}
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>CASH RECIEPT</td>
                                                    <td>
                                                        Rs:{{$totalCashReceipt}}
                                                    </td>
                                                    <td>
                                                        Rs:{{ number_format(($netTotal - $totalFixedDiscount) - $totalCreditSubtotal + $totalCashReceipt, 2) }}
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>CASH PAYMENT</td>
                                                    <td>
                                                        Rs:{{ number_format($totalCashPayment, 2) }}
                                                    </td>
                                                    <td>
                                                       Rs:{{ number_format(($netTotal - $totalFixedDiscount) - $totalCreditSubtotal + $totalCashReceipt - $totalCashPayment, 2) }}
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>EXPENSE</td>
                                                    <td>
                                                        Rs:{{ number_format($totalExpense, 2) }}
                                                    </td>
                                                    <td>
                                                       Rs:{{ number_format(($netTotal - $totalFixedDiscount) - $totalCreditSubtotal + $totalCashReceipt - $totalCashPayment - $totalExpense, 2) }}
                                                    </td>
                                                </tr>
                                                 {{--<tr>
                                                    <td>SALARY EXPENSE</td>
                                                    <td>
                                                        Rs:{{ number_format($totalSalariesPaid, 2) }}
                                                    </td>
                                                    <td>
                                                    </td>
                                                </tr>--}}
                                                 <tr>
                                                    <td>DAY CLOSE BALANCE</td>
                                                    <td>
                                                       Rs:{{ number_format(($netTotal - $totalFixedDiscount) - $totalCreditSubtotal + $totalCashReceipt - $totalCashPayment - $totalExpense, 2) }}
                                                    </td>
                                                   
                                                </tr>
                                            </tbody>
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
  function removeActionColumn(clonedTable) {
    const headerCells = clonedTable.querySelectorAll('thead th');
    let actionIndex = -1;

    headerCells.forEach((th, index) => {
      if (th.innerText.trim().toLowerCase() === 'action') {
        actionIndex = index;
      }
    });

    if (actionIndex > -1) {
      clonedTable.querySelectorAll('thead tr').forEach(row => {
        row.deleteCell(actionIndex);
      });

      clonedTable.querySelectorAll('tbody tr').forEach(row => {
        if (row.cells.length > actionIndex) {
          row.deleteCell(actionIndex);
        }
      });
    }
  }

  const printBtn = document.querySelector('.print-saletable');
  const pdfBtn = document.querySelector('.export-salepdf');

  if (printBtn) {
    printBtn.addEventListener('click', function () {
      const tables = document.querySelectorAll('.styled-table');
      const listTitle = "Day Close Report";

      if (tables.length === 0) {
        alert('Tables not found!');
        return;
      }

      let clonedTables = '';
      tables.forEach(table => {
        const clonedTable = table.cloneNode(true);
        removeActionColumn(clonedTable);
        clonedTables += clonedTable.outerHTML;
      });

      const newWin = window.open('', '_blank');
      newWin.document.write(`
        <html>
          <head>
            <title>${listTitle}</title>
            <style>
              table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 20px;
              }
              th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
              }
              h1 {
                text-align: center;
                margin-bottom: 20px;
              }
            </style>
          </head>
          <body>
            <h1>${listTitle}</h1>
            ${clonedTables}
          </body>
        </html>
      `);
      newWin.document.close();
      newWin.focus();
      newWin.print();
      newWin.close();
    });
  }

  if (pdfBtn) {
    pdfBtn.addEventListener('click', function () {
      const tables = document.querySelectorAll('.styled-table');
      const listTitle = "Day Close Report";

      if (tables.length === 0) {
        alert("Tables not found!");
        return;
      }

      let wrapper = document.createElement('div');
      let heading = document.createElement('h1');
      heading.innerText = listTitle;
      heading.style.textAlign = 'center';
      wrapper.appendChild(heading);

      tables.forEach(table => {
        const clonedTable = table.cloneNode(true);
        removeActionColumn(clonedTable);
        wrapper.appendChild(clonedTable);
      });

      document.body.appendChild(wrapper);

      html2canvas(wrapper).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

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
