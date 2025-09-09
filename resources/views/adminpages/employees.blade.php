<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addemployee {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addemployee:hover {
        background-color: #45a049;  
    }


    .custom-modal.employee, 
.custom-modal.employeeedit {
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

</style>
  </head>
  <body>
    <div class="wrapper">
    @include('adminpages.sidebar')

      <div class="main-panel">
        @include('adminpages.header')

        <div class="container">
            <div class="page-inner">
                <div
                  class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                  <div>
                    <h3 class="fw-bold mb-3">Staff List</h3>
                  </div>
                 @if(auth()->user()->empl_add != '0')
                  <div class="ms-md-auto py-2 py-md-0">
                    <a href="/admin/add_employee" onclick="loadEmployeePage(); return false;" class="btn btn-primary btn-round">Add Staff</a>
                  </div>
                  @endif

                </div>
                <div>
                  <input type="text" id="employeeSearch" class="form-control" placeholder="Search staff..." style="width: 100%;">
                  </div>
            <div class="row">

              @foreach($employees as $employee)
              <div class="col-sm-6 col-md-3 mt-2 employee-card" data-id="{{ $employee->id }}">
                <div class="card card-stats card-round h-100">
                  <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <div class="icon-big text-center mb-3">
                      <img src="{{ asset('images/dummy-image.jpg') }}" alt="User" class="rounded-circle" width="80" height="80">
                    </div>
            
                    <div class="numbers mb-3">
                      <p class="card-category mb-1 employee-name" data-id="{{ $employee->id }}">
                          {{ $employee->employee_name }} 
                          <span data-id="{{ $employee->id }}" class="area-id">({{ $employee->area_id }})</span>
                      </p>
                    </div>
            
                    <div class="d-flex gap-2">
                      @if(auth()->user()->empl_update != '0')
                      <a href="#" data-employee-id="{{ $employee->id }}" class="btn btn-sm btn-primary edit-employee-btn">Edit Profile</a>
                      @endif
                      @if(auth()->user()->empl_delete != '0')
                      <a href="#" data-employee-id="{{ $employee->id }}" class="btn btn-sm btn-danger delemployee">Delete</a>
                      @endif
                    </div>
            
                    <div class="numbers mb-3">
                      <h4 class="card-title mb-0">{{ Auth::user()->name }}</h4>
                    </div>
            
                  </div>
                </div>
              </div>
            @endforeach
                        
              </div>
            </div>
        </div>

        @include('adminpages.footer')
      </div>
    </div>

      <!-- Add employee edit Modal -->
      <div style="display:none" class="custom-modal employeeedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit employee</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="employeeeditform">
                    <input type="hidden" id="employeeforminput_edit" value=""/>
                    <div class="row mt-5">

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
                            id="default" name="area_id"
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
                                        id="defaultSelectt" name="client_residence"
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
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="employeeeditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
      $('.addemployee').click(function() {
          $('.custom-modal.employee').fadeIn();  
     });
 
      $('.closeModal').click(function() {
         $('.custom-modal.employee').fadeOut(); 
     });
 
      $(document).click(function(event) {
         if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addemployee')) {
             $('.custom-modal.employee').fadeOut(); 
         }
     });
 });
 
 //to del employee
 $(document).on('click', '.delemployee', function() {
    const employeeId = $(this).data('employee-id');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const employeeCard = $(this).closest('.employee-card');  

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
                url: '/delete-employee',
                type: 'POST',
                data: { employee_id: employeeId },
                dataType: 'json',
                success: function(response) {
                    removeLoader();
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        ).then(() => {
                            employeeCard.remove();  
                        });
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

 
 // get employee data
 $(document).on('click', '.edit-employee-btn', function () {
     var employeeId = $(this).data('employee-id');
     createLoader();
     $.ajax({
         url: "{{ route('employee.show', '') }}/" + employeeId, 
         type: "GET",  
         success: function (response) {
          removeLoader();
             if (response.success) {
              removeLoader();
                 $('#employeeeditform #employeeforminput_edit').val(response.employee.id);
                 if (response.employee.image) {
                     $('#employeeeditform #icon_edit').attr('src', "{{ asset('images') }}/" + response.employee.image);
                 }
                 $('#employeeeditform #name').val(response.employee.employee_name);
                 $('#employeeeditform #default').val(response.employee.area_id);
                 $('#employeeeditform #phone_1').val(response.employee.phone_1);
                 $('#employeeeditform #phone_2').val(response.employee.phone_2);
                 $('#employeeeditform #defaultSelect').val(response.employee.client_gender);
                 $('#employeeeditform #client_cnic').val(response.employee.client_cnic);
                 $('#employeeeditform #client_father_name').val(response.employee.client_father_name);
                 $('#employeeeditform #defaultSelectt').val(response.employee.client_residence);
                 $('#employeeeditform #client_salary').val(response.employee.client_salary);
                 $('#employeeeditform #client_permanent_address').val(response.employee.client_permanent_address);
                 $('#employeeeditform #client_residential_address').val(response.employee.client_residential_address);
                 $('.custom-modal.employeeedit').fadeIn();
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
 
 
 // Edit employee 
 $('#employeeeditform').on('submit', function (e) {
     e.preventDefault();
 
     var formData = new FormData(this); 
     var employeeId = $('#employeeforminput_edit').val(); 
     createLoader();
   
     $.ajax({
         url: "{{ route('employee.update', '') }}/" + employeeId,  
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
                     $('#employeeeditform')[0].reset();
                     $('.custom-modal.employeeedit').fadeOut();
 
                     $('.employee-name[data-id="' + employeeId + '"]').text(response.employee.employee_name);
                     $('.area-id[data-id="' + employeeId + '"]').text('(' + response.employee.area_id + ')');

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
     $('.custom-modal.employeeedit').fadeOut();
 });

 $(document).on('click', '.blockemployee', function (e) {
  e.preventDefault();
  var $this = $(this);
  var employeeId = $this.data('employee-id');

  Swal.fire({
      title: 'Are you sure?',
      text: "You want to block this employee!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, block it!'
  }).then((result) => {
      if (result.isConfirmed) {
          $.ajax({
              url: '/employee/block/' + employeeId,
              type: 'POST',
              data: {
                  _token: $('meta[name="csrf-token"]').attr('content')
              },
              success: function (response) {
                  if (response.success) {
                      Swal.fire('Blocked!', response.message, 'success');
                      $this.closest('tr').remove();
                  } else {
                      Swal.fire('Error!', response.message, 'error');
                  }
              },
              error: function () {
                  Swal.fire('Error!', 'Something went wrong.', 'error');
              }
          });
      }
  });
});

</script>
<script>
  document.getElementById('employeeSearch').addEventListener('input', function () {
    let query = this.value.toLowerCase();
    let employeeCards = document.querySelectorAll('.employee-card');

    employeeCards.forEach(function(card) {
      let name = card.querySelector('.employee-name').textContent.toLowerCase();
      if (name.includes(query)) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });
  });
</script>

  </body>
</html>
