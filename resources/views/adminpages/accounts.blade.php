<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addaccount {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addaccount:hover {
        background-color: #45a049;  
    }


.custom-modal.account, 
.custom-modal.accountedit {
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

        <div class="container py-4">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header  d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Accounts List</h4>
                    </div>
                    <div class="card-body">
        
                        <div class="mb-4 d-flex gap-2 flex-wrap">
                            <a href="/admin/add_account" onclick="loadaddaccountPage(); return false;" class="btn btn-success btn-md">
                                Add Account
                            </a>
                           
                        </div>
        
                        <div class="mb-3 d-flex flex-wrap gap-2">
                            <a href="/admin/chart_of_account" onclick="loadaccountPage(); return false;"  class="btn btn-outline-secondary btn-sm btn-filter active" data-target="total">
                                General Accounts
                            </a>
                            <a href="/admin/assets_child" onclick="loadassetchildPage('Asset Accounts'); return false;" class="btn btn-outline-success btn-sm btn-filter" data-target="assets">
                                Asset Accounts
                            </a>                            
                           
                            <a href="/admin/liability_child" onclick="loadliabilitychildPage('Liability Accounts'); return false;" class="btn btn-outline-info btn-sm btn-filter" data-target="liability">
                                Liability Accounts
                            </a>
                            <a href="/admin/equity_child" onclick="loadequityPage('Equity Accounts'); return false;" class="btn btn-outline-warning btn-sm btn-filter" data-target="equity">
                                Equity Accounts
                            </a>
                            <a href="/admin/revenue_child" onclick="loadRevenuePage('Revenue Accounts'); return false;" class="btn btn-outline-danger btn-sm btn-filter" data-target="revenue">
                                Revenue Accounts
                            </a>
                            <a href="/admin/expense_child" onclick="loadexpensePage('Expense Accounts'); return false;" class="btn btn-outline-dark btn-sm btn-filter" data-target="expense">
                                Expense Accounts
                            </a>
                            <a href="/admin/customers_account" onclick="loadcustomersaccountPage('Accounts Receiveable'); return false;" class="btn btn-outline-secondary btn-sm btn-filter" data-target="customers">
                                Customers
                            </a>
                            
                            <a href="/admin/vendor_account" onclick="loadvendorPage('Accounts Payable'); return false;" class="btn btn-outline-secondary btn-sm btn-filter" data-target="accounts">
                                Vendors
                            </a>
                        </div>


                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Account Name</th> 
                                        <th>Created At</th>
                                        {{--<th>Action</th>--}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; @endphp
                                    @foreach($accounts as $account)
                                      <tr class="user-row" id="purchase-{{ $account->id }}">
                                              <td>{{$counter}}</td>
                                              <td>
                                                <img src="{{ Auth::user()->image ? asset('images/' . Auth::user()->image) : '' }}"  alt="Account" width="50" class="rounded">
                                              </td>
                                              <td>{{$account->account_name}}</td>

                                              <td>{{$account->created_at}}</td>                                          
                                              
                                              {{--<td>
                                                  <div class="form-button-action" style="display: flex; gap: 8px; align-items: center;">
                                                    <a data-account-id="{{ $account->id }}" class="btn btn-link btn-primary btn-lg edit-accountttttttt-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                  </div>
                                              </td>--}}
                                              
                                             
                                               
                                          </tr>
                                          @php $counter++; @endphp
                                      @endforeach
                                </tbody>
                            </table>
                        </div>
        
        
                    </div>
                </div>
        
                @include('adminpages.footer')
            </div>
        </div>
        

    



    @include('adminpages.js')
    @include('adminpages.ajax')

    <div style="display:none" class="custom-modal accountedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit account</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="accounteditform">
                    <input type="hidden" id="accountforminput_edit" value=""/>
                    <div class="row mt-5">
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="account_name">Account</label>
                                <input type="text" id="account_name" name="account_name" class="form-control">
                            </div>
                        </div>
                       
                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="accounteditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
       
    function createLoader() {
    const loader = document.createElement('div');
    loader.id = 'loader';
    loader.style.position = 'fixed';
    loader.style.top = '0';
    loader.style.left = '0';
    loader.style.width = '100%';
    loader.style.height = '100%';
    loader.style.backgroundColor = 'rgba(128, 128, 128, 0.6)';
    loader.style.display = 'flex';
    loader.style.alignItems = 'center';
    loader.style.justifyContent = 'center';
    loader.style.zIndex = '9999';

    const spinner = document.createElement('div');
    spinner.style.border = '6px solid #f3f3f3';
    spinner.style.borderTop = '6px solid #3498db';
    spinner.style.borderRadius = '50%';
    spinner.style.width = '50px';
    spinner.style.height = '50px';
    spinner.style.animation = 'spin 0.8s linear infinite';

    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);

    loader.appendChild(spinner);
    document.body.appendChild(loader);
}

function removeLoader() {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.remove();
    }
}
   
      

   
   // get account data
   $(document).on('click', '.edit-accountttttttt-btn', function () {
       var accountId = $(this).data('account-id');
     
       createLoader();
       
       $.ajax({
           url: "{{ route('account.show', '') }}/" + accountId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#accounteditform #accountforminput_edit').val(response.account.id);
                  
                   $('#accounteditform #account_name').val(response.account.account_name);
                 
                   $('.custom-modal.accountedit').fadeIn();
               }
           },
           error: function (xhr) {
            removeLoader();
               Swal.fire({
                   icon: 'error',
                   title: 'Error!',
                   text: 'Failed to fetch details.',
                   confirmButtonText: 'Ok'
               });
           }
       });
   });
   
   
   // Edit account 
   $('#accounteditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var accountId = $('#accountforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('account.update', '') }}/" + accountId,  
           type: "POST",  
           data: formData,
           contentType: false, 
           processData: false, 
           success: function (response) {
            
            removeLoader();
               if (response.success) {
                removeLoader();
                   Swal.fire({
                       icon: 'success',
                       title: 'Updated!',
                       text: response.message || 'Updated successfully.',
                       confirmButtonText: 'Ok'
                   }).then(() => {
                       $('#accounteditform')[0].reset();
                       $('.custom-modal.accountedit').fadeOut();
   
                       const account = $(`a[data-account-id="${accountId}"]`).closest('tr');
                       account.find('td:nth-child(2)').text(response.account.id);
                       account.find('td:nth-child(3)').text(response.account.account_name);
                    
                   });
               } else {
                   Swal.fire({
                       icon: 'error',
                       title: 'Error!',
                       text: response.message || 'An error occurred.',
                       confirmButtonText: 'Ok'
                   });
               }
           },
           error: function (xhr) {
            removeLoader();
               let errors = xhr.responseJSON.errors;
               if (errors) {
                   let errorMessages = Object.values(errors)
                       .map(err => err.join('\n'))
                       .join('\n');
                   Swal.fire({
                       icon: 'error',
                       title: 'Error!',
                       text: errorMessages,
                       confirmButtonText: 'Ok'
                   });
               }
           }
       });
   });
   
   });
   
    $('.closeModal').on('click', function () {
       $('.custom-modal.accountedit').fadeOut();
   });


           </script>

  </body>
</html>
