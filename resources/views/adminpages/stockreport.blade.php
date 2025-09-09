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
                        <form method="GET" action="{{ route('stock.search') }}" class="row g-3 p-4">
                            <div class="col-md-2">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="to_date">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>

                             <div class="col-md-2 ">
                              <label for="customer_name" class="form-label">Products</label>
                              <select class="form-select" name="item_name">
                                  <option value="">Select</option>
                                  @foreach ($products as $product)
                                      <option value="{{ $product->item_name }}" 
                                          {{ request('item_name') == $product->item_name ? 'selected' : '' }}>
                                          {{ $product->item_name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="col-md-2 ">
                              <label for="customer_name" class="form-label">Category</label>
                              <select class="form-select" name="category_name">
                                  <option value="">Select</option>
                                  @foreach ($categorys as $category)
                                      <option value="{{ $category->category_name }}" 
                                          {{ request('category_name') == $category->category_name ? 'selected' : '' }}>
                                          {{ $category->category_name }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>


                          <div class="col-md-2">
    <label for="brand_name" class="form-label">Company</label>
    <select class="form-select" name="brand_name">
        <option value="">Select</option>
        @foreach ($componys as $compony)
            <option value="{{ $compony->designation_name }}" 
                {{ request('brand_name') == $compony->designation_name ? 'selected' : '' }}>
                {{ $compony->designation_name }}
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

                        <h1 class="mx-3">Stock Report</h1>

                        <div class="card-body" style="margin-top:-40px">
                            <div class="table-responsive">
                                <table class="styled-table">
                                    <thead>
                                        <tr>
                                            <th style="background: #1a2035;color:white">#</th>
                                            <th style="background: #1a2035;color:white">Code</th>
                                            <th style="background: #1a2035;color:white">Name</th>
                                            <th style="background: #1a2035;color:white">Shade</th>
                                            <th style="white-space: nowrap;background: #1a2035;color:white">Opening Stock</th>
                                            <th style="background: #1a2035;color:white">Amount</th>
                                            
                                            <th style="white-space: nowrap;background: #1a2035;color:white">Purchase Stock</th>
                                            <th style="background: #1a2035;color:white">Amount</th>
                                            <th style="white-space: nowrap;background: #1a2035;color:white">Total Stock</th>
                                            <th style="background: #1a2035;color:white">Amount</th>
                                            <th style="white-space: nowrap;background: #1a2035;color:white">Sale Stock</th>
                                            <th style="background: #1a2035;color:white">Amount</th>
                                            <th style="white-space: nowrap;background: #1a2035;color:white">Sale Return Stock</th>
                                            <th style="background: #1a2035;color:white">Amount</th>
                                            <th style="white-space: nowrap;background: #1a2035;color:white">Available Stock</th>
                                            <th style="background: #1a2035;color:white">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      @php $counter = 1; @endphp
                                      @foreach($products as $product)
                                      @php
                                      $saleItems = \DB::table('sale_items')
                                      ->where('product_name', $product->item_name)
                                      ->get();
                                      $totalPurchaseRate = $saleItems->sum('product_subtotal');
                                      $totalQuantity = $saleItems->sum('product_quantity');
                                      $totalSalePurchaseRate = $saleItems->sum('return_amount');
                                      $totalQuantityReturn = $saleItems->sum('return_qty');

                                      $dealSaleItems = \DB::table('deal_sale_items')
                                      ->where('deal_product_name', $product->item_name)
                                      ->get();
                                      
                                      $dealSaleQuantity = $dealSaleItems->sum('deal_product_quantity');
                                      $dealReturnAmount = $dealSaleItems->sum('return_amount');
                                      $dealReturnQty = $dealSaleItems->sum('return_qty');
                                      $dealSalePurchaseRate = $dealSaleItems->sum(function ($item) {
                                      return ($item->deal_product_retail_rate ?? 0) * ($item->deal_product_quantity ?? 0);
                                      });                                     
                                      $combinedReturnAmount = $totalSalePurchaseRate + $dealReturnAmount;
                                      $combinedReturnQty = $totalQuantityReturn + $dealReturnQty;
                                      $combinedQty = $dealSaleQuantity + $totalQuantity;
                                      $combinedPurchaseRate =  $totalPurchaseRate + $dealSalePurchaseRate
                                      @endphp

                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $product->id ?? 'N/A' }}</td>
                                        <td style="white-space: nowrap">{{ $product->item_name ?? 'N/A' }}</td>
                                        <td style="white-space: nowrap">{{ $product->shade ?? 'N/A' }}</td>
                                        <td>{{ $product->prev_quantity ?? 0 }}</td>
                                        <td>{{ number_format($product->prev_purchase_rate ?? 0, 2) }}</td>  
                                       
                                        <td>{{ $product->quantity ?? 0 }}</td>  
                                         <td>{{ number_format($product->purchase_rate ?? 0, 2) }}</td>   
                                        <td>{{ $product->quantity + $product->prev_quantity ?? 0}}</td>
                                        <td>{{ number_format($product->purchase_rate + $product->prev_purchase_rate ?? 0, 2)  }}</td>
                                        <td>{{$combinedQty}}</td>
                                        <td>{{ number_format($combinedPurchaseRate, 2) }}</td>
                                        <td>{{$combinedReturnQty}}</td>
                                        <td>{{number_format($combinedReturnAmount, 2)}}</td>
                                        <td>{{ $product->quantity + $product->prev_quantity ?? 0}}</td>
                                        <td>{{ number_format($product->purchase_rate + $product->prev_purchase_rate ?? 0, 2)  }}</td>
                                    </tr>

                                    @endforeach
                                       
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
        const listTitle = "Stock Report";

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
        const listTitle = "Stock Report";

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
