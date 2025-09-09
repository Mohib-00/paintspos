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
    .add-voucher-btn {
    background-color: #007bff;
    border: none;
    padding: 8px 16px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .add-voucher-btn:hover {
    background-color: #0056b3;
  }

  .add-voucher-btn i {
    font-size: 14px;
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

                        <form action="{{ route('search.vouchers') }}" method="GET" class="row g-2 p-3 d-flex align-items-end">
                          <div class="col-md-2">
                              <label for="from_date" class="form-label">From Date</label>
                              <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                          </div>
                      
                          <div class="col-md-2">
                              <label for="to_date" class="form-label">To Date</label>
                              <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                          </div>
                      
                      
                          <div class="col-md-2">
                              <button type="submit" class="btn btn-primary w-100">
                                  <i class="fas fa-search"></i> Search
                              </button>
                          </div>
                      </form>
                      
                      
  
                      <div class="card-header d-flex justify-content-between align-items-center">
  
                        <div class="d-flex">
                          <button class="btn btn-sm btn-outline-primary me-2 print-table">
                            <i class="fas fa-print"></i> Print
                          </button>
                      
                          <button class="btn btn-sm btn-outline-danger me-2 export-pdf">
                            <i class="fas fa-file-pdf"></i> PDF
                          </button>
                      
                          <button class="btn btn-sm btn-outline-primary me-2 export-excel">
                            <i class="fas fa-file-excel"></i> Excel
                          </button>
                        </div>
                      
                       <div class="d-flex gap-2">
                        @if(auth()->user()->vo_add != '0')
                          <a href="/admin/add_voucher" onclick="loadvoucherPage(); return false;" class="btn btn-primary add-voucher-btn">
                            <i class="fa fa-plus me-1"></i> Add Voucher
                          </a>
                          <a href="/admin/jv_voucher" onclick="loadjvvoucherPage(); return false;" class="btn btn-success add-voucher-btn">
                            <i class="fa fa-plus me-1"></i> Add JV
                          </a>
                          @endif
                        </div>

                      </div>
                    
  
                        <div class="card-body">
                          <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                              <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Id</th>
                                    <th>Location</th>
                                    <th>Narration</th>
                                    <th>Voucher Type</th> 
                                    <th>Debit Account</th>
                                    <th>Credit Account</th>
                                    <th>Total Amount</th>
                                    <th>Veoucher Status</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                @php 
                                $counter = 1; 
                                @endphp
                               
                                  @foreach($vouchers as $voucher)
                                   @php
        $debitAccountIds = [];
        $creditAccountIds = [];

        $grnAccounts = \App\Models\GrnAccount::where('voucher_id', $voucher->id)->get();

        foreach ($grnAccounts as $grn) {
            if ($grn->debit !== null && $grn->debit > 0) {
                $debitAccountIds[] = $grn->vendor_account_id;
            } elseif ($grn->vendor_net_amount !== null && $grn->vendor_net_amount > 0) {
                $creditAccountIds[] = $grn->vendor_account_id;
            }
        }

        $debitHeadNames = \App\Models\AddAccount::whereIn('id', $debitAccountIds)->pluck('sub_head_name')->toArray();
        $creditHeadNames = \App\Models\AddAccount::whereIn('id', $creditAccountIds)->pluck('sub_head_name')->toArray();

        $debitList = implode(', ', $debitHeadNames);
        $creditList = implode(', ', $creditHeadNames);
    @endphp
                                      <tr class="user-row sale-row" id="voucher-row-{{ $voucher->id }}">
                                          <td>{{ $counter }}</td>
                                          <td>{{ $voucher->id }}</td>
                                          <td>{{ $voucher->receiving_location }}</td>
                                          <td>{{ $voucher->remarks }}</td>
<td style="
    @if($voucher->voucher_type === 'JV') color: green;
    @elseif($voucher->voucher_type === 'Cash Payment') color: red;
    @elseif($voucher->voucher_type === 'Cash Receipt') color: blue;
    @endif
">
    {{ $voucher->voucher_type }}
</td>
                                          <td>{{  $debitList  }}</td>
                                          <td>{{ $creditList }}</td>
                                          <td>{{ $voucher->totalAmount }}</td>
                                          <td>{{ $voucher->voucher_status }}</td>
                                          <td>{{ $voucher->user->name }}</td>
                                          <td style="white-space: nowrap;">{{ $voucher->created_at->format('d-m-Y ') }}</td>
                                          <td>
                                              <div class="form-button-action" style="display: flex; gap: 8px; align-items: center;">
                                                @if($voucher->voucher_type === 'JV')
                                                  {{--<a data-voucher-id="{{ $voucher->id }}" onclick="loadjveditvoucherPage(this)" class="btn btn-link btn-primary btn-lg editjv-voucher-btn">
                                                  <i class="fa fa-edit"></i>
                                                  </a>--}}
                                                @else
                                                @if(auth()->user()->vo_update != '0')
                                                  <a data-voucher-id="{{ $voucher->id }}" onclick="loadeditvoucherPage(this)" class="btn btn-link btn-primary btn-lg edit-voucher-btn">
                                                  <i class="fa fa-edit"></i>
                                                  </a>
                                                @endif
                                                @endif


                                                  <a data-voucher-id="{{ $voucher->id }}" onclick="loadvoucheritemsPage(this)" class="btn btn-link btn-primary btn-lg invoicepage">
                                                      <i style="color: purple" class="fa fa-eye"></i>
                                                  </a>
                                               
                                                  @if(auth()->user()->vo_delete != '0')
                                                  <a data-voucher-id="{{ $voucher->id }}" class="btn btn-link btn-danger delvoucher mt-2">
                                                      <i class="fa fa-times"></i>
                                                  </a>
                                                  @endif

                                                   

                                                  
                                              </div>
                                          </td>
                                      </tr>
                                     
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
      $(document).on('click', '.delvoucher', function (e) {
    e.preventDefault();

    let voucherId = $(this).data('voucher-id');
    let url = `/voucher/${voucherId}`;
    let rowSelector = `#voucher-row-${voucherId}`;

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        $(rowSelector).remove();

                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function (xhr) {
                    Swal.fire(
                        'Error!',
                        xhr.responseJSON.message || 'Something went wrong!',
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
