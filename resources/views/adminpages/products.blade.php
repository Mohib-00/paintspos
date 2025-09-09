<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addproduct {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addproduct:hover {
        background-color: #45a049;  
    }

    .custom-modal.product, 
    .custom-modal.productedit {
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
                    
                        @if(auth()->user()->product_add != '0')
                        <div class="d-flex align-items-center">
                            <a class="addproduct" >Add product</a>
                        </div>
                        @endif
                    </div>
                    
                    <h1 class="mx-3 list">Product List</h1>

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
                                <th>Item Name</th>
                                <th>Shade</th>
                                <th>Code</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($products as $product)
                                        <tr class="user-row" id="product-{{ $product->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$product->id}}
                                                <td>
                                                <img height="80" width="80" src="{{ asset('public/images/' . $product->image) }}" alt="Image" >
                                                </td>
                                                <td id="name">{{$product->brand_name}}</td>  
                                                <td id="heading">{{$product->item_name}}</td> 
                                                <td id="heading">{{$product->shade}}</td> 
                                                <td id="heading">{{$product->code}}</td> 
                                                <td id="user_id">
                                                    @if($product->user)
                                                        {{ $product->user->name }}
                                                    @endif
                                                </td>
                                                
                                                <td id="slug">{{$product->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                        @if(auth()->user()->product_update != '0')
                                                    <a data-product-id="{{ $product->id }}" class="btn btn-link btn-primary btn-lg edit-product-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @endif
                                                
                                                    @if(auth()->user()->product_delete != '0')
                                                    <a data-product-id="{{ $product->id }}" class="btn btn-link btn-danger delproduct mt-2">
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



    <!-- Add product data Modal -->
    <div style="display:none" class="custom-modal product" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Add product</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="productform">
                    <input type="hidden" id="productforminput_add" value=""/>
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
                                <label for="product_name"> Category Name</label>
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
                                <label for="productcategory_name">Sub Category Name</label>
                                <select
                                class="form-select form-control"
                                id="defaultSelect" name="subcategory_name"
                              >
                              <option>Choose One</option>

                              @foreach ($subs as $sub )
                              <option>{{$sub->category_name}}</option>
                              @endforeach
                               
                               
                              </select>                                
                            </div>
                        </div>


                        <div class="col-4">
                            <div class="form-group">
                                <label for="item_name">Item_name</label>
                                <input type="text" id="item_name" name="item_name" class="form-control">
                            </div>
                        </div>
                         <div class="col-4">
                            <div class="form-group">
                                <label for="shade">Shade</label>
                                <input type="text" id="shade" name="shade" class="form-control">
                            </div>
                        </div>
                         <div class="col-4">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" id="code" name="code" class="form-control">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="barcode">barcode</label>
                                <input type="number" id="barcode" name="barcode" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="purchase_rate">purchase_rate</label>
                                <input type="number" id="purchase_rate" name="purchase_rate" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="retail_rate">retail_rate</label>
                                <input type="retail_rate" id="retail_rate" name="retail_rate" class="form-control" value="0">
                            </div>
                        </div>
                      
                        {{--<div class="col-4">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity" class="form-control">
                            </div>
                        </div>--}}

                     

                          <div class="col-8">
                            <div class="form-group">
                              <label for="email2">Image</label>
                              <input class="form-control" type="file" id="image" name="image" >
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>
                       
                      

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="productadd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    


     <!-- Add product edit Modal -->
     <div style="display:none" class="custom-modal productedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit product</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="producteditform">
                    <input type="hidden" id="productforminput_edit" value=""/>
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
                                <label for="product_name"> Category Name</label>
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
                                <label for="productcategory_name">Sub Category Name</label>
                                <select
                                class="form-select form-control"
                                id="subcategory_name" name="subcategory_name"
                              >
                              @foreach ($subs as $sub )
                              <option>{{$sub->category_name}}</option>
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
                                <label for="shade">Shade</label>
                                <input type="text" id="shade" name="shade" class="form-control">
                            </div>
                        </div>
                         <div class="col-4">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" id="code" name="code" class="form-control">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="barcode">barcode</label>
                                <input type="number" id="barcode" name="barcode" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="purchase_rate">purchase_rate</label>
                                <input type="number" step="any" id="purchase_rate" name="purchase_rate" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="retail_rate">retail_rate</label>
                                <input type="retail_rate" step="any" id="retail_rate" name="retail_rate" class="form-control" value="0">
                            </div>
                        </div>

                            {{--<div class="col-4">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" id="quantity" name="quantity" class="form-control">
                            </div>
                        </div>--}}
                
                      
                          <div class="col-8">
                            <div class="form-group">
                              <label for="email2">Image</label>
                              <input class="form-control" type="file" id="image" name="image" >
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="producteditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
        $('.addproduct').click(function() {
            $('.custom-modal.product').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.product').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addproduct')) {
               $('.custom-modal.product').fadeOut(); 
           }
       });
   });
   
   //to del product
   $(document).on('click', '.delproduct', function() {
       const productId = $(this).data('product-id');
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
                   url: '/delete-product',
                   type: 'POST',
                   data: { product_id: productId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.addproduct').show();
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
   
  $('#productform').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    createLoader();
    $.ajax({
        url: "{{ route('product.store') }}",
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
                    $('#productform')[0].reset();
                    $('.custom-modal.product').fadeOut();

                    const products = response.products; // ✅ array
                    const user_name = response.user_name;

                    products.forEach(product => {
                        const created_at = product.created_at;

                        const newRow = `
                            <tr data-product-id="${product.id}">
                                <td>${$('.table tbody tr').length + 1}</td>
                                <td>${product.id}</td>
                                <td>
                                    <img height="80" width="80" src="{{ asset('public/images') }}/${product.image}" alt="Image" />
                                </td>
                                <td>${product.brand_name ?? ''}</td>
                                <td>${product.item_name ?? ''}</td>
                                <td>${product.shade ?? ''}</td>
                                <td>${product.code ?? ''}</td>
                                <td>${user_name}</td>
                                <td>${created_at}</td>
                                <td>
                                    <div class="form-button-action">
                                        <a data-product-id="${product.id}" class="btn btn-link btn-primary btn-lg edit-product-btn">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a data-product-id="${product.id}" class="btn btn-link btn-danger mt-2 delproduct">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        `;
                        $('table tbody').prepend(newRow);
                    });

                    // Re-index rows
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


   
   $(document).on('click', '.edit-product-btn', function () {
       var productId = $(this).data('product-id');
       createLoader();
       $.ajax({
           url: "{{ route('product.show', '') }}/" + productId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#producteditform #productforminput_edit').val(response.product.id);
                  if (response.product.image) {
                    $('#producteditform #image').attr('src', "{{ asset('images') }}/" + response.product.image);
                }
                   $('#producteditform #name_edit').val(response.product.brand_name);
                   $('#producteditform #category_name').val(response.product.category_name);
                   $('#producteditform #subcategory_name').val(response.product.subcategory_name);
                   $('#producteditform #item_name').val(response.product.item_name);
                   $('#producteditform #barcode').val(response.product.barcode);
                   $('#producteditform #purchase_rate').val(response.product.single_purchase_rate);
                   $('#producteditform #retail_rate').val(response.product.single_retail_rate);
                   $('#producteditform #mini_whole_rate').val(response.product.mini_whole_rate);
                   $('#producteditform #wholesale_rate').val(response.product.wholesale_rate);
                   $('#producteditform #type_a_rate').val(response.product.type_a_rate);
                   $('#producteditform #type_b_rate').val(response.product.type_b_rate);
                   $('#producteditform #type_c_rate').val(response.product.type_c_rate);
                   $('#producteditform #quantity').val(response.product.quantity);
                   $('#producteditform #shade').val(response.product.shade);
                   $('#producteditform #code').val(response.product.code);
                   $('#producteditform #price').val(response.product.price);
                   $('.custom-modal.productedit').fadeIn();
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
   
   
   // Edit product 
   $('#producteditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var productId = $('#productforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('product.update', '') }}/" + productId,  
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
                       $('#producteditform')[0].reset();
                       $('.custom-modal.productedit').fadeOut();
   
                       const product = $(`a[data-product-id="${productId}"]`).closest('tr');
                       product.find('td:nth-child(2)').text(response.product.id);
                       product.find('td:nth-child(3) img').attr('src', `/images/${response.product.image}`);
                       product.find('td:nth-child(4)').text(response.product.brand_name);
                       product.find('td:nth-child(5)').text(response.product.item_name);
                        product.find('td:nth-child(6)').text(response.product.shade);
                         product.find('td:nth-child(7)').text(response.product.code);
                       product.find('td:nth-child(8)').text(response.user_name); 
                       product.find('td:nth-child(9)').text(response.product.created_at);
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
       $('.custom-modal.productedit').fadeOut();
   });
           </script>
  </body>
</html>
