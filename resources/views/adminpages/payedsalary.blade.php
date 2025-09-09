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

        @keyframes slideDown {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
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

                            <!-- Filter Form -->
                        <form action="{{ route('searchpayedsalary') }}" method="GET" class="row g-3 p-4">
    <div class="col-md-3">
        <label for="from_date" class="form-label">From Date</label>
        <input type="date" id="from_date" name="from_date" class="form-control" value="{{ old('from_date', $fromDate ?? '') }}">
    </div>
    <div class="col-md-3">
        <label for="to_date" class="form-label">To Date</label>
        <input type="date" id="to_date" name="to_date" class="form-control" value="{{ old('to_date', $toDate ?? '') }}">
    </div>
    <div class="col-md-3">
        <label for="employee_id" class="form-label">Select Employee</label>
        <select name="employee_id" id="employee_id" class="form-select">
            <option value="">All Employees</option>
            @foreach($employeesalll as $emp)
                <option value="{{ $emp->id }}" {{ (string)($employeeId ?? '') === (string)$emp->id ? 'selected' : '' }}>
                    {{ $emp->employee_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-search"></i> Search
        </button>
    </div>
</form>


                            <!-- Header Buttons -->
                            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                  <button class="btn btn-sm btn-outline-primary me-2 print-table">
                    <i class="fas fa-print"></i> Print
                  </button>
                  <button class="btn btn-sm btn-outline-danger export-pdf">
                    <i class="fas fa-file-pdf"></i> PDF
                  </button>
                  <button class="btn btn-sm btn-outline-primary export-excel">
                    <i class="fas fa-file-excel"></i> Excel
                  </button>
                </div>
              </div>
                            <!-- Salary Table -->
                            <h1 class="mx-3 list">Salary List</h1>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Phone</th>
                                                <th>Payed Date</th>
                                                <th>Salary</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $counter = 1; @endphp
                                            @foreach($employees as $employee)
                                                @foreach($employee->salaries as $salry)
                                                    <tr class="user-row" id="salry-{{ $salry->id }}">
                                                        <td>{{ $counter++ }}</td>
                                                        <td>{{ $employee->employee_name }}</td>
                                                        <td>{{ $employee->area_id }}</td>
                                                        <td>{{ $employee->phone_1 }}</td>
                                                        <td>{{ $salry->created_at }}</td>
                                                        <td>{{ $salry->paid }}</td>
                                                        <td>
                                                            <div class="form-button-action" style="display: flex; gap: 8px; align-items: center;">
                                                              <a 
   onclick="loadsalryinvoicemodel(this); return false;"
   data-salry-id="{{ $salry->id }}"
   class="btn btn-link btn-primary btn-lg salry-invoice icon-btn">
    <i class="icon-eye"></i>
</a>


                                                                <a href="javascript:void(0);"
                                                                   data-salry-id="{{ $salry->id }}"
                                                                   class="btn btn-link btn-danger delsalry icon-btn">
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- card -->
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="salryInvoiceModal" tabindex="-1" aria-labelledby="salryInvoiceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="salryInvoiceForm">
      @csrf
      <input type="hidden" id="salry_id" name="salry_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="salryInvoiceModalLabel">Edit Salary Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="paid" class="form-label">Paid Amount</label>
            <input type="number" step="0.01" class="form-control" id="paid" name="paid" required>
          </div>
          <!-- Add other input fields as needed -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Payment</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>


        @include('adminpages.footer')
    </div>
</div>

@include('adminpages.js')
@include('adminpages.ajax')


<script>
    function loadsalryinvoicemodel(el) {
    const salryId = $(el).data('salry-id');

    $.ajax({
        url: `/admin/salry_invoice/${salryId}`,
        type: 'GET',
        success: function(response) {
            $('#salry_id').val(response.id);
            $('#paid').val(response.paid);
            $('#salryInvoiceModal').modal('show');
        },
        error: function() {
            Swal.fire('Error', 'Failed to load salary data.', 'error');
        }
    });
}

$('#salryInvoiceForm').on('submit', function(e) {
    e.preventDefault();

    const salryId = $('#salry_id').val();
    const paid = $('#paid').val();

    $.ajax({
        url: `/admin/salry_invoice/${salryId}`, 
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            paid: paid
        },
        success: function(response) {
            Swal.fire('Success', 'Salary payment updated successfully.', 'success');
            $('#salryInvoiceModal').modal('hide');
        },
        error: function() {
            Swal.fire('Error', 'Failed to update salary payment.', 'error');
        }
    });
});

</script>

<script>
    $(document).on('click', '.delsalry', function(e) {
        e.preventDefault();
        const salryId = $(this).data('salry-id');
        const button = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: "This salary will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/salrys/' + salryId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success');
                        button.closest('tr').remove();
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong while deleting.', 'error');
                    }
                });
            }
        });
    });
</script>
</body>
</html>
