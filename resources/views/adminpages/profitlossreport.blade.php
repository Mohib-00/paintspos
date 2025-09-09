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
                       <form class="row g-3 p-4" method="GET" action="{{ route('profitlossreport') }}">
                            <div class="col-md-2">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
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

                        <h1 class="mx-3">Profit & Loss Report</h1>

                        <div class="card-body" style="margin-top:-40px">
                            <div class="table-responsive">
                                <table class="styled-table">
                                    <thead>
                                        <tr>
                                            <th style="background: #1a2035;color:white">#</th>
                                            <th style="background: #1a2035;color:white">Name</th>
                                            <th style="background: #1a2035;color:white">Shade</th>
                                            <th style="white-space: nowrap;background: #1a2035;color:white">Sale Rate</th>
                                            <th style="background: #1a2035;color:white">Quantity</th>
                                            <th style="background: #1a2035;color:white">Total</th>
                                            <th style="white-space: nowrap;background: #1a2035;color:white">Purchase Rate</th>
                                            <th style="white-space: nowrap;background: #1a2035;color:white">Profit</th>
                                            <th style="white-space: nowrap;background: #1a2035;color:white">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     @php
    $counter = 1;
    $totalRate = 0;
    $totalQty = 0;
    $total = 0;
    $totalPurchase = 0;
    $profit = 0;
@endphp

@foreach ($saleItems as $item)
    @php
        $subtotal = $item->product_rate * $item->product_quantity;

        $displayRetailRate = $item->product_rate;
        if (!empty($item->deal_items)) {
            $displayRetailRate = $item->product_rate * $item->product_quantity;
        }

        $purchaseTotal = $item->purchase_rate;
        if (!empty($item->deal_items)) {
            $purchaseTotal = $item->purchase_rate * $item->product_quantity;
        }

        $adjustedPurchaseRate = $purchaseTotal;

        if (!empty($item->return_qty) && $item->return_qty > 0) {
            $purchaseRatePerUnit = $purchaseTotal / $item->product_quantity;
            $adjustedPurchaseRate -= $purchaseRatePerUnit * $item->return_qty;

            if ($adjustedPurchaseRate < 0) {
                $adjustedPurchaseRate = 0;
            }
        }


          $netSubtotal = $subtotal - ($item->return_amount ?? 0);
          $finalAmount = $netSubtotal;

        $totalRate += $displayRetailRate ?? 0;
        $totalQty += $item->product_quantity - ($item->return_qty ?? 0);
        $total += $subtotal - ($item->return_amount ?? 0);
        $totalPurchase += $adjustedPurchaseRate;
        $profit += ($subtotal - ($item->return_amount ?? 0)) - $adjustedPurchaseRate;


        $shade = \App\Models\Product::where('item_name', $item->product_name)->value('shade') ?? 'No Shade';


    @endphp

    <tr>
        <td>{{ $counter++ }}</td>
        <td>
            {{ $item->product_name }}
        </td>
         <td>
            {{ $shade }}
        </td>
        <td>{{ number_format($displayRetailRate, 2) }}</td>
        <td>{{ $item->product_quantity - ($item->return_qty ?? 0) }}</td>
        <td>{{ number_format($finalAmount, 2) }}</td>
        <td>{{ number_format($adjustedPurchaseRate, 2) }}</td>
        <td>{{ number_format($finalAmount - $adjustedPurchaseRate, 2) }}</td>
        @php
            $hasDeal = \DB::table('deal_sale_items')
                          ->where('sale_item_id', $item->id)
                          ->exists();
        @endphp

       
            <td>
               @if ($hasDeal)
                <a href="javascript:void(0);" data-item-id="{{ $item->id }}"class="btn btn-link btn-primary btn-lg dealitemsopen icon-btn">
                  <i class="icon-eye"></i>
                </a>
                @endif
            </td>
        
    </tr>
@endforeach



<tfoot>
<tr>
    <th colspan="3" style="background-color:#1a2035; color: #fff;">Total Profit</th>
    <th style="background-color:#1a2035; color: #fff;">{{ number_format($totalRate, 2) }}</th>
    <th style="background-color:#1a2035; color: #fff;">{{ $totalQty }}</th>
    <th style="background-color:#1a2035; color: #fff;">{{ number_format($total, 2) }}</th>
    <th style="background-color:#1a2035; color: #fff;">{{ number_format($totalPurchase, 2) }}</th>
    <th style="background-color:#1a2035; color: #fff;">{{ number_format(abs($profit), 2) }}</th>
     <th style="background-color:#1a2035; color: #fff;"></th>
</tr>
</tfoot>


                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


 
