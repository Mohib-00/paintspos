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
                        <a class="user" href="/admin/customer_list" onclick="loadCustomerPage(); return false;">Back</a>
                    </div>
                    <form id="customerform">     
                    <div class="card-body">
                      <div class="row">


                         <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="email2">Image</label>
                              <input class="form-control" type="file" id="img" name="img" >
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="email2">Name</label>
                              <input class="form-control" type="text" id="name" name="customer_name" placeholder="Name">
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="defaultSelect">Area</label>
                            <select
                              class="form-select form-control"
                              id="defaultSelect" name="area_id"
                            >
                              <option>Choose Area</option>
                              @foreach($areas as $area)
                              <option>{{$area->area_name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="defaultSelect">Assign User</label>
                                <select
                                  class="form-select form-control"
                                  id="defaultSelect" name="assigned_user_id"
                                >
                                  <option>Select User</option>
                                  @foreach($employees as $employee)
                                  <option>{{$employee->employee_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>


                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="client_type">Client Type</label>
                                  <select
                                  class="form-select form-control"
                                  id="client_type" name="client_type"
                                >
                                  <option>Select Type</option>
                                 
                                  <option>retail_rate</option>
                              
                                </select>
                                  <span id="nameError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="Phone_1">Client Phone_1</label>
                                  <input class="form-control" type="number" id="phone_1" name="phone_1" placeholder="Client phone_1">
                                  <span id="nameError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="Phone_2">Client Phone_2</label>
                                  <input class="form-control" type="number" id="phone_2" name="phone_2" placeholder="Client phone_2">
                                  <span id="nameError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="defaultSelect">Client Gender</label>
                                    <select
                                      class="form-select form-control"
                                      id="defaultSelect" name="client_gender"
                                    >
                                      <option>Select Gender</option>
                                      <option>Male</option>
                                      <option>FeMale</option>
                                      <option>Other</option>
                                      
                                    </select>
                                  </div>
                                </div>


                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                      <label for="client_cnic">Client CNIC</label>
                                      <input class="form-control" type="number" id="client_cnic" name="client_cnic" placeholder="XXXXX-XXXXXXXXXXX-X">
                                      <span id="nameError" class="text-danger"></span>
                                    </div>
                                </div>


                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                      <label for="client_father_name">Client Father Name</label>
                                      <input class="form-control" type="text" id="client_father_name" name="client_father_name" placeholder="Father Name">
                                      <span id="nameError" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="defaultSelect">Client Residence</label>
                                        <select
                                          class="form-select form-control"
                                          id="defaultSelect" name="client_residence"
                                        >
                                          <option>Select Residence</option>
                                          <option>Personal</option>
                                          <option>Rent</option>
                                          <option>Government</option>
                                          
                                        </select>
                                      </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_occupation">Client Occupation</label>
                                          <input class="form-control" type="text" id="client_occupation" name="client_occupation" placeholder="Client Occupation*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                  

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_salary">Client Fixed discount %</label>
                                          <input class="form-control" type="number" id="client_fixed_discount" name="client_fixed_discount" placeholder="Client Fixed Discount*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    {{--<div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="distributor_fix_margin">Distributor Fix Margin</label>
                                          <input class="form-control" type="number" id="distributor_fix_margin" name="distributor_fix_margin" placeholder="Fix Margin*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>--}}


                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_permanent_address">Client Permanent Address</label>
                                          <input class="form-control" type="text" id="client_permanent_address" name="client_permanent_address" placeholder="Permanent Address*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_residential_address">Client Residential Address</label>
                                          <input class="form-control" type="text" id="client_residential_address" name="client_residential_address" placeholder="Residential Address*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_office_address">Client Office Address</label>
                                          <input class="form-control" type="text" id="client_office_address" name="client_office_address" placeholder="Office Address*">
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

    let formData = new FormData($('#customerform')[0]);

    createLoader();
    $.ajax({
        url: "{{ route('customer.store') }}", 
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
                    text: 'Customer saved successfully!',
                    confirmButtonText: 'OK'
                });

                $('#customerform')[0].reset();
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
                    if (key === 'customer_name') {
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
