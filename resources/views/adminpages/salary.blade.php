<!DOCTYPE html>
<html lang="en">
<head>
  @include('adminpages.css')
  <style>
    .form-button-action {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .custom-btn {
      display: inline-flex;
      align-items: center;
      padding: 8px 14px;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      font-weight: 500;
      transition: 0.3s ease;
    }

    .custom-btn i {
      margin-right: 6px;
    }

    .btn-pay { background-color: cadetblue; }
    .btn-advance { background-color: #007bff; }
    .btn-bonus { background-color: #1a2035; }

    .custom-btn:hover {
      transform: scale(1.05);
      opacity: 0.9;
      color: white;
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
                    <i class="fas fa-file-excel"></i> Excel
                  </button>
                </div>
              </div>

              <h1 class="mx-3 list">Pay Salary</h1>

              <div class="card-body">
                <div class="table-responsive">
                  <table id="add-row" class="display table table-striped table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Phone</th>
                        <th>Join Date</th>
                        <th>Salary</th>
                        <th>Paid</th>
                        <th>Bonus</th>
                        <th>Fine</th>
                        <th style="width: 10%">Action</th>
                      </tr>
                    </thead>
                  <tbody>
  @foreach($employees as $index => $employee)
    @php
      $todaySalaries = $employee->salaries ?? collect(); 
      $totalPaid = $todaySalaries->sum('paid');
      $totalBonus = $todaySalaries->sum('bonus');
      $salaryDate = $todaySalaries->first() ? $todaySalaries->first()->created_at->format('Y-m-d') : '-';
      $totalFines = \App\Models\Fine::where('employee_id', $employee->id)->sum('fine');

    @endphp

    <tr id="employee-{{ $employee->id }}">
      <td>{{ $index + 1 }}</td>
      <td>{{ $employee->employee_name }}</td>
      <td>{{ $employee->area_id }}</td>
      <td>{{ $employee->phone_1 }}</td>
      <td style="white-space: nowrap">{{ $employee->created_at }}</td>
      <td>{{ number_format($employee->client_salary) }}</td>
      <td>{{ number_format($totalPaid) }}</td>
      <td>{{ number_format($totalBonus) }}</td>
      <td>{{ number_format($totalFines) }}</td>
      <td>
        <div class="form-button-action">
          <a href="javascript:void(0)" class="custom-btn btn-pay" data-employee-id="{{ $employee->id }}" onclick="loadpaysalry(this)">
            <i class="fas fa-money-bill-wave"></i> Pay
          </a>
          <a href="javascript:void(0)" class="custom-btn btn-advance" data-employee-id="{{ $employee->id }}">
            <i class="fas fa-hand-holding-usd"></i> Advance
          </a>
          <a href="javascript:void(0)" class="custom-btn btn-bonus" data-employee-id="{{ $employee->id }}">
            <i class="fas fa-gift"></i> Bonus
          </a>
        </div>
      </td>
    </tr>
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

<!-- Advance Salary Modal -->
<div class="modal fade" id="advanceSalaryModal" tabindex="-1" aria-labelledby="advanceSalaryLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="advanceSalaryForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Advance Salary</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="employeeId" name="employee_id" />
          <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="updated_at" />
          </div>
           <div class="mb-3">
            <label for="salary" class="form-label">Fine</label>
            <input type="text" class="form-control" id="fine" readonly />
          </div>
          <div class="mb-3">
            <label for="salary" class="form-label">Salary</label>
            <input type="text" class="form-control" id="salary" readonly />
          </div>
          <div class="mb-3">
            <label for="paid" class="form-label">Paid</label>
            <input type="text" class="form-control" id="paid" readonly />
          </div>
          <div class="mb-3">
            <label for="advance" class="form-label">Advance Amount</label>
            <input type="number" class="form-control" id="advance" name="paid" required />
          </div>
          <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Advance</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

@include('adminpages.js')
@include('adminpages.ajax')

<script>
 $(document).ready(function () {
  function formatCurrency(value) {
    return parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

 function parseCurrency(value) {
  if (value === null || value === undefined) return 0;
  return parseFloat(String(value).replace(/,/g, '')) || 0;
}


  $('.btn-advance').on('click', function () {
    const employeeId = $(this).data('employee-id');
    $('#advanceSalaryForm')[0].reset();
    $('#employeeId').val(employeeId);

    $.ajax({
      url: `/employee/${employeeId}/salary-info`,
      method: 'GET',
    success: function (response) {
  const fine = parseCurrency(response.fine);
  const originalSalary = parseCurrency(response.client_salary);
  const paid = parseCurrency(response.paid);
  const netSalary = originalSalary - fine;
  const remaining = netSalary - paid;

  $('#fine').val(formatCurrency(fine));
  $('#salary').val(formatCurrency(netSalary));
  $('#paid').val(formatCurrency(paid));

  $('#advance').val('');
  $('#advance').attr('max', remaining);

  new bootstrap.Modal($('#advanceSalaryModal')).show();
},
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Failed to load employee salary info.'
        });
      }
    });
  });

  $('#advance').on('input', function () {
    const max = parseFloat($(this).attr('max'));
    const val = parseFloat($(this).val());

    if (val > max) {
      Swal.fire({
        icon: 'warning',
        title: 'Invalid Amount',
        text: `Advance amount cannot exceed remaining salary: ${formatCurrency(max)}`
      });
      $(this).val(max);
    } else if (val < 0) {
      $(this).val(0);
    }
  });

  $('#advanceSalaryForm').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      url: '{{ route("salary.advance.store") }}',
      method: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: response.message,
          timer: 2000,
          timerProgressBar: true,
          willClose: () => {
            $('#advanceSalaryModal').modal('hide');
            loadsalary();
          }
        });
      },
      error: function (xhr) {
        let message = 'Error saving advance';
        if(xhr.responseJSON && xhr.responseJSON.message) {
          message = xhr.responseJSON.message;
        }
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: message
        });
      }
    });
  });
});

</script>


</body>
</html>
