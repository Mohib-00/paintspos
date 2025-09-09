<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addarea {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addarea:hover {
        background-color: #45a049;  
    }


.custom-modal.area, 
.custom-modal.areaedit {
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
                      <div class="card-header">
                         @if(auth()->user()->area_add != '0')
                        <div class="d-flex align-items-center">
                            <button class="addarea">Add Area</button>
                        </div>
                        @endif
                      </div>
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
                                <th>Area Name</th>
                                <th>Created At</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($areas as $area)
                                        <tr class="user-row" id="area-{{ $area->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$area->id}}
                                               
                                                <td id="name">{{$area->area_name}}</td>  
                                               
                                                <td id="slug">{{$area->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                    @if(auth()->user()->area_update != '0')
                                                    <a data-area-id="{{ $area->id }}" class="btn btn-link btn-primary btn-lg edit-area-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @endif
                                                
                                                    @if(auth()->user()->area_delete != '0')
                                                    <a data-area-id="{{ $area->id }}" class="btn btn-link btn-danger delarea mt-2">
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



    <!-- Add area data Modal -->
    <div style="display:none" class="custom-modal area" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Add area</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="areaform">
                    <input type="hidden" id="areaforminput_add" value=""/>
                    <div class="row mt-5">
                        

                        <div class="col-12">
                            <div class="form-group">
                                <label for="name_add">Area Name</label>
                                <input type="text" id="name_add" name="area_name" class="form-control">
                            </div>
                        </div>
                        
                       

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="areaadd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    


     <!-- Add area edit Modal -->
     <div style="display:none" class="custom-modal areaedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit area</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="areaeditform">
                    <input type="hidden" id="areaforminput_edit" value=""/>
                    <div class="row mt-5">
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name_edit">Area Name</label>
                                <input type="text" id="name_edit" name="area_name" class="form-control">
                            </div>
                        </div>
                       
                       
                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="areaeditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
        $('.addarea').click(function() {
            $('.custom-modal.area').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.area').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addarea')) {
               $('.custom-modal.area').fadeOut(); 
           }
       });
   });
   
   //to del area
   $(document).on('click', '.delarea', function() {
       const areaId = $(this).data('area-id');
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
                   url: '/delete-area',
                   type: 'POST',
                   data: { area_id: areaId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.addarea').show();
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
   
   $('#areaform').on('submit', function (e) {
    e.preventDefault();   

    let formData = new FormData(this);
    createLoader();
    $.ajax({
        url: "{{ route('area.store') }}",
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
                    $('#areaform')[0].reset();
                    $('.custom-modal.area').fadeOut();

                    const area = response.area;
                    const created_at = area.created_at; 
                    const user_name = response.user_name;
                    const newRow = `
                        <tr data-area-id="${area.id}">
                            <td>${$('.table tbody tr').length + 1}</td>
                            <td>${area.id}</td>
                            <td>${area.area_name}</td>
                       
                            <td>${created_at}</td>
                            <td>
                                <div class="form-button-action">
                                    <a id="areaedit" data-area-id="${area.id}" class="btn btn-link btn-primary btn-lg edit-area-btn">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-area-id="${area.id}" class="btn btn-link btn-danger mt-2 delarea">
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

   
   // get area data
   $(document).on('click', '.edit-area-btn', function () {
       var areaId = $(this).data('area-id');
       createLoader();
       $.ajax({
           url: "{{ route('area.show', '') }}/" + areaId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#areaeditform #areaforminput_edit').val(response.area.id);
                   if (response.area.image) {
                       $('#areaeditform #icon_edit').attr('src', "{{ asset('images') }}/" + response.area.image);
                   }
                   $('#areaeditform #name_edit').val(response.area.area_name);
                  
                   $('.custom-modal.areaedit').fadeIn();
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
   
   
   // Edit area 
   $('#areaeditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var areaId = $('#areaforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('area.update', '') }}/" + areaId,  
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
                       $('#areaeditform')[0].reset();
                       $('.custom-modal.areaedit').fadeOut();
   
                       const area = $(`a[data-area-id="${areaId}"]`).closest('tr');
                       area.find('td:nth-child(2)').text(response.area.id);
                       area.find('td:nth-child(3)').text(response.area.area_name);
                      
                       area.find('td:nth-child(7)').text(response.area.created_at);
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
       $('.custom-modal.areaedit').fadeOut();
   });
           </script>
  </body>
</html>
