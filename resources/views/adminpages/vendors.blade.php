<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addvendor {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addvendor:hover {
        background-color: #45a049;  
    }


.custom-modal.vendor, 
.custom-modal.vendoredit {
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
                    
                        @if(auth()->user()->vend_add != '0')
                        <div class="d-flex align-items-center">
                            <a class="addvendor" >Add Vendor</a>
                        </div>
                        @endif
                    </div>
                    
                    <h1 class="mx-3 list">Vendor List</h1>

                      <div class="card-body">
                        <div class="table-responsive">
                          <table
                            id="add-row"
                            class="display table table-striped table-hover"
                          >
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($vendors as $vendor)
                                        <tr class="user-row" id="vendor-{{ $vendor->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$vendor->id}}
                                               
                                                <td id="name">{{$vendor->name}}</td>  
                                                <td id="heading">{{$vendor->mobile}}</td>  
                                                <td id="address">{{$vendor->address}}</td> 
                                                <td id="user_id">{{ $vendor->user->name }}</td>
                                                <td id="slug">{{$vendor->created_at}}</td>
                                                <td>
                                                    @if(auth()->user()->vend_update != '0')
                                                    <div class="form-button-action">
                                                    <a data-vendor-id="{{ $vendor->id }}" class="btn btn-link btn-primary btn-lg edit-vendor-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @endif
                                                
                                                    @if(auth()->user()->vend_delete != '0')
                                                    <a data-vendor-id="{{ $vendor->id }}" class="btn btn-link btn-danger delvendor mt-2">
                                                        <i class="fa fa-times"></i>                    
                                                    </a>
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



    <!-- Add vendor data Modal -->
    <div style="display:none" class="custom-modal vendor" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Add vendor</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="vendorform">
                    <input type="hidden" id="vendorforminput_add" value=""/>
                    <div class="row mt-5">
                        

                        <div class="col-6">
                            <div class="form-group">
                                <label for="name_add">Name</label>
                                <input type="text" id="name_add" name="name" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="mobile_add">Mobile</label>
                                <input type="number" id="mobile_add" name="mobile" class="form-control">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="address_add">Address</label>
                                <input type="text" id="address_add" name="address" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="vendoradd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    


     <!-- Add vendor edit Modal -->
     <div style="display:none" class="custom-modal vendoredit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit vendor</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="vendoreditform">
                    <input type="hidden" id="vendorforminput_edit" value=""/>
                    <div class="row mt-5">
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name_edit">Name</label>
                                <input type="text" id="name_edit" name="name" class="form-control">
                            </div>
                        </div>
                       
                        <div class="col-6">
                            <div class="form-group">
                                <label for="mobile_edit">Mobile</label>
                                <input type="text" id="mobile_edit" name="mobile" class="form-control">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="address_edit">Address</label>
                                <input type="text" id="address_edit" name="address" class="form-control">
                            </div>
                        </div>

                       
                       
                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="vendoreditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
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
   
           $(document).ready(function() {
        $('.addvendor').click(function() {
            $('.custom-modal.vendor').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.vendor').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addvendor')) {
               $('.custom-modal.vendor').fadeOut(); 
           }
       });
   });
   
   //to del vendor
   $(document).on('click', '.delvendor', function() {
       const vendorId = $(this).data('vendor-id');
       const csrfToken = $('meta[name="csrf-token"]').attr('content');
       const row = $(this).closest('tr');  
   
       Swal.fire({
           title: 'Are you sure?',
           text: "Do you want to delete this?",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#d33',
           cancelButtonColor: '#3085d6',
           confirmButtonText: 'Yes, delete!'
       }).then((result) => {
           if (result.isConfirmed) {
            createLoader();
               $.ajaxSetup({
                   headers: { 'X-CSRF-TOKEN': csrfToken }
               });
   
               $.ajax({
                   url: '/delete-vendor',
                   type: 'POST',
                   data: { vendor_id: vendorId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.addvendor').show();
                           row.remove(); 
                           Swal.fire(
                               'Deleted!',
                               response.message,
                               'success'
                           );
                       } else {
                           Swal.fire(
                               'Error',
                               response.message,
                               'error'
                           );
                       }
                   },
                   error: function(xhr) {
                    removeLoader();
                       console.error(xhr);
                       Swal.fire(
                           'Error',
                           'An error occurred while deleting this.',
                           'error'
                       );
                   }
               });
           }
       });
   });
   
  $(document).on('keydown', function(e) {
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        $('#vendorform').submit();  
    }
});

