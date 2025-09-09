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

                        <form  method="GET" class="row g-3 p-4">
                            <div class="col-md-3">
                                <label for="from_date" class="form-label">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            
                            <div class="col-md-3 align-self-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </form>
  
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
                      <h1 style="font-weight:bolder" class="mx-3 list">{{ $employees->employee_name }}</h1>
  
                        <div class="card-body">
                          <div class="table-responsive">
                           <form id="salaryForm">
    <table class="display table table-striped table-hover">
        <thead>
            <tr>
                <th style="background:#1a2035; color:white">#</th>
                <th style="background:#1a2035; color:white">Salary</th>
                <th style="background:#1a2035; color:white">Total</th>
            </tr>
        </thead>
      <tbody>
    @php
        $counter = 1;

        $todaySalaries = $employees->salaries ?? collect();

        $paid = $todaySalaries->sum('paid');
        $bonus = $todaySalaries->sum('bonus');

        $basic = $employees->client_salary ?? 0;

        $total = $basic - $paid + $bonus;
    @endphp

    @php
    use App\Models\Fine;

    $employeeId = request()->segment(3); 
    $fineAmount = Fine::where('employee_id', $employeeId)->sum('fine');
@endphp





    <tr class="user-row" id="employee-{{ $employees->id }}">
        <td>{{ $counter++ }}</td>
        <td>Basic Salary</td>
        <td>{{ number_format($basic) }}</td>
    </tr>

    <tr class="user-row" id="employee-{{ $employees->id }}">
        <td>{{ $counter++ }}</td>
        <td>Fine</td>
        <td>{{ number_format($fineAmount) }}</td>
    </tr>

    <tr class="user-row">
        <td>{{ $counter++ }}</td>
        <td>Given Salary</td>
        <td>{{ number_format($paid) }}</td>
    </tr>

    <tr class="user-row">
        <td>{{ $counter++ }}</td>
        <td>Bonus</td>
        <td>{{ number_format($bonus) }}</td>
    </tr>
</tbody>


        <tfoot>
            <tr style="font-weight: bold; background-color: #f0f0f0;">
                <td style="background:#1a2035; color:white" colspan="2" class="text-right">Total</td>
                <td style="background:#1a2035; color:white">{{ number_format($total - $fineAmount) }}</td>
            </tr>
        </tfoot>

        <input type="hidden" name="paid" id="paidAmount" value="{{ ($basic ?? 0) - ($fineAmount ?? 0) - ($paid ?? 0) }}">
    </table>

    <div style="margin-top: 20px; text-align: center;">
        @if($paid != $basic)
            <button type="submit" class="btn btn-success" style="padding: 10px 30px; font-size: 16px;">
                Submit
            </button>
        @endif
    </div>
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
    document.getElementById('salaryForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const paidAmount = document.getElementById('paidAmount').value;
        const url = window.location.pathname; 
        const employeeId = url.split('/').pop();

        fetch(`/pay_salary/${employeeId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                paid: paidAmount
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                Swal.fire({
                    icon: 'success',
                    title: 'Salary Saved Successfully',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        loadsalary(); 
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred.'
            });
        });
    });
</script>

  
  </body>
</html>
