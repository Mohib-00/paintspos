<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .adddesignation {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .adddesignation:hover {
        background-color: #45a049;  
    }


.custom-modal.designation, 
.custom-modal.designationedit {
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
                    
                         @if(auth()->user()->dgnation_add != '0')
                        <div class="d-flex align-items-center">
                            <a class="adddesignation" >Add designation</a>
                        </div>
                        @endif
                    </div>
                    
                    <h1 class="mx-3 list">Designation List</h1>

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
                                <th>Designation Name</th>
                               
                                <th>Created At</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($designations as $designation)
                                        <tr class="user-row" id="designation-{{ $designation->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$designation->id}}
                                               
                                                <td id="name">{{$designation->designation_name}}</td>  
                                               
                                                <td id="slug">{{$designation->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                    @if(auth()->user()->dgnation_update != '0')
                                                    <a data-designation-id="{{ $designation->id }}" class="btn btn-link btn-primary btn-lg edit-designation-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @endif
                                                
                                                    @if(auth()->user()->dgnation_delete != '0')
                                                    <a data-designation-id="{{ $designation->id }}" class="btn btn-link btn-danger deldesignation mt-2">
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



    <!-- Add designation data Modal -->
    <div style="display:none" class="custom-modal designation" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Add designation</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="designationform">
                    <input type="hidden" id="designationforminput_add" value=""/>
                    <div class="row mt-5">
                        

                        <div class="col-12">
                            <div class="form-group">
                                <label for="designation_name">Designation Name</label>
                                <input type="text" id="designation_name" name="designation_name" class="form-control">
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="designationadd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    


     <!-- Add designation edit Modal -->
     <div style="display:none" class="custom-modal designationedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit designation</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="designationeditform">
                    <input type="hidden" id="designationforminput_edit" value=""/>
                    <div class="row mt-5">
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="designation_name">Designation Name</label>
                                <input type="text" id="designation_name" name="designation_name" class="form-control">
                            </div>
                        </div>
                       
                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="designationeditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
        $('.adddesignation').click(function() {
            $('.custom-modal.designation').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.designation').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.adddesignation')) {
               $('.custom-modal.designation').fadeOut(); 
           }
       });
   });
   
   //to del designation
   $(document).on('click', '.deldesignation', function() {
       const designationId = $(this).data('designation-id');
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
                   url: '/delete-designation',
                   type: 'POST',
                   data: { designation_id: designationId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.adddesignation').show();
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
   
   $('#designationform').on('submit', function (e) {
    e.preventDefault();   

    let formData = new FormData(this);
    createLoader();
    $.ajax({
        url: "{{ route('designation.store') }}",
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
                    $('#designationform')[0].reset();
                    $('.custom-modal.designation').fadeOut();

                    const designation = response.designation;
                    const created_at = designation.created_at; 
                  
                    const newRow = `
                        <tr data-designation-id="${designation.id}">
                            <td>${$('.table tbody tr').length + 1}</td>
                            <td>${designation.id}</td>
                            <td>${designation.designation_name}</td>
                           
                            <td>${created_at}</td>
                            <td>
                                <div class="form-button-action">
                                    <a id="designationedit" data-designation-id="${designation.id}" class="btn btn-link btn-primary btn-lg edit-designation-btn">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-designation-id="${designation.id}" class="btn btn-link btn-danger mt-2 deldesignation">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;

                    $('table tbody').prepend(newRow);
                    $('table tbody tr').each(function (index) {
                        $(this).find('td:first').text(index + 1);
                    });
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

   
   // get designation data
   $(document).on('click', '.edit-designation-btn', function () {
       var designationId = $(this).data('designation-id');
       createLoader();
       $.ajax({
           url: "{{ route('designation.show', '') }}/" + designationId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#designationeditform #designationforminput_edit').val(response.designation.id);
                   if (response.designation.image) {
                       $('#designationeditform #icon_edit').attr('src', "{{ asset('images') }}/" + response.designation.image);
                   }
                   $('#designationeditform #designation_name').val(response.designation.designation_name);
                  
                   $('.custom-modal.designationedit').fadeIn();
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
   
   
   // Edit designation 
   $('#designationeditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var designationId = $('#designationforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('designation.update', '') }}/" + designationId,  
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
                       $('#designationeditform')[0].reset();
                       $('.custom-modal.designationedit').fadeOut();
   
                       const designation = $(`a[data-designation-id="${designationId}"]`).closest('tr');
                       designation.find('td:nth-child(2)').text(response.designation.id);
                       designation.find('td:nth-child(3)').text(response.designation.designation_name);

                       designation.find('td:nth-child(7)').text(response.designation.created_at);
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
       $('.custom-modal.designationedit').fadeOut();
   });
           </script>
  </body>
</html>
