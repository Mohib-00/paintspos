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

                        <form method="GET" action="{{ route('purchases.indexx') }}" class="row g-3 p-4">

                            <div class="col-md-3">
                                <label for="from_date" class="form-label">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            
                            <div class="col-md-3 align-self-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </form>
  
                        <div class="card-header d-flex justify-content-between align-items-center">
                          
                          <div>
                            @if(auth()->user()->pur_add != '0')
                              <a href="/admin/purchase" onclick="loadaddpurchasePage(); return false;" class="btn btn-sm btn-outline-primary me-2" >
                                  <i class="icon-plus"></i> Add Purchase PO
                              </a>
                              @endif
                      
                              <a href="/admin/GRN" onclick="loadgrnPage(); return false;" class="btn btn-sm btn-outline-danger ">
                                  <i class="icon-plus"></i> Add GRN
                              </a>

                              <a href="/admin/payment" onclick="loadpayemntPage(); return false;" class="btn btn-sm btn-outline-primary me-2" >
                                <i class="icon-plus"></i> $Payment
                              </a>

                             
                          </div>
                      
                         
                      </div>
                      
                      <h1 class="mx-3 list">Purchase List</h1>
  
                        <div class="card-body">
                          <div class="table-responsive">
                              <table id="add-row" class="display table table-striped table-hover">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>Purchase #</th>
                                          <th>Invoice No</th> 
                                          <th>Vendor Invoice No</th>
                                          <th>Purchase Type</th>
                                          <th>Vendor Name</th>
                                          <th>Stock Status</th>
                                          <th>Payment Status</th>
                                          <th>Net Amount</th>
                                          <th>Discount</th>
                                          <th>Created By</th>
                                          <th>Created At</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @php $counter = 1; @endphp
                                      @foreach($purchases as $purchase)
                                        <tr class="user-row" id="purchase-{{ $purchase->id }}">
                                                <td>{{$counter}}</td>
                                                <td>Purchase_{{$purchase->id}}</td>
                                                <td>Invoice{{$purchase->id}}</td>
                                                <td>{{$purchase->invoice_no}}</td>

                                               

                                                <td>POS Purchase</td>
                                                <td>{{$purchase->vendors}}</td> 

                                                @php
                                                $status = strtolower($purchase->stock_status);
                                                $bgColor = $status === 'pending' 
                                                    ? 'background-color: #f8d7da; color: #721c24;'   
                                                    : ($status === 'complete' 
                                                        ? 'background-color: #d4edda; color: #155724;' 
                                                        : 'background-color: #e2e3e5; color: #383d41;'); 
                                            @endphp
                                            
                                            <td style="{{ $bgColor }} padding: 2px 8px; border-radius: 12px; font-size: 13px; font-weight: 600; text-align: center;">
                                                {{ ucfirst($status) }}
                                            </td>


                                             @php
                                                $status = strtolower($purchase->payment_status);
                                                $bgColor = $status === 'pending' 
                                                    ? 'background-color: #f8d7da; color: #721c24;'   
                                                    : ($status === 'complete' 
                                                        ? 'background-color: #d4edda; color: #155724;' 
                                                        : 'background-color: #e2e3e5; color: #383d41;'); 
                                            @endphp
                                            
                                            <td style="{{ $bgColor }} padding: 2px 8px; border-radius: 12px; font-size: 13px; font-weight: 600; text-align: center;">
                                                {{ ucfirst($status) }}
                                            </td>
                                            
                                                <td>{{$purchase->net_amount}}</td>
                                                <td>{{$purchase->discount}}</td>
                                                <td>{{$purchase->user->name}}</td>
                                                <td>{{$purchase->created_at}}</td>
                                               
                                                
                                                <td>
                                                    <div class="form-button-action" style="display: flex; gap: 8px; align-items: center;">
                                                        @if(auth()->user()->pur_update != '0')
                                                        @if ($purchase->stock_status !== 'complete')
                                                        <a href="/admin/edit_purchase_list" onclick="loadeditpurchasePage(this); return false;" data-purchase-id="{{ $purchase->id }}" class="btn btn-link btn-primary btn-lg edit-purchase-btn icon-btn">
                                                           <i class="fa fa-edit"></i>
                                                        </a>
                                                        @endif
                                                        @endif

                                                
                                                        <a href="/admin/purchase_invoice" 
                                                           onclick="loadpurchaseinvoicePage(this); return false;" 
                                                           data-purchase-id="{{ $purchase->id }}" 
                                                           class="btn btn-link btn-primary btn-lg purchase-invoice icon-btn">
                                                            <i class="icon-eye"></i>
                                                        </a>
                                                        @if(auth()->user()->pur_delete != '0')
                                                        @if ($purchase->stock_status !== 'complete')
                                                        <a href="javascript:void(0);" 
                                                           data-purchase-id="{{ $purchase->id }}" 
                                                           class="btn btn-link btn-danger delpurchase icon-btn">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                        @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                
                                               
                                                 
                                            </tr>
                                            @php $counter++; @endphp
                                        @endforeach
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
    <script>
        $(document).on('click', '.delpurchase', function(e) {
            e.preventDefault();
    
            var purchaseId = $(this).data('purchase-id');
            var button = $(this);
    
            Swal.fire({
                title: 'Are you sure?',
                text: "This purchase will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
    
                    $.ajax({
                        url: '/purchases/' + purchaseId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
    
                            button.closest('tr').remove();
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Something went wrong while deleting.',
                                'error'
                            );
                        }
                    });
    
                }
            });
        });
    </script>
  
  </body>
</html>
