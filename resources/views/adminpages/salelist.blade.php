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


.custom-modal.employee, 
.custom-modal.employeeedit {
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

  
    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
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
        height: auto;
        text-align: center;
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

                        <form action="{{ route('sales.list') }}" method="GET" class="row g-2 p-3 d-flex align-items-end">
                          <div class="col-md-2">
                              <label for="from_date" class="form-label">From Date</label>
                              <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                          </div>
                      
                          <div class="col-md-2">
                              <label for="to_date" class="form-label">To Date</label>
                              <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                          </div>
                      
                          <div class="col-md-2">
                              <label for="user_id" class="form-label">Created By</label>
                              <select class="form-select" name="user_id">
                                  <option value="">Select</option>
                                  @foreach ($saless->unique('user_id') as $sale)
                                      <option value="{{ $sale->user->id }}" 
                                          {{ request('user_id') == $sale->user->id ? 'selected' : '' }}>
                                          {{ $sale->user->name ?? 'Unknown' }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                      
                          <div class="col-md-2">
                              <label for="status" class="form-label">Status</label>
                              <select class="form-select" name="status">
                                  <option value="">Select</option>
                                  <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>complete</option>
                                  <option value="Pending" {{ request('status') == 'pending' ? 'selected' : '' }}>pending</option>
                              </select>
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
                              <button type="submit" class="btn btn-primary w-100">
                                  <i class="fas fa-search"></i> Search
                              </button>
                          </div>
                      </form>
                      
                      
  
                        <div class="card-header d-flex justify-content-between align-items-center">
                          
                          <div>
                              <button class="btn btn-sm btn-outline-primary me-2 print-table" >
                                  <i class="fas fa-print"></i> Print
                              </button>
                      
                              <button class="btn btn-sm btn-outline-danger export-pdf">
                                  <i class="fas fa-file-pdf"></i> PDF
                              </button>
                              <button class="btn btn-sm btn-outline-primary export-excel">
                                <i class="fas fa-file-pdf"></i> Excel
                            </button>
                          </div>
                      
                         
                      </div>
                      
                      <h1 class="mx-3 list">Sale List</h1>
  
                        <div class="card-body">
                          <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                              <thead>
                                  <tr>
                                      <th>#</th>
                                      <th>Id</th>
                                      <th>Invoice#</th>
                                      <th>Customer Name</th> 
                                      <th>Payment Method</th>
                                      <th>Net Amount</th>
                                      <th>Fixed Dis</th>
                                      <th>Discount</th>
                                      <th>Sale Return</th>
                                      <th>Credit</th>
                                      <th>Cash</th>
                                      <th>Created By</th>
                                      <th>Status</th>
                                      <th>Created Date</th>
                                      <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @php
                                      $counter = 1;
                                      $totalNetAmount = 0;
                                      $totalFixedDiscount = 0;
                                      $totalDiscount = 0;
                                      $totalSaleReturn = 0;
                                      $totalCredit = 0;
                                      $totalCash = 0;
                                  @endphp
                                  @foreach($sales as $sale)
                                      <tr class="user-row sale-row" id="sale-{{ $sale->id }}">
                                          <td>{{ $counter }}</td>
                                          <td>{{ $sale->id }}</td>
                                          <td>{{ $sale->ref }}</td>
                                          <td>{{ $sale->customer_name }}</td>
                                          <td>{{ $sale->payment_type ?? 'N/A' }}</td>
                                          <td>{{ $sale->total }}</td>
                                          <td>{{ $sale->fixed_discount }}</td>
                                          <td>{{ $sale->discount }}</td>
                                          <td>{{ $sale->sale_return }}</td>
                                          <td>{{ $sale->sale_type == 'credit' ? $sale->subtotal : '' }}</td>
                                          <td>{{ $sale->sale_type == 'cash' ? $sale->subtotal : '' }}</td>
                                          <td>{{ $sale->user->name ?? 'Unknown' }}</td>
                                          <td>{{ $sale->status ?? 'Pending' }}</td>
                                          <td style="white-space: nowrap;">{{ $sale->created_at->format('d-m-Y ') }}</td>
                                          <td>

                                              <div class="form-button-action" style="display: flex; gap: 8px; align-items: center;">
                                                @if(auth()->user()->sale_update != '0')
                                                 <a data-sale-id="{{ $sale->id }}" onclick="loadeditSalePage(this)" class="btn btn-link btn-primary btn-lg edit-sale-btn">
                                                  <i class="fa fa-edit"></i>
                                                 </a>

                                                  @endif
                                                  <a data-sale-id="{{ $sale->id }}" onclick="loadsaleinvoicePage(this)" class="btn btn-link btn-primary btn-lg invoicepage">
                                                      <i style="color: purple" class="fa fa-eye"></i>
                                                  </a>
                                                  <a data-sale-id="{{ $sale->id }}" onclick="loadsaleprintPage(this)" class="btn btn-link btn-primary btn-lg saleprintpage">
                                                      <i style="color:darkred" class="fas fa-print"></i>
                                                  </a>
                                                  @if(auth()->user()->sale_delete != '0')
                                                  <a data-sale-id="{{ $sale->id }}" class="btn btn-link btn-danger delsale mt-2">
                                                      <i class="fa fa-times"></i>
                                                  </a>
                                                  @endif
                                                  @if ($sale->sale_type == 'credit')
                                                  <a data-sale-id="{{ $sale->id }}" data-customer-name="{{ $sale->customer_name }}" data-subtotal="{{ $sale->subtotal }}" class="btn btn-sm completesale d-inline-flex align-items-center px-2 py-1 text-white" style="background-color: #dc3545;" data-bs-toggle="modal" data-bs-target="#completeSaleModal">
                                                      <i class="fas fa-check-circle me-1"></i> Complete
                                                  </a>
                                                  @endif
                                                  <a data-sale-id="{{ $sale->id }}" onclick="loadsaleReturnPage(this)" class="btn btn-sm salereturn d-inline-flex align-items-center px-2 py-1 text-white" style="background-color: #007bff; white-space: nowrap;">
                                                      <i class="fas fa-undo-alt me-1"></i> Sale Return
                                                  </a>
                                              </div>
                                          </td>
                                      </tr>
                                      @php
                                          $counter++;
                                          $totalNetAmount += $sale->total;
                                          $totalFixedDiscount += $sale->fixed_discount;
                                          $totalDiscount += $sale->discount;
                                          $totalSaleReturn += $sale->sale_return;
                                          $totalCredit += $sale->sale_type == 'credit' ? $sale->subtotal : 0;
                                          $totalCash += $sale->sale_type == 'cash' ? $sale->subtotal : 0;
                                      @endphp
                                  @endforeach
                              </tbody>
                             <tfoot>
    <tr>
        <th colspan="5">Total</th>
        <th style="white-space: nowrap">Rs: {{ number_format($totalNetAmount, 2) }}</th>
        <th style="white-space: nowrap">Rs: {{ number_format($totalFixedDiscount, 2) }}</th>
        <th style="white-space: nowrap">Rs: {{ number_format($totalDiscount, 2) }}</th>
        <th style="white-space: nowrap">Rs: {{ number_format($totalSaleReturn, 2) }}</th>
        <th style="white-space: nowrap">Rs: {{ number_format($totalCredit, 2) }}</th>
        <th style="white-space: nowrap">Rs: {{ number_format($totalCash - $totalSaleReturn, 2) }}</th>
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

    <!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="completeSaleModal" tabindex="-1" aria-labelledby="completeSaleLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="completeSaleForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Complete Sale</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="saleId" name="sale_id">
            <input type="hidden" id="customerName" name="customer_name">
            <input type="hidden" id="subtotal" name="subtotal">
  
            <label for="payment_type" class="form-label">Payment Type</label>
            <select class="form-select" name="payment_type" id="paymentType" required>
              <option value="">Select Type</option>
              <option value="1">Cash In Hand</option>
              <option value="2">Cash At Bank</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  
  


    @include('adminpages.js')
    @include('adminpages.ajax')


    <script>
        document.querySelectorAll('.completesale').forEach(button => {
          button.addEventListener('click', function () {
            document.getElementById('saleId').value = this.dataset.saleId;
            document.getElementById('customerName').value = this.dataset.customerName;
            document.getElementById('subtotal').value = this.dataset.subtotal;
          });
        });
      
        document.getElementById('completeSaleForm').addEventListener('submit', function (e) {
          e.preventDefault();
      
          const formData = new FormData(this);
      
          fetch("{{ route('complete.sale') }}", {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            alert(data.message);
            location.reload(); 
          })
          .catch(err => {
            console.error('Error:', err);
            alert('Something went wrong.');
          });
        });
      </script>
      

    <script>
        $(document).on('click', '.delsale', function(e) {
            e.preventDefault();
    
            var saleId = $(this).data('sale-id'); 
    
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will delete the sale and its associated records!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/saledelete/' + saleId, 
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}', 
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message, 
                                icon: 'success',
                                confirmButtonText: 'OK' 
                            });
    
                            $('[data-sale-id="'+ saleId +'"]').closest('tr').remove();                        
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK' 
                            });
                        }
                    });
                }
            });
        });
    </script>
    

    <script>
    function loadeditSalePage(element) {
    const saleId = element.getAttribute('data-sale-id');
    loadPage(`/admin/edit_sale_list/${saleId}`, `/admin/edit_sale_list/${saleId}`);
    }

    function loadsaleReturnPage(element) {
    const saleReturnId = element.getAttribute('data-sale-id');
    loadPage(`/admin/sale_return/${saleReturnId}`, `/admin/sale_return/${saleReturnId}`);
    }

    </script>
  
  </body>
</html>
