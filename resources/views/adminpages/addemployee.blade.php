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
                        <a class="user" href="/admin/employees_list" onclick="loadEmployeesPage(); return false;">Back</a>
                    </div>
                    <form id="employeeform">     
                    <div class="card-body">
                      <div class="row">

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="email2">Name</label>
                              <input class="form-control" type="text" id="name" name="employee_name" placeholder="Name">
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="defaultSelect">Select Position</label>
                            <select
                              class="form-select form-control"
                              id="defaultSelect" name="area_id"
                            >
                              <option>Select Position</option>
                             @foreach($designations as $designation)
                              <option>{{$designation->designation_name}}</option>
                              @endforeach
                            
                            </select>
                          </div>
                        </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="Phone_1">Employee Phone_1</label>
                                  <input class="form-control" type="number" id="phone_1" name="phone_1" placeholder="Client phone_1">
                                  <span id="nameError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="Phone_2">Employee Phone_2</label>
                                  <input class="form-control" type="number" id="phone_2" name="phone_2" placeholder="Client phone_2">
                                  <span id="nameError" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label for="defaultSelect">Employee Gender</label>
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
                                      <label for="client_cnic">Employee CNIC</label>
                                      <input class="form-control" type="number" id="client_cnic" name="client_cnic" placeholder="XXXXX-XXXXXXXXXXX-X">
                                      <span id="nameError" class="text-danger"></span>
                                    </div>
                                </div>


                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                      <label for="client_father_name">Employee Father Name</label>
                                      <input class="form-control" type="text" id="client_father_name" name="client_father_name" placeholder="Father Name">
                                      <span id="nameError" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="defaultSelect">Employee Residence</label>
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
                                          <label for="client_salary">Employee Salary</label>
                                          <input class="form-control" type="number" id="client_salary" name="client_salary" placeholder="Client Salary*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                  
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_permanent_address">Employee Permanent Address</label>
                                          <input class="form-control" type="text" id="client_permanent_address" name="client_permanent_address" placeholder="Permanent Address*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_residential_address">Employee Current Address</label>
                                          <input class="form-control" type="text" id="client_residential_address" name="client_residential_address" placeholder="Current Address*">
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

    let formData = new FormData($('#employeeform')[0]);

    createLoader();
    $.ajax({
        url: "{{ route('employee.store') }}", 
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
              removeLoader();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'employee saved successfully!',
                    confirmButtonText: 'OK'
                });

                $('#employeeform')[0].reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
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
                    errorMessage += value[0] + '<br>';
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
