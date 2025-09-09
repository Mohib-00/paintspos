<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addsub {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addsub:hover {
        background-color: #45a049;  
    }


.custom-modal.sub, 
.custom-modal.subedit {
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
                        </div>
                    
                        @if(auth()->user()->subctgry_add != '0')
                        <div class="d-flex align-items-center">
                            <a class="addsub" >Add sub</a>
                        </div>
                        @endif
                    </div>
                    
                    <h1 class="mx-3 list">sub List</h1>

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
                                <th>Brand Name</th>
                                <th>Category Name</th>
                                <th>Sub Category Name</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($subs as $sub)
                                        <tr class="user-row" id="sub-{{ $sub->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$sub->id}}
                                               
                                                <td id="name">{{$sub->brand_name}}</td>  
                                                <td id="heading">{{$sub->category_name}}</td>  
                                                <td id="heading">{{$sub->subcategory_name}}</td> 
                                                <td id="user_id">{{ $sub->user->name }}</td>
                                                <td id="slug">{{$sub->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                    @if(auth()->user()->subctgry_update != '0')
                                                    <a data-sub-id="{{ $sub->id }}" class="btn btn-link btn-primary btn-lg edit-sub-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @endif
                                                
                                                    @if(auth()->user()->subctgry_delete != '0')
                                                    <a data-sub-id="{{ $sub->id }}" class="btn btn-link btn-danger delsub mt-2">
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



    <!-- Add sub data Modal -->
    <div style="display:none" class="custom-modal sub" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Add sub</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="subform">
                    <input type="hidden" id="subforminput_add" value=""/>
                    <div class="row mt-5">
                        

                        <div class="col-6">
                            <div class="form-group">
                                <label for="brand_name">Brand Name</label>
                                <select
                                class="form-select form-control"
                                id="defaultSelect" name="brand_name"
                              >
                              @foreach ($brands as $brand )
                              <option>{{$brand->designation_name}}</option>
                              @endforeach
                               
                               
                              </select>                            
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="sub_name"> Category Name</label>
                                <select
                                class="form-select form-control"
                                id="defaultSelect" name="category_name"
                              >
                              @foreach ($categorys as $category )
                              <option>{{$category->category_name}}</option>
                              @endforeach
                               
                               
                              </select>                               
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="subcategory_name">Sub Category Name</label>
                                <input type="text" id="subcategory_name" name="subcategory_name" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="subadd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    


     <!-- Add sub edit Modal -->
     <div style="display:none" class="custom-modal subedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit sub</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="subeditform">
                    <input type="hidden" id="subforminput_edit" value=""/>
                    <div class="row mt-5">
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name_edit">Brand Name</label>
                                <select
                                          class="form-select form-control"
                                          id="name_edit" name="brand_name"
                                        >
                                        @foreach ($brands as $brand )
                                        <option>{{$brand->designation_name}}</option>
                                        @endforeach
                                          
                                        </select>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="sub_name"> Category Name</label>
                                <select
                                class="form-select form-control"
                                id="category_name" name="category_name"
                              >
                              @foreach ($categorys as $category )
                              <option>{{$category->category_name}}</option>
                              @endforeach
                               
                               
                              </select>                               
                            </div>
                        </div>
                       
                        <div class="col-6">
                            <div class="form-group">
                                <label for="subcategory_name">Sub Category Name</label>
                                <input type="text" id="subcategory_name" name="subcategory_name" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="subeditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
        $('.addsub').click(function() {
            $('.custom-modal.sub').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.sub').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addsub')) {
               $('.custom-modal.sub').fadeOut(); 
           }
       });
   });
   
   //to del sub
   $(document).on('click', '.delsub', function() {
       const subId = $(this).data('sub-id');
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
                   url: '/delete-sub',
                   type: 'POST',
                   data: { sub_id: subId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.addsub').show();
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
   
   $('#subform').on('submit', function (e) {
    e.preventDefault();   

    let formData = new FormData(this);
    createLoader();
    $.ajax({
        url: "{{ route('sub.store') }}",
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
                    $('#subform')[0].reset();
                    $('.custom-modal.sub').fadeOut();

                    const sub = response.sub;
                    const created_at = sub.created_at; 
                    const user_name = response.user_name;
                    const newRow = `
                        <tr data-sub-id="${sub.id}">
                            <td>${$('.table tbody tr').length + 1}</td>
                            <td>${sub.id}</td>
                            <td>${sub.brand_name}</td>
                            <td>${sub.category_name}</td>
                              <td>${sub.subcategory_name}</td>
                             <td>${user_name}</td>
                            <td>${created_at}</td>
                            <td>
                                <div class="form-button-action">
                                    <a id="subedit" data-sub-id="${sub.id}" class="btn btn-link btn-primary btn-lg edit-sub-btn">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-sub-id="${sub.id}" class="btn btn-link btn-danger mt-2 delsub">
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

   
   // get sub data
   $(document).on('click', '.edit-sub-btn', function () {
       var subId = $(this).data('sub-id');
       createLoader();
       $.ajax({
           url: "{{ route('sub.show', '') }}/" + subId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#subeditform #subforminput_edit').val(response.sub.id);
                  
                   $('#subeditform #name_edit').val(response.sub.brand_name);
                   $('#subeditform #category_name').val(response.sub.category_name);
                   $('#subeditform #subcategory_name').val(response.sub.subcategory_name);
                   $('.custom-modal.subedit').fadeIn();
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
   
   
   // Edit sub 
   $('#subeditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var subId = $('#subforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('sub.update', '') }}/" + subId,  
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
                       $('#subeditform')[0].reset();
                       $('.custom-modal.subedit').fadeOut();
   
                       const sub = $(`a[data-sub-id="${subId}"]`).closest('tr');
                       sub.find('td:nth-child(2)').text(response.sub.id);
                       sub.find('td:nth-child(3)').text(response.sub.brand_name);
                       sub.find('td:nth-child(4)').text(response.sub.category_name);
                       sub.find('td:nth-child(5)').text(response.sub.subcategory_name);
                       sub.find('td:nth-child(6)').text(response.user_name); 
                       sub.find('td:nth-child(7)').text(response.sub.created_at);
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
       $('.custom-modal.subedit').fadeOut();
   });
           </script>
  </body>
</html>