$('#vendorform').on('submit', function (e) {
    e.preventDefault();   

    let formData = new FormData(this);
    createLoader();
    
    $.ajax({
        url: "{{ route('vendor.store') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            removeLoader();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Added!',
                    text: response.message || 'Added successfully.',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#vendorform')[0].reset();
                    $('.custom-modal.vendor').fadeOut();
                });
            }
        },
        error: function (xhr) {
            removeLoader();
            let response = xhr.responseJSON;

            if (response && response.errors) {
                let errorMessages = '';

                $.each(response.errors, function (key, errors) {
                    if (key === 'name') {
                        errorMessages += `<strong>Name Error:</strong> ${errors.join('<br>')}<br>`;
                    } else {
                        errorMessages += errors.join('<br>') + '<br>';
                    }
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessages,  
                    confirmButtonText: 'Ok'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    confirmButtonText: 'Ok'
                });
            }
        }
    });
});


   
   // get vendor data
   $(document).on('click', '.edit-vendor-btn', function () {
       var vendorId = $(this).data('vendor-id');
       createLoader();
       $.ajax({
           url: "{{ route('vendor.show', '') }}/" + vendorId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#vendoreditform #vendorforminput_edit').val(response.vendor.id);
                   if (response.vendor.image) {
                       $('#vendoreditform #icon_edit').attr('src', "{{ asset('images') }}/" + response.vendor.image);
                   }
                   $('#vendoreditform #name_edit').val(response.vendor.name);
                   $('#vendoreditform #mobile_edit').val(response.vendor.mobile);
                   $('#vendoreditform #address_edit').val(response.vendor.address);
                   $('.custom-modal.vendoredit').fadeIn();
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
   
   
   // Edit vendor 
   $('#vendoreditform').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this); 
    var vendorId = $('#vendorforminput_edit').val(); 
    createLoader();

    $.ajax({
        url: "{{ route('vendor.update', '') }}/" + vendorId,  
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            removeLoader();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: response.message || 'Updated successfully.',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#vendoreditform')[0].reset();
                    $('.custom-modal.vendoredit').fadeOut();

                    const vendor = $(`a[data-vendor-id="${vendorId}"]`).closest('tr');
                    vendor.find('td:nth-child(3)').text(response.vendor.name);
                    vendor.find('td:nth-child(4)').text(response.vendor.mobile);
                    vendor.find('td:nth-child(5)').text(response.vendor.address);
                    vendor.find('td:nth-child(6)').text(response.user_name); 
                    vendor.find('td:nth-child(7)').text(response.vendor.created_at);
                });
            }
        },
        error: function (xhr) {
            removeLoader();
            let response = xhr.responseJSON;

            if (response && response.errors) {
                let errorMessages = '';
                
                $.each(response.errors, function (key, errors) {
                    if (key === 'name') {
                        errorMessages += `<strong>Name Error:</strong> ${errors.join('<br>')}<br>`;
                    } else {
                        errorMessages += errors.join('<br>') + '<br>';
                    }
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessages,
                    confirmButtonText: 'Ok'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    confirmButtonText: 'Ok'
                });
            }
        }
    });
});

   
   });
   
    $('.closeModal').on('click', function () {
       $('.custom-modal.vendoredit').fadeOut();
   });
           </script>
  </body>
</html>
