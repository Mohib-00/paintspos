<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addcompany {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addcompany:hover {
        background-color: #45a049;  
    }


.custom-modal.company, 
.custom-modal.companyedit {
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
                    
                        @if(auth()->user()->cmppny_add != '0')
                        <div class="d-flex align-items-center">
                            <a class="addcompany" >Add company</a>
                        </div>
                        @endif
                    </div>
                    
                    <h1 class="mx-3 list">company List</h1>

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
    <th>Document</th>
    <th>Company Name</th>
    <th>Address</th>
    <th>Phone</th>
    <th>Created By</th>
    <th>Created At</th>
    <th style="width: 10%">Action</th>
  </tr>
</thead>

<tbody>
  @php $counter = 1; @endphp
  @foreach($companys as $company)
    <tr class="user-row" id="company-{{ $company->id }}">
      <td>{{ $counter }}</td>
      <td>{{ $company->id }}</td>

     <td>
  @if ($company->image)
    <a href="{{ asset('images/' . $company->image) }}" download>
      <img src="{{ asset('images/' . $company->image) }}" alt="Company Image" width="80" class="img-thumbnail">
    </a>
  @else
    <span>No Image</span>
  @endif
</td>


      <td>{{ $company->designation_name }}</td>
      <td>{{ $company->address }}</td>
      <td>{{ $company->phone }}</td>
      <td>{{ $company->user->name }}</td>
      <td>{{ $company->created_at }}</td>

      <td>
        <div class="form-button-action">
            @if(auth()->user()->cmppny_update != '0')
          <a data-company-id="{{ $company->id }}" class="btn btn-link btn-primary btn-lg edit-company-btn" title="Edit">
            <i class="fa fa-edit"></i>
          </a>
          @endif
          @if(auth()->user()->cmppny_delete != '0')
          <a data-company-id="{{ $company->id }}" class="btn btn-link btn-danger delcompany mt-2" title="Delete">
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



    <!-- Add company data Modal -->
    <div style="display:none" class="custom-modal company" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Add company</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="companyform">
                    <input type="hidden" id="companyforminput_add" value=""/>
                    <div class="row mt-5">

                         <div class="col-6">
                            <div class="form-group">
                                <label for="image">Document</label>
                                <input type="file" id="image" name="image" class="form-control">
                            </div>
                        </div>
                        

                        <div class="col-6">
                            <div class="form-group">
                                <label for="designation_name">Company Name</label>
                                <input type="text" id="designation_name" name="designation_name" class="form-control">
                            </div>
                        </div>

                         <div class="col-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" class="form-control">
                            </div>
                        </div>

                          <div class="col-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="number" id="phone" name="phone" class="form-control">
                            </div>
                        </div>


                    

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="companyadd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    


     <!-- Add company edit Modal -->
     <div style="display:none" class="custom-modal companyedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit company</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="companyeditform">
                    <input type="hidden" id="companyforminput_edit" value=""/>
                    <div class="row mt-5">

                         <div class="col-6">
                            <div class="form-group">
                                <label for="image">Document</label>
                                <input type="file" id="image" name="image" class="form-control">
                            </div>
                        </div>
                        
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name_edit">Company Name</label>
                                <input type="text" id="name_edit" name="designation_name" class="form-control">
                            </div>
                        </div>

                          <div class="col-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" class="form-control">
                            </div>
                        </div>

                          <div class="col-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="number" id="phone" name="phone" class="form-control">
                            </div>
                        </div>
                       
                      
                       
                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="companyeditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
        $('.addcompany').click(function() {
            $('.custom-modal.company').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.company').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addcompany')) {
               $('.custom-modal.company').fadeOut(); 
           }
       });
   });
   
   //to del company
   $(document).on('click', '.delcompany', function() {
       const companyId = $(this).data('company-id');
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
                   url: '/delete-company',
                   type: 'POST',
                   data: { company_id: companyId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.addcompany').show();
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
   
   $('#companyform').on('submit', function (e) {
    e.preventDefault();   

    let formData = new FormData(this);
    createLoader();
    $.ajax({
        url: "{{ route('company.store') }}",
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
                    $('#companyform')[0].reset();
                    $('.custom-modal.company').fadeOut();

                    const company = response.company;
                    const created_at = company.created_at; 
                    const user_name = response.user_name;
                    const newRow = `
                        <tr data-company-id="${company.id}">
                            <td>${$('.table tbody tr').length + 1}</td>
                            <td>${company.id}</td>
                            <td><img height="80" width="80" src="{{ asset('images/') }}/${company.image}" /></td>
                            <td>${company.designation_name}</td>
                            <td>${company.address}</td>
                            <td>${company.phone}</td>
                            <td>${user_name}</td>
                            <td>${created_at}</td>
                            <td>
                                <div class="form-button-action">
                                    <a id="companyedit" data-company-id="${company.id}" class="btn btn-link btn-primary btn-lg edit-company-btn">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-company-id="${company.id}" class="btn btn-link btn-danger mt-2 delcompany">
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

   
   // get company data
   $(document).on('click', '.edit-company-btn', function () {
       var companyId = $(this).data('company-id');
       createLoader();
       $.ajax({
           url: "{{ route('company.show', '') }}/" + companyId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#companyeditform #companyforminput_edit').val(response.company.id);
                   if (response.company.image) {
                       $('#companyeditform #image').attr('src', "{{ asset('images') }}/" + response.company.image);
                   }
                    $('#companyeditform #name_edit').val(response.company.designation_name);
                    $('#companyeditform #address').val(response.company.address);
                    $('#companyeditform #phone').val(response.company.phone);
     
                   $('.custom-modal.companyedit').fadeIn();
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
   
   
   // Edit company 
   $('#companyeditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var companyId = $('#companyforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('company.update', '') }}/" + companyId,  
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
                       $('#companyeditform')[0].reset();
                       $('.custom-modal.companyedit').fadeOut();
   
                       const company = $(`a[data-company-id="${companyId}"]`).closest('tr');
                       company.find('td:nth-child(2)').text(response.company.id);
                       company.find('td:nth-child(3)').attr('src', "{{ asset('images') }}/" + company.image);
                       company.find('td:nth-child(4)').text(response.company.designation_name);
                       company.find('td:nth-child(5)').text(response.company.address);
                       company.find('td:nth-child(6)').text(response.company.phone);
                       company.find('td:nth-child(7)').text(response.user_name); 
                       company.find('td:nth-child(8)').text(response.company.created_at);
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
       $('.custom-modal.companyedit').fadeOut();
   });
           </script>
  </body>
</html>
