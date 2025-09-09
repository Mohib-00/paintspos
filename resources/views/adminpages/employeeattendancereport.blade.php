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

                        <form action="{{ route('attendance.report') }}" method="GET" class="row g-3 p-4">
                            <div class="col-md-3">
                                <label for="from_date" class="form-label">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="employee_id" class="form-label">Select Employee</label>
                                <select name="employee_id" id="employee_id" class="form-select">
                                    <option value="">All Employees</option>
                                    @foreach($employes as $emp)
                                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
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
                      
                      <h1 class="mx-3 list">Attendance Report</h1>
  
                        <div class="card-body">
                          <div class="table-responsive">
                              <table id="add-row" class="display table table-striped table-hover">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>Id</th>
                                          <th>Employee</th>
                                          <th>Designation</th> 
                                          <th>Date</th>
                                          <th>Attendance</th>
                                          <th>Created At</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @php $counter = 1; @endphp
                                      @foreach($attendances as $attendance)
                                          <tr class="user-row" id="employee-{{ $attendance->id }}">
                                              <td>{{ $counter }}</td>
                                              <td>{{ $attendance->user_id }}</td>
                                              <td>{{ $attendance->employee_name }}</td>
                                              <td>{{ $attendance->area_id ?? 'N/A' }}</td> 
                                              <td>{{ \Carbon\Carbon::parse($attendance->from_date)->format('d-m-Y') }}</td>
                                              <td>{{ ucfirst($attendance->status) }}</td>
                                              <td>{{ $attendance->created_at->format('d-m-Y H:i') }}</td>
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


    @include('adminpages.js')
    @include('adminpages.ajax')
  
  </body>
</html>
