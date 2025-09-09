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
                        <a href="/admin/chart_of_account" onclick="loadaccountPage(); return false;" 
                               class="btn btn-success btn-md my-2 position-absolute" 
                               data-target="accounts" style="right: 0; top: 0;">
                              Back
                            </a>
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
                               
                                <tbody>
                                    @php $counter = 1; @endphp
                                    @foreach($accounts as $account)
                                      <tr class="user-row" id="purchase-{{ $account->id }}">
                                              <td>{{$counter}}</td>
                                              <td>
                                                <img src="{{ Auth::user()->image ? asset('images/' . Auth::user()->image) : '' }}"  alt="Account" width="50" class="rounded">
                                              </td>
                                              <td>{{$account->sub_head_name}}</td>

                                              <td>
                                                <a href="javascript:void(0)" 
                                                   class="btn btn-outline-info btn-sm btn-filter open-opening-modal"
                                                   data-account-id="{{ $account->id }}"
                                                   data-opening="{{ $account->opening }}">
                                                   Opening<br><span id="opening-value-{{ $account->id }}">{{ $account->opening }}</span>
                                                </a>
                                              </td>
                                              

                                              <td>{{ $account->created_at->format('Y-m-d') }}</td>
                                              <td>
                                                <a class="btn btn-outline-danger btn-sm btn-filter" data-target="revenue">
                                                  @if(Str::contains($account->sub_head_name, '(') && Str::contains($account->sub_head_name, ')'))
                                                    Sub Child
                                                  @else
                                                    Child
                                                  @endif
                                                </a>
                                              </td>
                                              
                                            
                                              
                                              {{--<td>
                                                  <div class="form-button-action" style="display: flex; gap: 8px; align-items: center;">
                                                    <a data-account-id="{{ $account->id }}" class="btn btn-link btn-primary btn-lg edit-account-btn" href="javascript:void(0);" onclick="loadeditaccountPage(this)">
                                                        <i class="fa fa-edit"></i>
                                                    </a>                                                    
                                                    <a data-account-id="{{ $account->id }}" class="btn btn-link btn-danger delaccount mt-2">
                                                        <i class="fa fa-times"></i>                    
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

   
  </body>
</html>
