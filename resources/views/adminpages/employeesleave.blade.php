<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addleave {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addleave:hover {
        background-color: #45a049;  
    }


.custom-modal.leave, 
.custom-modal.leaveedit {
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
                    
                        @if(auth()->user()->emplleave_add != '0')
                        <div class="d-flex align-items-center">
                            <a class="addleave" >Add leave</a>
                        </div>
                        @endif
                    </div>
                    
                    <h1 class="mx-3 list">Leave List</h1>

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
                                <th>Name</th>
                                <th>Leave Type</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Reason</th>
                                <th>Created At</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($leaves as $leave)
                                        <tr class="user-row" id="leave-{{ $leave->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$leave->id}}
                                               
                                                <td id="name">{{$leave->employee_name}}</td>  
                                                <td id="heading">{{$leave->leave_type}}</td>  
                                                <td id="from_date">{{$leave->from_date}}</td> 
                                                <td id="to_date">{{ $leave->to_date }}</td>
                                                <td id="leave_reason">{{ $leave->leave_reason }}</td>
                                                <td id="slug">{{$leave->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                    @if(auth()->user()->emplleave_update != '0')
                                                    <a data-leave-id="{{ $leave->id }}" class="btn btn-link btn-primary btn-lg edit-leave-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @endif
                                                
                                                    @if(auth()->user()->emplleave_delete != '0')
                                                    <a data-leave-id="{{ $leave->id }}" class="btn btn-link btn-danger delleave mt-2">
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



    <!-- Add leave data Modal -->
    <div style="display:none" class="custom-modal leave" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Add leave</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="leaveform">
                    <input type="hidden" id="leaveforminput_add" value=""/>
                    <div class="row mt-5">
                        
                        <div class="col-6">
                        <div class="form-group">
                            <label for="defaultSelect">Select Name</label>
                            <select
                              class="form-select form-control"
                              id="defaultSelect" name="employee_name"
                            >
                              <option>Select Name</option>
                             @foreach($employees as $employee)
                              <option>{{$employee->employee_name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="defaultSelect">Select Leave Type</label>
                                <select
                                  class="form-select form-control"
                                  id="defaultSelect" name="leave_type"
                                >
                                  <option>Select Leave Type</option>
                                 
                                  <option>Casual Leave</option>
                                  <option>Medical Leave</option>
                                  <option>Maternity Leave</option>
                                 
                                </select>
                              </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                              <label for="leave_reason">Leave Reason</label>
                              <textarea id="leave_reason" name="leave_reason" class="form-control" rows="4" placeholder="Enter  reason for leave..."></textarea>
                            </div>
                          </div>
                          

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="leaveadd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    


     <!-- Add leave edit Modal -->
     <div style="display:none" class="custom-modal leaveedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit leave</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="leaveeditform">
                    <input type="hidden" id="leaveforminput_edit" value=""/>
                    <div class="row mt-5">
                        
                        <div class="col-6">
                        <div class="form-group">
                            <label for="defaultSelect">Select Name</label>
                            <select
                              class="form-select form-control"
                              id="defaultSelect" name="employee_name"
                            >
                              <option>Select Name</option>
                             @foreach($employees as $employee)
                              <option>{{$employee->employee_name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="defaultSelect">Select Leave Type</label>
                                <select
                                  class="form-select form-control"
                                  id="defaultSelect" name="leave_type"
                                >
                                  <option>Select Leave Type</option>
                                 
                                  <option>Casual Leave</option>
                                  <option>Medical Leave</option>
                                  <option>Maternity Leave</option>
                                 
                                </select>
                              </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="to_date">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                              <label for="leave_reason">Leave Reason</label>
                              <textarea id="leave_reason" name="leave_reason" class="form-control" rows="4" placeholder="Enter  reason for leave..."></textarea>
                            </div>
                          </div>
                          

                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="leaveeditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
        $('.addleave').click(function() {
            $('.custom-modal.leave').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.leave').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addleave')) {
               $('.custom-modal.leave').fadeOut(); 
           }
       });
   });
   
   //to del leave
   $(document).on('click', '.delleave', function() {
       const leaveId = $(this).data('leave-id');
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
                   url: '/delete-leave',
                   type: 'POST',
                   data: { leave_id: leaveId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.addleave').show();
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
   
   $('#leaveform').on('submit', function (e) {
    e.preventDefault();   

    let formData = new FormData(this);
    createLoader();
    $.ajax({
        url: "{{ route('leave.store') }}",
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
                    $('#leaveform')[0].reset();
                    $('.custom-modal.leave').fadeOut();

                    const leave = response.leave;
                    const created_at = leave.created_at; 
                    const user_name = response.user_name;
                    const newRow = `
                        <tr data-leave-id="${leave.id}">
                            <td>${$('.table tbody tr').length + 1}</td>
                            <td>${leave.id}</td>
                            <td>${leave.employee_name}</td>
                            <td>${leave.leave_type}</td>
                            <td>${leave.from_date}</td>
                            <td>${leave.to_date}</td>
                            <td>${leave.leave_reason}</td>
                            <td>${created_at}</td>
                            <td>
                                <div class="form-button-action">
                                    <a id="leaveedit" data-leave-id="${leave.id}" class="btn btn-link btn-primary btn-lg edit-leave-btn">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-leave-id="${leave.id}" class="btn btn-link btn-danger mt-2 delleave">
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

   
   // get leave data
   $(document).on('click', '.edit-leave-btn', function () {
       var leaveId = $(this).data('leave-id');
       createLoader();
       $.ajax({
           url: "{{ route('leave.show', '') }}/" + leaveId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#leaveeditform #leaveforminput_edit').val(response.leave.id);
                   if (response.leave.image) {
                       $('#leaveeditform #icon_edit').attr('src', "{{ asset('images') }}/" + response.leave.image);
                   }
                   $('#leaveeditform #employee_name').val(response.leave.employee_name);
                   $('#leaveeditform #leave_type').val(response.leave.leave_type);
                   $('#leaveeditform #from_date').val(response.leave.from_date);
                   $('#leaveeditform #to_date').val(response.leave.to_date);
                   $('#leaveeditform #leave_reason').val(response.leave.leave_reason);
                   $('.custom-modal.leaveedit').fadeIn();
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
   
   
   // Edit leave 
   $('#leaveeditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var leaveId = $('#leaveforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('leave.update', '') }}/" + leaveId,  
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
                       $('#leaveeditform')[0].reset();
                       $('.custom-modal.leaveedit').fadeOut();
   
                       const leave = $(`a[data-leave-id="${leaveId}"]`).closest('tr');
                       leave.find('td:nth-child(2)').text(response.leave.id);
                       leave.find('td:nth-child(3)').text(response.leave.employee_name);
                       leave.find('td:nth-child(4)').text(response.leave.leave_type);
                       leave.find('td:nth-child(5)').text(response.leave.from_date);
                       leave.find('td:nth-child(6)').text(response.leave.to_date); 
                       leave.find('td:nth-child(7)').text(response.leave.leave_reason); 
                       leave.find('td:nth-child(8)').text(response.leave.created_at);
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
       $('.custom-modal.leaveedit').fadeOut();
   });
           </script>
  </body>
</html>
