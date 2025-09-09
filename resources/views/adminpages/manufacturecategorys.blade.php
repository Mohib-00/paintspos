<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addcategory {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addcategory:hover {
        background-color: #45a049;  
    }


.custom-modal.category, 
.custom-modal.categoryedit {
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
                    
                        @if(auth()->user()->ctgry_add != '0')
                        <div class="d-flex align-items-center">
                            <a class="addcategory" >Add category</a>
                        </div>
                        @endif
                    </div>
                    
                    <h1 class="mx-3 list">Category List</h1>

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
                                <th>Image</th>
                                <th>Brand Name</th>
                                <th>Category Name</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($categorys as $category)
                                        <tr class="user-row" id="category-{{ $category->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$category->id}}
                                                <td>
                                                <img height="80" width="80" src="{{ asset('public/images/' . $category->image) }}" alt="Image" >
                                                </td>
                                               
                                                <td id="name">{{$category->brand_name}}</td>  
                                                <td id="heading">{{$category->category_name}}</td>  
                                                <td id="user_id">{{ $category->user->name }}</td>
                                                <td id="slug">{{$category->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                    <a data-category-id="{{ $category->id }}" class="btn btn-link btn-primary btn-lg edit-category-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                
                                                    <a data-category-id="{{ $category->id }}" class="btn btn-link btn-danger delcategory mt-2">
                                                        <i class="fa fa-times"></i>                    
                                                    </a>
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



    <!-- Add category data Modal -->
    <div style="display:none" class="custom-modal category" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Add category</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="manufacturecategoryform">
                    <input type="hidden" id="categoryforminput_add" value=""/>
                    <div class="row mt-5">


                        <div class="col-12">
                            <div class="form-group">
                              <label for="email2">Image</label>
                              <input class="form-control" type="file" id="image" name="image" >
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>
                        

                        <div class="col-12">
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
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <input type="text" id="category_name" name="category_name" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="categoryadd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    


     <!-- Add category edit Modal -->
     <div style="display:none" class="custom-modal categoryedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit category</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="manufacturecategoryeditform">
                    <input type="hidden" id="categoryforminput_edit" value=""/>
                    <div class="row mt-5">
                        
                          <div class="col-12">
                            <div class="form-group">
                              <label for="email2">Image</label>
                              <input class="form-control" type="file" id="image" name="image" >
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>

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
                                <label for="mobile_edit">Category Name</label>
                                <input type="text" id="mobile_edit" name="category_name" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="manufacturecategoryeditform" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
        $('.addcategory').click(function() {
            $('.custom-modal.category').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.category').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addcategory')) {
               $('.custom-modal.category').fadeOut(); 
           }
       });
   });
   
   //to del category
   $(document).on('click', '.delcategory', function() {
       const categoryId = $(this).data('category-id');
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
                   url: '/delete-manufacturecategory',
                   type: 'POST',
                   data: { category_id: categoryId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.addcategory').show();
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
   
   $('#manufacturecategoryform').on('submit', function (e) {
    e.preventDefault();   

    let formData = new FormData(this);
    createLoader();
    $.ajax({
        url: "{{ route('manufacturecategory.store') }}",
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
                    $('#manufacturecategoryform')[0].reset();
                    $('.custom-modal.category').fadeOut();

                    const category = response.category;
                    const created_at = category.created_at; 
                    const user_name = response.user_name;
                    const newRow = `
                        <tr data-category-id="${category.id}">
                            <td>${$('.table tbody tr').length + 1}</td>
                            <td>${category.id}</td>
                            <td><img height="80" width="80" src="{{ asset('public/images/') }}/${category.image}" /></td>
                            <td>${category.brand_name}</td>
                            <td>${category.category_name}</td>
                             <td>${user_name}</td>
                            <td>${created_at}</td>
                            <td>
                                <div class="form-button-action">
                                    <a id="categoryedit" data-category-id="${category.id}" class="btn btn-link btn-primary btn-lg edit-category-btn">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-category-id="${category.id}" class="btn btn-link btn-danger mt-2 delcategory">
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

   
   // get category data
   $(document).on('click', '.edit-category-btn', function () {
       var categoryId = $(this).data('category-id');
       createLoader();
       $.ajax({
           url: "{{ route('manufacturecategory.show', '') }}/" + categoryId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#manufacturecategoryeditform #categoryforminput_edit').val(response.category.id);
                   if (response.category.image) {
                    $('#manufacturecategoryeditform #image').attr('src', "{{ asset('images') }}/" + response.category.image);
                }
                   $('#manufacturecategoryeditform #name_edit').val(response.category.brand_name);
                   $('#manufacturecategoryeditform #mobile_edit').val(response.category.category_name);
                   $('.custom-modal.categoryedit').fadeIn();
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
   
   
   // Edit category 
   $('#manufacturecategoryeditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var categoryId = $('#categoryforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('manufacturecategory.update', '') }}/" + categoryId,  
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
                       $('#manufacturecategoryeditform')[0].reset();
                       $('.custom-modal.categoryedit').fadeOut();
   
                       const category = $(`a[data-category-id="${categoryId}"]`).closest('tr');
                       category.find('td:nth-child(2)').text(response.category.id);
                       category.find('td:nth-child(3) img').attr('src', `/images/${response.category.image}`);
                       category.find('td:nth-child(4)').text(response.category.brand_name);
                       category.find('td:nth-child(5)').text(response.category.category_name);
                       category.find('td:nth-child(6)').text(response.user_name); 
                       category.find('td:nth-child(7)').text(response.category.created_at);
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
       $('.custom-modal.categoryedit').fadeOut();
   });
           </script>
  </body>
</html>
