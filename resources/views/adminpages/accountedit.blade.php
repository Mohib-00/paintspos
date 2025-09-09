<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .user {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .user:hover {
        background-color: #45a049;  
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
                    <div class="card-header">
                        <a class="user" href="/admin/chart_of_account" onclick="loadaccountPage(); return false;">Back</a>
                    </div>
                    <form id="accountseditform">   
                        <input type="hidden" id="account_id" value="{{ $account->id }}" />  
                      <div class="card-body">
                        <div class="row">
                          <!-- Account Type -->
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="account_type">Account Type</label>
                              <select class="form-select form-control" id="head_name" name="head_name" required>
                                <option>Choose Account</option>
                                                      
                              </select>
                            </div>
                          </div>
                    
                          <!-- Head -->
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <button type="button" class="btn btn-success btn-sm hideit" title="Add New Head" id="openAddHeadModal">
                                <i class="fas fa-plus"></i>
                              </button>
                              <label for="sub_head_name">Head</label>
                              <div class="d-flex align-items-center gap-2">
                                <input class="form-control" type="text" id="sub_head_name" name="sub_head_name" placeholder="Sub Head Level 1" required>
                              </div>
                            </div>
                          </div>


                          <!-- Child -->
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="childsub_head_name">Child</label>
                              <input class="form-control" type="text" id="childsub_head_name" name="child_sub_head_name" placeholder="Sub Head Level 2" required>
                              <span id="nameError" class="text-danger"></span>
                            </div>
                          </div>

                        </div>
                      </div>
                    
                      <!-- Submit Button -->
                      <div class="card-action">
                        <button type="submit" class="btn btn-success">Submit</button>
                      </div>
                    </form>
                    
                  </div>
                </div>
              </div>
          </div>
        </div>

        @include('adminpages.footer')
      </div>
    </div>

  
<div class="modal fade" id="modalNoBrackets" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="myforedit">
        <input type="hidden" id="accountId" name="account_id" value="{{ $account->id }}">
      
        <div class="modal-content p-3">
          <div class="mb-3">
            <label for="accountSelect" class="form-label">Select Account</label>
            <select name="head_name" class="form-select" id="accountSelect" required>
              <option value="">Choose One</option>
            </select>
          </div>
      
          <div class="mb-3">
            <label for="subHeadName" class="form-label">Sub Head Name</label>
            <input type="text" name="sub_head_name" class="form-control" id="subHeadName" placeholder="Sub Head Name" required>
          </div>
      
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="submitAccount">Submit</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </form>
      
    </div>
  </div>
  



    @include('adminpages.js')
    @include('adminpages.ajax')

   
  </body>
</html>
