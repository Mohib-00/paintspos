<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addfine {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addfine:hover {
        background-color: #45a049;  
    }


.custom-modal.fine, 
.custom-modal.fineedit {
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
                    
                        <div class="d-flex align-items-center">
                            <a class="addfine" >Add fine</a>
                        </div>
                    </div>
                    
                    <h1 class="mx-3 list">Fine List</h1>

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
                                <th>Employee</th>
                                <th>Narration</th>
                                <th>Fine</th>
                                <th>Date</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach ($fines as $fine)
                                        <tr class="user-row" id="fine-{{ $fine->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$fine->id}}
                                                <td id="name">{{$fine->employee->employee_name ?? 'N/A' }}</td>  
                                                <td id="slug">{{$fine->narration}}</td>
                                                <td id="slug">{{$fine->fine}}</td>
                                                <td id="slug">{{$fine->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                    <a data-fine-id="{{ $fine->id }}" class="btn btn-link btn-primary btn-lg edit-fine-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                
                                                    <a data-fine-id="{{ $fine->id }}" class="btn btn-link btn-danger delfine mt-2">
                                                        <i class="fa fa-times"></i>                    
                                                    </a>
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



    <!-- Add fine data Modal -->
 <div style="display:none" class="custom-modal fine" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 style="font-weight: bolder" class="modal-title ">Add fine</h2>
                <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                    &times;
                </button>
            </div>

            <form id="fineform">
                <input type="hidden" id="fineforminput_add" value=""/>
                <div class="row mt-5 px-4">
                    
                    <!-- Employee Dropdown -->
                    <div class="form-group col-md-12 mb-3">
                        <label for="employee_id">Select Employee</label>
                        <select id="employee_id" name="employee_id" class="form-control" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Narration Field -->
                    <div class="form-group col-md-12 mb-3">
                        <label for="narration">Narration</label>
                        <textarea id="narration" name="narration" class="form-control" rows="3" placeholder="Enter fine narration" required></textarea>
                    </div>

                    <!-- Fine Amount Field -->
                    <div class="form-group col-md-12 mb-3">
                        <label for="fine">Fine Amount</label>
                        <input type="number" id="fine" name="fine" class="form-control" step="0.01" placeholder="Enter fine amount" required>
                    </div>

                    <!-- Custom Created At Field -->
                    <div class="form-group col-md-12 mb-3">
                        <label for="created_at">Date</label>
                        <input type="datetime-local" id="created_at" name="created_at" class="form-control" >
                    </div>
                </div>

                <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                    <button id="fineadd" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                    <button type="button" class="btn btn-secondary closeModal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

    


     <!-- Add fine edit Modal -->
     <div style="display:none" class="custom-modal fineedit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit fine</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="fineeditform">
                    <input type="hidden" id="fineforminput_edit" value=""/>
                    <div class="row mt-5 px-4">
                    
                    <!-- Employee Dropdown -->
                    <div class="form-group col-md-12 mb-3">
                        <label for="employee_id">Select Employee</label>
                        <select id="employee_id_edit" name="employee_id" class="form-control" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Narration Field -->
                    <div class="form-group col-md-12 mb-3">
                        <label for="narration">Narration</label>
                        <textarea id="narration_edit" name="narration" class="form-control" rows="3" placeholder="Enter fine narration" required></textarea>
                    </div>

                    <!-- Fine Amount Field -->
                    <div class="form-group col-md-12 mb-3">
                        <label for="fine">Fine Amount</label>
                        <input type="number" id="fine_edit" name="fine" class="form-control" step="0.01" placeholder="Enter fine amount" required>
                    </div>

                    <!-- Custom Created At Field -->
                    <div class="form-group col-md-12 mb-3">
                        <label for="created_at">Date</label>
                        <input type="datetime-local" id="created_at_edit" name="created_at" class="form-control" >
                    </div>
                </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="fineeditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
                        <button type="button" class="btn btn-secondary closeModal">Close</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    @include('adminpages.js')
    @include('adminpages.ajax')
<script>
    $(document).on('submit', '#fineform', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Submitting...',
            text: 'Please wait while the fine is being saved.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        let formData = {
            employee_id: $('#employee_id').val(),
            narration: $('#narration').val(),
            fine: $('#fine').val(),
            created_at: $('#created_at').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: "{{ route('fine.store') }}",
            method: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#fineform')[0].reset();
                    $('.custom-modal.fine').fadeOut();

                    let fine = response.fine;
                    let rowCount = $('table tbody tr').length + 1;

                    let newRow = `
                        <tr class="user-row" id="fine-${fine.id}">
                            <td>${rowCount}</td>
                            <td>${fine.id}</td>
                            <td id="name">${fine.employee?.employee_name ?? 'N/A'}</td>
                            <td id="slug">${fine.narration}</td>
                            <td id="slug">${fine.fine}</td>
                            <td id="slug">${fine.created_at}</td>
                            <td>
                                <div class="form-button-action">
                                    <a data-fine-id="${fine.id}" class="btn btn-link btn-primary btn-lg edit-fine-btn">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a data-fine-id="${fine.id}" class="btn btn-link btn-danger delfine mt-2">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;

                    $('table tbody').append(newRow);
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'An error occurred while saving.',
                });
            }
        });
    });


    $(document).on('click', '.edit-fine-btn', function () {
    const fineId = $(this).data('fine-id');

    $.ajax({
        url: `/fine/${fineId}`,
        method: 'GET',
        success: function (fine) {
            $('#fineforminput_edit').val(fine.id);
            $('#employee_id_edit').val(fine.employee_id);
            $('#narration_edit').val(fine.narration);
            $('#fine_edit').val(fine.fine);
            const datetime = new Date(fine.created_at);
            const formattedDateTime = datetime.toISOString().slice(0, 16); 
            $('#created_at_edit').val(formattedDateTime);

            $('.custom-modal.fineedit').fadeIn();
        },
        error: function () {
            Swal.fire('Error', 'Unable to fetch fine data.', 'error');
        }
    });
});



$(document).on('submit', '#fineeditform', function (e) {
    e.preventDefault();

    let fineId = $('#fineforminput_edit').val();

    Swal.fire({
        title: 'Updating...',
        text: 'Please wait while the fine is being updated.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: `/fine/update/${fineId}`,
        method: 'PUT',
        data: {
            employee_id: $('#employee_id_edit').val(),
            narration: $('#narration_edit').val(),
            fine: $('#fine_edit').val(),
            created_at: $('#created_at_edit').val(),
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Updated',
                text: response.message,
                confirmButtonText: 'OK'
            });

            // Update row in table
            const fine = response.fine;

            let updatedRow = `
                <td>${$('#fine-' + fine.id + ' td:first').text()}</td>
                <td>${fine.id}</td>
                <td id="name">${fine.employee?.employee_name ?? 'N/A'}</td>
                <td id="slug">${fine.narration}</td>
                <td id="slug">${fine.fine}</td>
                <td id="slug">${fine.created_at}</td>
                <td>
                    <div class="form-button-action">
                        <a data-fine-id="${fine.id}" class="btn btn-link btn-primary btn-lg edit-fine-btn">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a data-fine-id="${fine.id}" class="btn btn-link btn-danger delfine mt-2">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </td>
            `;

            $('#fine-' + fine.id).html(updatedRow);

            $('.custom-modal.fineedit').fadeOut();
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'Update failed.',
            });
        }
    });
});


$(document).on('click', '.delfine', function () {
    let fineId = $(this).data('fine-id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This fine will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e3342f',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/fine/${fineId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });

                    $('#fine-' + fineId).remove();
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong while deleting.'
                    });
                }
            });
        }
    });
});



    $(document).ready(function () {
        $('.addfine').on('click', function () {
            $('.custom-modal.fine').fadeIn();
        });

        $('.closeModal').on('click', function () {
            $('.custom-modal.fine').fadeOut();
        });
    });

    $('.closeModal').on('click', function () {
    $('.custom-modal').fadeOut();
});

</script>


  </body>
</html>
