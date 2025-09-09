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
                        <a class="user" href="/admin/vehicle_list" onclick="loadVehiclelistPage(); return false;">Back</a>
                    </div>
                    <form id="vehicleform">     
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
          </div>
        </div>

        @include('adminpages.footer')
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
   
$(document).on('keydown', function(e) {
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        $('#submitdata').click();  
    }
});

$('#submitdata').on('click', function (e) {
    e.preventDefault();

    let formData = new FormData($('#vehicleform')[0]);

    createLoader();
    $.ajax({
        url: "{{ route('vehicle.store') }}", 
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            removeLoader();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'vehicle saved successfully!',
                    confirmButtonText: 'OK'
                }).then((result) => {
            if (result.isConfirmed) {
                $('#vehicleform')[0].reset();
                loadVehiclelistPage();
            }
        });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Something went wrong!',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function (xhr) {
            removeLoader();
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = '';

                $.each(errors, function (key, value) {
                    if (key === 'vehicle_name') {
                        errorMessage += `<strong>Name Error:</strong> ${value.join('<br>')}<br>`;
                    } else {
                        errorMessage += `${value.join('<br>')}<br>`;
                    }
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errorMessage,
                    confirmButtonText: 'OK'
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Something went wrong on the server!',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
});

});

</script>
   
  </body>
</html>
