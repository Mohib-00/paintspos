<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addrawmaterial {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addrawmaterial:hover {
        background-color: #45a049;  
    }

    .custom-modal.rawmaterial, 
    .custom-modal.rawmaterialedit {
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        overflow: auto;
    }

    .modal-dialog {
        width: 60vw;        
        max-width: 700px;   
        animation: slideDown 0.5s ease;
        margin: 5% auto;    
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 100%;
        height: auto;
        text-align: center;
    }

    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    @keyframes slideDown {
        0% { transform: translateY(-50px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
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
                    
                        @if(auth()->user()->rawmaterial_add != '0')
                        <div class="d-flex align-items-center">
                            <a class="addrawmaterial" >Add rawmaterial</a>
                        </div>
                        @endif
                    </div>
                    
                    <h1 class="mx-3 list">rawmaterial List</h1>

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
                                <th>Category</th>
                                <th>Item Name</th>
                                <th>Purchase Rate</th>
                                <th>Quantity</th>
                                <th>Created At</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($rawmaterials as $rawmaterial)
                                        <tr class="user-row" id="rawmaterial-{{ $rawmaterial->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$rawmaterial->id}}
                                               
                                                <td id="name">{{$rawmaterial->brand_name}}</td>  
                                                <td id="name">{{$rawmaterial->category_name}}</td> 
                                                <td id="heading">{{$rawmaterial->item_name}}</td> 
                                                <td id="heading">{{$rawmaterial->purchase_rate}}</td> 
                                                <td id="quantity">{{$rawmaterial->quantity}}</td>
                                                
                                                <td id="slug">{{$rawmaterial->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                    <a data-rawmaterial-id="{{ $rawmaterial->id }}" class="btn btn-link btn-primary btn-lg edit-rawmaterial-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                
                                                    <a data-rawmaterial-id="{{ $rawmaterial->id }}" class="btn btn-link btn-danger delrawmaterial mt-2">
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



    <!-- Add rawmaterial data Modal -->
    <div style="display:none" class="custom-modal rawmaterial" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Add rawmaterial</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="rawmaterialform">
                    <input type="hidden" id="rawmaterialforminput_add" value=""/>
                    <div class="row mt-5">
                        

                        <div class="col-4">
                            <div class="form-group">
                                <label for="brand_name">Brand Name</label>
                                <select
                                class="form-select form-control"
                                id="defaultSelect" name="brand_name"
                              >
                              <option>Choose One</option>

                              @foreach ($brands as $brand )
                              <option>{{$brand->designation_name}}</option>
                              @endforeach
                               
                               
                              </select>                            
                            </div>
                        </div>
                        
                        <div class="col-4">
                            <div class="form-group">
                                <label for="rawmaterial_name"> Category Name</label>
                                <select
                                class="form-select form-control"
                                id="defaultSelect" name="category_name"
                              >
                              <option>Choose One</option>

                              @foreach ($categorys as $category )
                              <option>{{$category->category_name}}</option>
                              @endforeach
                               
                               
                              </select>                               
                            </div>
                        </div>

                      


                        <div class="col-4">
                            <div class="form-group">
                                <label for="item_name">item_name</label>
                                <input type="text" id="item_name" name="item_name" class="form-control">
                            </div>
                        </div>

                        
                        <div class="col-4">
                            <div class="form-group">
                                <label for="purchase_rate">purchase_rate</label>
                                <input type="number" id="purchase_rate" name="purchase_rate" class="form-control">
                            </div>
                        </div>
                       
                      
                        <div class="col-4">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="text" id="quantity" name="quantity" class="form-control">
                            </div>
                        </div>

                     

                        
                      

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="rawmaterialadd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    


     <!-- Add rawmaterial edit Modal -->
     <div style="display:none" class="custom-modal rawmaterialedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit rawmaterial</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="rawmaterialeditform">
                    <input type="hidden" id="rawmaterialforminput_edit" value=""/>
                    <div class="row mt-5">
                        
                        <div class="col-4">
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

                        <div class="col-4">
                            <div class="form-group">
                                <label for="rawmaterial_name"> Category Name</label>
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
                       
                       

                        <div class="col-4">
                            <div class="form-group">
                                <label for="item_name">item_name</label>
                                <input type="text" id="item_name" name="item_name" class="form-control">
                            </div>
                        </div>

                       
                        <div class="col-4">
                            <div class="form-group">
                                <label for="purchase_rate">Purchase_rate</label>
                                <input type="number" step="any" id="purchase_rate" name="purchase_rate" class="form-control">
                            </div>
                        </div>
                      
                        <div class="col-4">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="text" id="quantity" name="quantity" class="form-control">
                            </div>
                        </div>
                
                      
                       

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="rawmaterialeditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
        $('.addrawmaterial').click(function() {
            $('.custom-modal.rawmaterial').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.rawmaterial').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addrawmaterial')) {
               $('.custom-modal.rawmaterial').fadeOut(); 
           }
       });
   });
   
   //to del rawmaterial
   $(document).on('click', '.delrawmaterial', function() {
       const rawmaterialId = $(this).data('rawmaterial-id');
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
                   url: '/delete-rawmaterial',
                   type: 'POST',
                   data: { rawmaterial_id: rawmaterialId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.addrawmaterial').show();
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
   
   $('#rawmaterialform').on('submit', function (e) {
    e.preventDefault();   

    let formData = new FormData(this);
    createLoader();
    $.ajax({
        url: "{{ route('rawmaterial.store') }}",
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
                    $('#rawmaterialform')[0].reset();
                    $('.custom-modal.rawmaterial').fadeOut();

                    const rawmaterial = response.rawmaterial;
                    const created_at = rawmaterial.created_at; 
                    const newRow = `
                        <tr data-rawmaterial-id="${rawmaterial.id}">
                            <td>${$('.table tbody tr').length + 1}</td>
                            <td>${rawmaterial.id}</td>
                            <td>${rawmaterial.brand_name}</td>
                             <td>${rawmaterial.category_name}</td>
                            <td>${rawmaterial.item_name}</td>
                            <td>${rawmaterial.purchase_rate}</td>
                            <td>${rawmaterial.quantity}</td>
                            <td>${created_at}</td>
                            <td>
                                <div class="form-button-action">
                                    <a id="rawmaterialedit" data-rawmaterial-id="${rawmaterial.id}" class="btn btn-link btn-primary btn-lg edit-rawmaterial-btn">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-rawmaterial-id="${rawmaterial.id}" class="btn btn-link btn-danger mt-2 delrawmaterial">
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

   
   $(document).on('click', '.edit-rawmaterial-btn', function () {
       var rawmaterialId = $(this).data('rawmaterial-id');
       createLoader();
       $.ajax({
           url: "{{ route('rawmaterial.show', '') }}/" + rawmaterialId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#rawmaterialeditform #rawmaterialforminput_edit').val(response.rawmaterial.id);
                  if (response.rawmaterial.image) {
                    $('#rawmaterialeditform #image').attr('src', "{{ asset('images') }}/" + response.rawmaterial.image);
                }
                   $('#rawmaterialeditform #name_edit').val(response.rawmaterial.brand_name);
                   $('#rawmaterialeditform #category_name').val(response.rawmaterial.category_name);
                   $('#rawmaterialeditform #item_name').val(response.rawmaterial.item_name);
                   $('#rawmaterialeditform #purchase_rate').val(response.rawmaterial.purchase_rate);
                   $('#rawmaterialeditform #quantity').val(response.rawmaterial.quantity);
                   $('.custom-modal.rawmaterialedit').fadeIn();
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
   
   
   // Edit rawmaterial 
   $('#rawmaterialeditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var rawmaterialId = $('#rawmaterialforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('rawmaterial.update', '') }}/" + rawmaterialId,  
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
                       $('#rawmaterialeditform')[0].reset();
                       $('.custom-modal.rawmaterialedit').fadeOut();
   
                       const rawmaterial = $(`a[data-rawmaterial-id="${rawmaterialId}"]`).closest('tr');
                       rawmaterial.find('td:nth-child(2)').text(response.rawmaterial.id);
                       rawmaterial.find('td:nth-child(3)').text(response.rawmaterial.brand_name);
                       rawmaterial.find('td:nth-child(4)').text(response.rawmaterial.category_name);
                       rawmaterial.find('td:nth-child(5)').text(response.rawmaterial.item_name);
                       rawmaterial.find('td:nth-child(6)').text(response.rawmaterial.purchase_rate);
                       rawmaterial.find('td:nth-child(7)').text(response.rawmaterial.quantity);
                       rawmaterial.find('td:nth-child(8)').text(response.rawmaterial.created_at);
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
       $('.custom-modal.rawmaterialedit').fadeOut();
   });
           </script>
  </body>
</html>