<div class="container my-4">
  <div class="row g-4">


    <!-- Second Table -->
   @php
    $cashSales = 0;
    $salesReturn = 0;
    $creditSales = 0;
    $costOfGoodsSold = 0;
    $otherIncome = 0;
    $discountAvailed = 0;

    foreach ($revenueAccounts as $account) {
        switch ($account->sub_head_name) {
            case 'Cash Sales':
                $cashSales = $account->netRevenue;
                break;
            case 'Sales Return':
                $salesReturn = $account->netRevenue;
                break;
            case 'Credit Sales':
                $creditSales = $account->netRevenue;
                break;
            case 'Cost Of Goods Sold':
                $costOfGoodsSold = $account->netRevenue;
                break;
            case 'Other Income':
                $otherIncome = $account->netRevenue;
                break;
            case 'Discount Availed':
                $discountAvailed = $account->netRevenue;
                break;
        }
    }

    $netTotal = $cashSales - $salesReturn + $creditSales - $costOfGoodsSold + $otherIncome + $discountAvailed;
@endphp

<div class="col-12 col-md-6">
    <table class="table table-hover table-bordered align-middle mb-0" style="border-radius: 10px; overflow: hidden;">
        <thead>
            <tr>
                <th style="background-color: #1a2035; color: #fff;">Account</th>
                <th style="background-color: #1a2035; color: #fff;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($revenueAccounts as $revenueAccount)
            <tr style="background-color: #ffffff;">
                <td>{{ $revenueAccount->sub_head_name }}</td>
                <td>{{ number_format($revenueAccount->netRevenue, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="background-color:#1a2035; color: #fff;">Total</th>
                <th style="background-color:#1a2035; color: #fff;">{{ number_format($netTotal, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>


    @php
    $difference = $netTotal - $totalFinal;
@endphp

<div class="col-12 col-md-6">
    <table class="table table-hover table-bordered align-middle mb-0" style="border-radius: 10px; overflow: hidden;">
      <thead>
        <tr>
          <th style="background-color:#1a2035; color: #fff;">Account</th>
          <th style="background-color: #1a2035; color: #fff;">Amount</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($subHeadNames as $account)
        <tr style="background-color: #ffffff;">
          <td>{{ $account->sub_head_name }}</td>
          <td>{{ number_format($account->finalTotal, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
       <tfoot>
        <tr>
          <th style="background-color:#1a2035; color: #fff;">Total</th>
          <th style="background-color:#1a2035; color: #fff;">{{ number_format($totalFinal, 2) }}</th>
        </tr>
        <tr>
          <th style="background-color:#1a2035; color: #fff;">Net Revenue - Expenses</th>
          <th style="background-color:#1a2035; color: #fff;">{{ number_format($difference, 2) }}</th>
        </tr>
      </tfoot>
    </table>
</div>


  </div>
</div>


<div id="dealItemsModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius: 1rem; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); overflow: hidden;">
            <div class="modal-header" style="background: #1A2035; color: white; padding: 1rem 1.5rem;">
                <h5 class="modal-title" style="font-weight: bold; font-size: 1.25rem;">ðŸ“¦ Deal Items</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto; padding: 1rem;">
                <table id="dealItemsTable" class="table table-hover table-striped table-bordered align-middle" style="font-size: 0.95rem;">
                    <thead class="table-light text-center">
                        <tr style="background-color: #f8f9fa;">
                            <th>ID</th>
                            <th>Deal Name</th>
                            <th>Product Name</th>
                            <th>Qty</th>
                            <th>Purchase Rate</th>
                            <th>Retail Rate</th>
                            <th>Return Qty</th>
                            <th>Return Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" style="cursor: default;">
                    </tbody>
                </table>
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
    $(document).on('click', '.dealitemsopen', function () {
        let saleItemId = $(this).data('item-id');

        $.ajax({
            url: '/deal-items/' + saleItemId,
            method: 'GET',
            success: function (data) {
                let tbody = $('#dealItemsTable tbody');
                tbody.empty();

                if (data.length === 0) {
                    tbody.append('<tr><td colspan="4">No deal items found</td></tr>');
                } else {
                    data.forEach(function (item) {
                        tbody.append(`
                            <tr>
                                <td>${item.id}</td>
                                <td>${item.deal_name}</td>
                                <td>${item.deal_product_name}</td>
                                <td>${item.deal_product_quantity}</td>
                                <td>${item.deal_product_purchase_rate}</td>
                                <td>${item.deal_product_retail_rate}</td>
                                <td>${item.return_qty ?? '0'}</td>
                                <td>${item.return_amount ?? '0'}</td>
                            </tr>
                        `);
                    });
                }

                $('#dealItemsModal').modal('show');
            },
            error: function () {
                alert('Failed to load deal items');
            }
        });
    });
</script>


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
        const listTitle = "Profit & Loss Report";

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
                @media print {
                  table {
                    table-layout: auto !important;
                    font-size: 10pt !important;
                    width: 100% !important;
                  }
                  th {
                    padding: 4px 6px !important;
                    white-space: nowrap !important;
                  }
                  td {
                    padding: 4px 6px !important;
                    white-space: normal !important;
                    word-break: break-word !important;
                  }
                }
                thead {
                  display: table-header-group;
                }
                tr {
                  page-break-inside: avoid;
                  page-break-after: auto;
                }
                th, td {
                  border: 1px solid #000 !important;
                  text-align: center;
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
        const listTitle = "Profit & Loss Report";

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
