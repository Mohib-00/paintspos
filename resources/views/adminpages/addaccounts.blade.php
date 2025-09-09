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
                    <form id="accountsssform">     
                      <div class="card-body">
                        <div class="row">
                          <!-- Account Type -->
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="account_type">Account Type</label>
                              <select class="form-select form-control" id="account_type" name="account_type" required>
                                <option>Choose Account</option>
                                @foreach($accounts as $account)
                                  <option value="{{ $account->account_name }}" data-account-id="{{ $account->id }}">
                                    {{ $account->account_name }}
                                  </option>
                                @endforeach                            
                              </select>
                            </div>
                          </div>
                    
                          <!-- Head -->
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <button type="button" class="btn btn-success btn-sm" title="Add New Head" data-bs-toggle="modal" data-bs-target="#addHeadModal">
                                <i class="fas fa-plus"></i>
                              </button>
                              <label for="sub_head_level_1">Head</label>
                              <div class="d-flex align-items-center gap-2">
                                <select class="form-select form-control" id="sub_head_level_1" name="sub_head_level_1" required>
                                  <option>Select Sub Head Level 1</option>
                                  {{-- Options will be populated dynamically using JS --}}
                                </select>
                              </div>
                            </div>
                          </div>
                    
                          <!-- Child -->
                          <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="sub_head_level_2">Child</label>
                              <input class="form-control" type="text" id="sub_head_level_2" name="sub_head_level_2" placeholder="Sub Head Level 2" required>
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

    <!-- Modal -->
<div class="modal fade" id="addHeadModal" tabindex="-1" aria-labelledby="addHeadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="addHeadModalLabel">Add Sub Head Level 1</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="addacccounnttsss">
        <div class="modal-body">

          <div class="mb-3">
            <label for="accountSelect" class="form-label">Select Account</label>
            <select name="head_name" class="form-select" id="accountSelect" required>
              <option>Choose One</option>
              @foreach($accounts as $account)
                <option >{{ $account->account_name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="subHeadName" class="form-label">Sub Head Name</label>
            <input type="text" name="sub_head_name" class="form-control" id="subHeadName" placeholder="Sub Head Name">
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>

    </div>
  </div>
</div>



    @include('adminpages.js')
    @include('adminpages.ajax')
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


   $('#addacccounnttsss').on('submit', function(e) {
  e.preventDefault();

  createLoader();
  $.ajax({
    url: "{{ route('add.account') }}",
    type: 'POST',
    data: $(this).serialize(),
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    success: function(response) {
      removeLoader();
      if (response.success) {
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: response.message,
          confirmButtonText: 'OK',
          showConfirmButton: true
        }).then(() => {
          $('#addacccounnttsss')[0].reset();
          $('#addacccounnttsss').modal('hide'); 
          loadaccountPage(); 
        });
      }
    },
    error: function(xhr) {
      removeLoader();
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Something went wrong. Please try again.',
        confirmButtonText: 'OK',
        showConfirmButton: true
      });
    }
  });
});

  function loadaccountPage() {
    loadPage('/admin/chart_of_account', '/admin/chart_of_account');
  }

});

</script>

<script>
  $('#account_type').on('change', function () {
    let selectedAccountName = $(this).val();

    if (selectedAccountName) {
      $.ajax({
        url: '/get-sub-heads-by-head-name/' + encodeURIComponent(selectedAccountName),
        type: 'GET',
        success: function (data) {
          let $subHeadSelect = $('#sub_head_level_1');
          $subHeadSelect.empty().append('<option>Select Sub Head Level 1</option>');

          $.each(data, function (index, value) {
            $subHeadSelect.append('<option value="' + value + '">' + value + '</option>');
          });
        },
        error: function () {
          console.error('Failed to fetch sub head names.');
        }
      });
    }
  });
</script>

<script>

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



  $('#accountsssform').on('submit', function (e) {
    e.preventDefault();

    let accountType = $('#account_type').val(); 
    let head = $('#sub_head_level_1').val();
    let child = $('#sub_head_level_2').val();

    if (!accountType || !head || !child) {
      Swal.fire({
        icon: 'warning',
        title: 'Missing Fields',
        text: 'Please fill in all fields.'
      });
      return;
    }

    let subHeadNameFormatted = `${head} (${child})`;
    createLoader();
    $.ajax({
      url: '/save-sub-head-name',
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        head_name: accountType,
        sub_head_name: subHeadNameFormatted,
      },
      success: function (response) {
        removeLoader();
        Swal.fire({
          icon: 'success',
          title: 'Saved!',
          text: response.message || 'Sub Head Name saved successfully!',
        });
        $('#accountsssform')[0].reset();
        $('#addHeadModal').modal('hide');
      },
      error: function (xhr) {
        removeLoader();
        console.error(xhr.responseText);
        Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: 'Failed to save Sub Head Name.'
        });
      }
    });
  });
</script>



   
  </body>
</html>
