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
                    
                       
                    </div>
                    
                    <h1 class="mx-3 list">Attendance List</h1>

                      <div class="card-body">
                        <div class="table-responsive">
                            <form id="attendance-form">
                                <button type="button" id="mark-present" class="btn btn-success mb-3">Mark Present</button>
                                <button type="button" id="mark-absent" class="btn btn-danger mb-3">Mark Absent</button>
                            
                                <table id="add-row" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"></th>
                                            <th>#</th>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Phone</th>
                                            <th>CNIc</th>
                                            <th>Created At</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter = 1; @endphp
                                        @foreach($employees as $employee)
                                            <tr class="user-row" id="employee-{{ $employee->id }}">
                                                <td><input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"></td>
                                                <td>{{ $counter }}</td>
                                                <td>{{ $employee->id }}</td>
                                                <td>{{ $employee->employee_name }}</td>
                                                <td>{{ $employee->area_id }}</td>
                                                <td>{{ $employee->phone_1 }}</td>
                                                <td>{{ $employee->client_cnic }}</td>
                                                <td>{{ $employee->created_at }}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                        <a data-employee-id="{{ $employee->id }}" class="btn btn-sm btn-success present-employee-btn">
                                                            <i class="fa fa-check"></i> Present
                                                        </a>
                                                        <a data-employee-id="{{ $employee->id }}" class="btn btn-sm btn-danger absent-employee-btn mx-1">
                                                            <i class="fa fa-times"></i> Absent
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $counter++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                            
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


    @include('adminpages.js')
    @include('adminpages.ajax')
    <script>
        $(document).on('click', '.present-employee-btn, .absent-employee-btn', function() {
            var employeeId = $(this).data('employee-id');
            var status = $(this).hasClass('present-employee-btn') ? 'present' : 'absent';
    
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to mark the employee as " + status + ".",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, mark as ' + status,
                cancelButtonText: 'No, cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/mark-attendancec',
                        type: 'POST',
                        data: {
                            employee_id: employeeId,
                            status: status,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Success', response.message, 'success');
                            } else {
                                Swal.fire('Error', 'There was an issue marking attendance.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'There was an error marking attendance.', 'error');
                        }
                    });
                } else {
                    Swal.fire('Cancelled', 'The action has been cancelled', 'info');
                }
            });
        });
    </script>

    <script>
        document.getElementById('mark-present').addEventListener('click', () => {
    markAttendance('present');
});

document.getElementById('mark-absent').addEventListener('click', () => {
    markAttendance('absent');
});

function markAttendance(status) {
    const formData = new FormData(document.getElementById('attendance-form'));
    formData.append('status', status);

    fetch('/mark-attendance', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}

document.getElementById('select-all').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[name="employee_ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

    </script>
  </body>
</html>
