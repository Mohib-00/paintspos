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
                                <form method="GET" action="{{ route('sales.search') }}" class="row g-3 p-4">
                                    <div class="col-md-2">
                                        <label for="from_date">From Date</label>
                                        <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="to_date">To Date</label>
                                        <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-2">
                              <label for="customer_name" class="form-label">All Customers</label>
                              <select class="form-select" name="customer_name">
                                  <option value="">Select</option>
                                  @foreach ($saless->unique('customer_name') as $sale)
                                      <option value="{{ $sale->customer_name }}" 
                                          {{ request('customer_name') == $sale->customer_name ? 'selected' : '' }}>
                                          {{ $sale->customer_name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="col-md-2">
                              <label for="employee" class="form-label">User</label>
                              <select class="form-select" name="employee">
                                  <option value="">Select</option>
                                  @foreach ($saless->unique('employee') as $sale)
                                      <option value="{{ $sale->employee }}" 
                                          {{ request('employee') == $sale->employee ? 'selected' : '' }}>
                                          {{ $sale->employee ?? 'Unknown' }}
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

                                <h1 class="mx-3 list">Sale Report</h1>

                                <div class="card-body" style="margin-top:-40px">
                                    <div class="table-responsive">
                                        <table class="styled-table ">
                                            <thead>
                                                <tr>
                                                    <th style="background: #1a2035;color:white">#</th>
                                                    <th style="background: #1a2035;color:white">User</th>
                                                    <th style="background: #1a2035;color:white">Customer</th>
                                                    <th style="background: #1a2035;color:white">Qty</th>
                                                    <th style="background: #1a2035;color:white">Gross Amount</th>
                                                    <th style="background: #1a2035;color:white">Discount</th>
                                                    <th style="background: #1a2035;color:white">Fixed Discount</th>
                                                    <th style="background: #1a2035;color:white">Subtotal</th>
                                                    <th style="background: #1a2035;color:white">View</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php 
                                                    $counter = 1; 
                                                    $totalNetAmount = $totalFixedDiscount = $totalDiscount = $totalSaleReturn = $totalCredit = $totalCash = 0;
                                                @endphp

                                                @foreach($sales as $sale)
                                                    <tr>
                                                        <td>{{ $counter++ }}</td>
                                                        <td>{{ $sale->employee }}</td>
                                                        <td>{{ $sale->customer_name }}</td>
                                                        <td>{{ $sale->total_items }}</td>
                                                        <td>{{ $sale->total }}</td>
                                                        <td>{{ $sale->discount }}</td>
                                                        <td>{{ $sale->fixed_discount }}</td>
                                                        <td>{{ $sale->subtotal }}</td>
                                                        <td>
                                                        <a data-saledetailsPage-id="{{ $sale->id }}" onclick="loadsaledetailsPage(this)" class="btn btn-link btn-primary btn-lg saledetailspage">
                                                           <i style="color: purple" class="fa fa-eye"></i>
                                                        </a>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $totalNetAmount += $sale->total;
                                                        $totalFixedDiscount += $sale->fixed_discount;
                                                        $totalDiscount += $sale->discount;
                                                        $totalSaleReturn += $sale->sale_return;
                                                        $totalCash += $sale->subtotal;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th style="background: #1a2035;color:white" colspan="4">Total</th>
                                                    <th style="background: #1a2035;color:white">{{ number_format($totalNetAmount, 2) }}</th>
                                                    <th style="background: #1a2035;color:white">{{ number_format($totalDiscount, 2) }}</th>
                                                    <th style="background: #1a2035;color:white">{{ number_format($totalFixedDiscount, 2) }}</th>
                                                    <th style="background: #1a2035;color:white">{{ number_format($totalCash, 2) }}</th>
                                                    <th style="background: #1a2035;color:white"></th>
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
                                                    <td>Net Sale</td>
                                                    <td>Rs: {{ number_format($totalCash, 2) }}</td>
                                                    <td>Rs: {{ number_format($totalCash, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Sale Return</td>
                                                    <td>Rs: {{ number_format($totalSaleReturn, 2) }}</td>
                                                    <td>Rs: {{ number_format($totalCash - $totalSaleReturn, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Credit</td>
                                                    <td>
                                                        Rs: 
                                                        @php
                                                            $totalCreditSubtotal = $sales->where('sale_type', 'credit')->sum('subtotal');
                                                        @endphp
                                                        {{ number_format($totalCreditSubtotal, 2) }}
                                                    </td>
                                                    <td>
                                                        Rs: {{ number_format(abs(($totalCash - $totalSaleReturn) - $totalCreditSubtotal), 2) }}
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
      const listTitle = "Sales Report";

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
      const listTitle = "Sales Report";

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
