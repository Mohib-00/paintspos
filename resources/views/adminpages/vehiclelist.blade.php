
<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addvehicle {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addvehicle:hover {
        background-color: #45a049;  
    }


    .custom-modal.vehicle, 
.custom-modal.vehicleedit {
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
    width: 90vw; 
    max-width: 100%; 
    animation: slideDown 0.5s ease;
    margin: 0 auto;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 100%;
    height: auto;
    text-align: center;
}

.modal-dialog.modal-sm-custom {
  max-width: 500px; 
  margin: auto;
}


.slide-modal .modal-dialog {
  transform: translateY(-100px);
  transition: transform 0.4s ease-out;
}

.slide-modal.show .modal-dialog {
  transform: translateY(0);
}

.modal-content {
  border-radius: 10px;
}

.modal-backdrop.show {
  opacity: 0.5;
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
                            <button class="btn btn-sm btn-outline-primary me-2 print-table">
                                <i class="fas fa-print"></i> Print
                            </button>
                    
                            <button class="btn btn-sm btn-outline-danger export-pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            <button class="btn btn-sm btn-outline-primary export-excel">
                                <i class="fas fa-file-pdf"></i> Excel
                            </button>
                        </div>
                    
                        
                        <div class="d-flex align-items-center">
                            <button class="addvehicle" href="/admin/vehicle_record_add" onclick="loadVehicleRecordAddPage(); return false;">Add Vehicle</button>
                        </div>
                     
                    </div>
                    
                    <h1 class="mx-3 list">Vehicle List</h1>
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
                                <th>Vehicle No</th>
                                <th>Owner Name</th>
                                <th>Phone No</th>
                                <th>Current Oil Reading</th>
                                <th>Next Oil Reading</th>
                                <th>Oil Brand</th>
                                <th>Quantity</th>
                                <th>Gear Oil</th>
                                <th>Oil filter</th>
                                <th>Air Filter</th>
                                <th>Ac Filter</th>
                                <th>Battery Checkup</th>
                                <th>Air Pressure</th>
                                <th>Invoice No</th>
                                <th>Total Bill</th>
                                <th>Date</th>
                                <th style="width: 10%">Action</th>
                                <th>Add Alert</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($vehicles as $vehicle)
                                        <tr class="user-row" id="vehicle-{{ $vehicle->id }}">
                                            <td>{{$counter}}</td>
                                            <td>{{$vehicle->id}}</td>
                                                <td>{{$vehicle->vehicle_no}}</td>
                                                <td>{{$vehicle->owner_name}}</td>
                                                <td>{{$vehicle->phone_no}}</td>
                                                <td>{{$vehicle->currentoil_reading}}</td>  
                                                <td>{{$vehicle->nextoil_reading}}</td>
                                                <td>{{$vehicle->oil_brand}}</td>
                                                <td>{{$vehicle->quantity}}</td>
                                                <td>{{$vehicle->gear_oil}}</td>
                                                <td>{{$vehicle->oil_filter}}</td>
                                                <td>{{$vehicle->air_filter}}</td>
                                                <td>{{$vehicle->Ac_filter}}</td>
                                                <td>{{$vehicle->battery_checkup}}</td>
                                                <td>{{$vehicle->type_air_pressure}}</td> 
                                                <td>{{$vehicle->total_bill}}</td>
                                                 <td>{{$vehicle->invoice_no}}</td>

                                                <td>{{$vehicle->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                 
                                                    <a data-vehicle-id="{{ $vehicle->id }}" class="btn btn-link btn-primary btn-lg edit-vehicle-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                    
                                                    <a data-vehicle-id="{{ $vehicle->id }}" class="btn btn-link btn-danger delvehicle mt-2">
                                                        <i class="fa fa-times"></i>                    
                                                    </a>
                                                </div>
                                                </td>
                                                <td>
                                                    <a data-vehicle-id="{{ $vehicle->id }}" class="btn btn-link btn-success addvehiclealert mt-2">
                                                        <i class="fas fa-bell"></i>                    
                                                    </a>
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

<div class="modal fade slide-modal vehiclealertmodel" id="addAlertModal" tabindex="-1" role="dialog" aria-labelledby="addAlertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm-custom modal-dialog-centered" role="document">
    <form id="alertForm" class="w-100">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Vehicle Alert</h5>
            <button type="button" class="btn btn-outline-danger btn-sm" id="customClose">
            ✖
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="vehicle_id" id="alertVehicleId">

          <div class="form-group">
            <label for="alert">Alert</label>
            <input type="text" name="alert" id="alert" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="custom_date">Alert Date</label>
            <input type="date" name="created_at" id="custom_date" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-block">Save Alert</button>
        </div>
      </div>
    </form>
  </div>
</div>




        @include('adminpages.footer')
      </div>
    </div>

     <!-- Add vehicle edit Modal -->
     <div style="display:none" class="custom-modal vehicleedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit vehicle</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                 <form id="vehicleeditform"> 
                    <input type="hidden" id="vehicleforminput_edit" value=""/>    
                    <div class="card-body">
                      <div class="row">

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="vehicle_no">Vehicle No</label>
                              <input class="form-control" type="text" id="vehicle_no" name="vehicle_no">
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>


                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="owner_name">Owner Name</label>
                                  <input class="form-control" type="text" id="owner_name" name="owner_name">
                                  <span id="nameError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="phone_no">Phone No</label>
                                  <input class="form-control" type="number" id="phone_no" name="phone_no">
                                  <span id="nameError" class="text-danger"></span>
                                </div>
                            </div>

                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                      <label for="currentoil_reading">Current Oil Reading</label>
                                      <input class="form-control" type="text" id="currentoil_reading" name="currentoil_reading">
                                      <span id="nameError" class="text-danger"></span>
                                    </div>
                                </div>


                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                      <label for="nextoil_reading">Next Oil Reading</label>
                                      <input class="form-control" type="text" id="nextoil_reading" name="nextoil_reading">
                                      <span id="nameError" class="text-danger"></span>
                                    </div>
                                </div>


                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="oil_brand">Oil Brand</label>
                                          <input class="form-control" type="text" id="oil_brand" name="oil_brand">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                  

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="quantity">Quantity</label>
                                          <input class="form-control" type="text" id="quantity" name="quantity">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                   <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="gear_oil">Gear Oil</label>
                                          <input class="form-control" type="text" id="gear_oil" name="gear_oil">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="oil_filter">Oil filter</label>
                                          <input class="form-control" type="text" id="oil_filter" name="oil_filter">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="air_filter">Air Filter</label>
                                          <input class="form-control" type="text" id="air_filter" name="air_filter" >
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="Ac_filter">Ac Filter</label>
                                          <input class="form-control" type="text" id="Ac_filter" name="Ac_filter" >
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                      <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="battery_checkup">Battery Checkup</label>
                                          <input class="form-control" type="text" id="battery_checkup" name="battery_checkup" >
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                      <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="type_air_pressure">Air Pressure</label>
                                          <input class="form-control" type="text" id="type_air_pressure" name="type_air_pressure" >
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                      <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="invoice_no">Invoice No</label>
                                          <input class="form-control" type="text" id="invoice_no" name="invoice_no" value="Invoice-{{ rand(1000, 9999) }}">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                      <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="total_bill">Total Bill</label>
                                          <input class="form-control" type="text" id="total_bill" name="total_bill" >
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                      <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="created_at">Date</label>
                                          <input class="form-control" type="date" id="created_at" name="created_at">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                      
                      </div>
                    </div>
                    <div class="card-action">
                      <a id="submitdata" class="btn btn-success">Submit</a>
                    </div>
                    </form>
                
            </div>
        </div>
    </div>

    @include('adminpages.js')
    @include('adminpages.ajax')

    <script>
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('downloadLink').href = imageSrc;
        document.getElementById('imageModal').style.display = 'block';
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    window.onclick = function(event) {
        let modal = document.getElementById('imageModal');
        if (event.target == modal) {
            closeImageModal();
        }
    }
</script>

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
        $('.addvehicle').click(function() {
            $('.custom-modal.vehicle').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.vehicle').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addvehicle')) {
               $('.custom-modal.vehicle').fadeOut(); 
           }
       });
   });
   
   //to del vehicle
  $(document).on('click', '.delvehicle', function() {
    const vehicleId = $(this).data('vehicle-id');
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
                url: '/delete-vehicle',
                type: 'POST',
                data: { vehicle_id: vehicleId },  
                dataType: 'json',
                success: function(response) {
                    removeLoader();
                    if (response.success) {
                        $('.addvehicle').show();
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
                    let errorMessage = 'An error occurred while deleting this.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire(
                        'Error',
                        errorMessage,
                        'error'
                    );
                }
            });
        }
    });
});

   

   
   // get vehicle data
   $(document).on('click', '.edit-vehicle-btn', function () {
       var vehicleId = $(this).data('vehicle-id');
       createLoader();
       $.ajax({
           url: "{{ route('vehicle.show', '') }}/" + vehicleId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#vehicleeditform #vehicleforminput_edit').val(response.vehicle.id);
                   if (response.vehicle.image) {
                       $('#vehicleeditform #icon_edit').attr('src', "{{ asset('images') }}/" + response.vehicle.image);
                   }
                   $('#vehicleeditform #name').val(response.vehicle.vehicle_name);
                   $('#vehicleeditform #vehicle_no').val(response.vehicle.vehicle_no);
                   $('#vehicleeditform #owner_name').val(response.vehicle.owner_name);
                   $('#vehicleeditform #phone_no').val(response.vehicle.phone_no);
                   $('#vehicleeditform #currentoil_reading').val(response.vehicle.currentoil_reading);
                   $('#vehicleeditform #nextoil_reading').val(response.vehicle.nextoil_reading);
                   $('#vehicleeditform #oil_brand').val(response.vehicle.oil_brand);
                   $('#vehicleeditform #quantity').val(response.vehicle.quantity);
                   $('#vehicleeditform #gear_oil').val(response.vehicle.gear_oil);
                   $('#vehicleeditform #oil_filter').val(response.vehicle.oil_filter);
                   $('#vehicleeditform #air_filter').val(response.vehicle.air_filter);
                   $('#vehicleeditform #Ac_filter').val(response.vehicle.Ac_filter);
                   $('#vehicleeditform #battery_checkup').val(response.vehicle.battery_checkup);
                   $('#vehicleeditform #type_air_pressure').val(response.vehicle.type_air_pressure);
                   $('#vehicleeditform #invoice_no').val(response.vehicle.invoice_no);
                   $('#vehicleeditform #total_bill').val(response.vehicle.total_bill);
                   $('#vehicleeditform #created_at').val(response.vehicle.created_at);
                   $('.custom-modal.vehicleedit').fadeIn();
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
   
   
   // Edit vehicle 
   $(document).on('click', '#submitdata', function (e) {
       e.preventDefault();
   
       var formData = new FormData($('#vehicleeditform')[0]);
       var vehicleId = $('#vehicleforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('vehicle.update', '') }}/" + vehicleId,  
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
                       $('#vehicleeditform')[0].reset();
                       $('.custom-modal.vehicleedit').fadeOut();
   
                       loadVehiclelistPage();
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
       $('.custom-modal.vehicleedit').fadeOut();
   });


  $(document).on('click', '.addvehiclealert', function () {
    const vehicleId = $(this).data('vehicle-id');
    $('#alertVehicleId').val(vehicleId);
    $('#addAlertModal').modal('show');
});

$('#alertForm').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serialize();

    Swal.fire({
        title: 'Saving alert...',
        html: 'Please wait...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '/save-alert',
        method: 'POST',
        data: formData,
        success: function (response) {
            Swal.close(); 

            $('#addAlertModal').modal('hide');
            $('#alertForm')[0].reset();

            Swal.fire('Success', response.message, 'success');
        },
        error: function (xhr) {
            Swal.close(); 

            let msg = 'Something went wrong.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            Swal.fire('Error', msg, 'error');
        }
    });
});
document.getElementById("customClose").addEventListener("click", function () {
    $('#addAlertModal').modal('hide');
  });


           </script>
  </body>
</html>

