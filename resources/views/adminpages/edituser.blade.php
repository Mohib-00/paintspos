<!DOCTYPE html>
<html lang="en">
  <head>
    @include('adminpages.css')
    <style>
      body {
        color: #1a202c;
        background-color: #e2e8f0;
        text-align: left;
      }
      .main-body {
        padding: 15px;
      }
      .card {
        background-color: #fff;
        border: 0 solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.1), 0 1px 2px rgba(0,0,0,.06);
        display: flex;
        flex-direction: column;
      }
      .card-body {
        padding: 1rem;
      }
      .gutters-sm {
        margin: 0 -8px;
      }
      .gutters-sm > .col,
      .gutters-sm > [class*=col-] {
        padding: 0 8px;
      }
      .mb-3 {
        margin-bottom: 1rem !important;
      }
      .form-label {
        display: block;
        margin-bottom: 0.5rem;
      }
      .form-check-inline {
        margin-right: 15px;
      }

         .permissions-container {
    max-width: 1200px;
    margin: 30px auto;
    padding: 25px;
    border-radius: 10px;
    background: #ffffff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    font-family: "Segoe UI", sans-serif;
  }

  .permissions-container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #343a40;
  }

  table.permissions-table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
    border-radius: 10px;
    overflow: hidden;
  }

  .permissions-table th,
  .permissions-table td {
    padding: 14px;
    border: 1px solid #ddd;
  }

  .permissions-table thead {
    background-color: #343a40;
    color: white;
  }

  .permissions-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
  }

  .permissions-table tbody tr:hover {
    background-color: #e9ecef;
  }

  .text-start {
    text-align: left;
    padding-left: 12px;
  }

  .submit-btn {
    margin-top: 20px;
    display: block;
    width: 100%;
    padding: 12px;
    background-color:#1a202c;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
  }

  .submit-btn:hover {
    background-color: #218838;
  }
    </style>
  </head>
  <body>
    <div class="wrapper">
      @include('adminpages.sidebar')

      <div class="main-panel">
        @include('adminpages.header')

        <div class="container">
          <div class="main-body">
            <div class="row gutters-sm">
              <div class="col-md-4 mb-3">
                <div class="card">
                  <div class="card-body text-center">
                   <img width="100" height="100" 
                   src="{{ $users->image ? asset($users->image) : 'https://bootdey.com/img/Content/avatar/avatar7.png' }}" 
                   alt="Admin" class="rounded-circle">

                    <div class="mt-3">
                      <h4>{{ $users->name }}</h4>
                      <p class="text-secondary mb-1">{{ $users->email }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-8">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="white_shd full margin_bottom_30 mt-4">
                      <div class="full graph_head">
                        <h2 class="heading1 margin_0">Change Profile</h2>
                      </div>
                      <div class="full price_table padding_infor_info">
                       <form id="usereditform">
  <div class="row">
    <div class="col-sm-6">
      <label for="image" class="form-label">Image</label>
      <input type="file" class="form-control" id="image" name="image">
     
    </div>

    <div class="col-sm-6">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $users->name) }}">
    </div>

    <div class="col-sm-6 mt-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $users->email) }}">
    </div>

    <div class="col-sm-6 mt-3">
      <label class="form-label">Role</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="roleManager" name="role" value="manager" {{ $users->role === 'manager' ? 'checked' : '' }}>
        <label class="form-check-label" for="roleManager">Manager</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="roleOperator" name="role" value="operator" {{ $users->role === 'operator' ? 'checked' : '' }}>
        <label class="form-check-label" for="roleOperator">Operator</label>
      </div>
    </div>

    <div class="col-sm-6 mt-3">
      <label class="form-label">Gender</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="genderMale" name="gender" value="male" {{ $users->gender === 'male' ? 'checked' : '' }}>
        <label class="form-check-label" for="genderMale">Male</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="genderFemale" name="gender" value="female" {{ $users->gender === 'female' ? 'checked' : '' }}>
        <label class="form-check-label" for="genderFemale">Female</label>
      </div>
    </div>

    <div class="col-sm-6 mt-3">
      <label class="form-label">Dashboard</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="dashboardShow" name="dashboard" value="show" {{ $users->dashboard === 'show' ? 'checked' : '' }}>
        <label class="form-check-label" for="dashboardShow">Show</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="dashboardHide" name="dashboard" value="hide" {{ $users->dashboard === 'hide' ? 'checked' : '' }}>
        <label class="form-check-label" for="dashboardHide">Hide</label>
      </div>
    </div>

    <div class="col-sm-6 mt-3">
      <label class="form-label">Discount</label>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="discountYes" name="discount" value="yes" {{ $users->discount === 'yes' ? 'checked' : '' }}>
        <label class="form-check-label" for="discountYes">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="discountNo" name="discount" value="no" {{ $users->discount === 'no' ? 'checked' : '' }}>
        <label class="form-check-label" for="discountNo">No</label>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-12">
      <button type="button" id="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="white_shd full margin_bottom_30 mt-4">
                      <div class="full graph_head">
                        <h2 class="heading1 margin_0">Change Password</h2>
                      </div>
                      <div class="full price_table padding_infor_info">
                        <form id="changePasswordForm" method="POST" onsubmit="return false;">
                        <div class="row">
                          <div class="col-sm-6">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                              <input type="password" class="form-control" id="password" name="password">
                              <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="fa fa-eye"></i>
                              </span>
                            </div>
                            <span class="text-danger" id="passwordError"></span>
                          </div>

                          <div class="col-sm-6">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <div class="input-group">
                              <input type="password" class="form-control" id="confirm_password" name="password_confirmation">
                              <span class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;">
                                <i class="fa fa-eye"></i>
                              </span>
                            </div>
                            <span class="text-danger" id="confirmPasswordError"></span>
                          </div>
                        </div>

                        <div class="row mt-3">
                          <div class="col-12">
                            <button type="button" id="submitttttttt" class="btn btn-primary">Submit</button>
                          </div>
                        </div>
                        </form>

                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>


        <div class="permissions-container">
  <h2>User Permissions</h2>
  <form>

    <table class="permissions-table">
      <thead>
        <tr>
          <th rowspan="2">Pages</th>
          <th colspan="5">Permissions</th>
        </tr>
        <tr>
          <th>Read</th>
          <th>Add</th>
          <th>Update</th>
          <th>Delete</th>
          <th>Past Date</th>
        </tr>
      </thead>
      <tbody>
          <tr>
                <td class="text-start">Users</td>
                <td><input type="checkbox" name="user_read" {{ $users->user_read == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="user_add" {{ $users->user_add == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="user_update" {{ $users->user_update == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="user_delete" {{ $users->user_delete == 0 ? 'checked' : '' }}></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-start">POS</td>
                <td><input type="checkbox" name="pos_read" {{ $users->pos_read == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="pos_add" {{ $users->pos_add == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="pos_update" {{ $users->pos_update == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="pos_delete" {{ $users->pos_delete == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="pos_pastdate" {{ $users->pos_pastdate == 0 ? 'checked' : '' }}></td>
            </tr>

           <tr>
                <td class="text-start">Sale List</td>
                <td><input type="checkbox" name="sale_read" {{ $users->sale_read == 0 ? 'checked' : '' }}></td>
                <td></td>
                <td><input type="checkbox" name="sale_update" {{ $users->sale_update == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="sale_delete" {{ $users->sale_delete == 0 ? 'checked' : '' }}></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-start">Purchase</td>
                <td><input type="checkbox" name="pur_read" {{ $users->pur_read == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="pur_add" {{ $users->pur_add == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="pur_update" {{ $users->pur_update == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="pur_delete" {{ $users->pur_delete == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="pur_pastdate" {{ $users->pur_pastdate == 0 ? 'checked' : '' }}></td>
            </tr>

            <tr>
                <td class="text-start">Purchase Return</td>
                <td><input type="checkbox" name="purchase_return_read" {{ $users->purchase_return_read == 0 ? 'checked' : '' }}></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td class="text-start">Accounts</td>
                <td><input type="checkbox" name="acc_read" {{ $users->acc_read == 0 ? 'checked' : '' }}></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td class="text-start">Vouchers</td>
                <td><input type="checkbox" name="vo_read" {{ $users->vo_read == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="vo_add" {{ $users->vo_add == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="vo_update" {{ $users->vo_update == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="vo_delete" {{ $users->vo_delete == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="vo_pastdate" {{ $users->vo_pastdate == 0 ? 'checked' : '' }}></td>
            </tr>

         <tr>
                <td class="text-start">Pay Salary</td>
                <td><input type="checkbox" name="paysalary_read" {{ $users->paysalary_read == 0 ? 'checked' : '' }}></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td class="text-start">Payed Salary</td>
                <td><input type="checkbox" name="payedsalary_read" {{ $users->payedsalary_read == 0 ? 'checked' : '' }}></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td class="text-start">Reports</td>
                <td><input type="checkbox" name="reports_read" {{ $users->reports_read == 0 ? 'checked' : '' }}></td>
                <td></td><td></td><td></td><td></td>
            </tr>
           <tr>
                <td class="text-start">Sale Report</td>
                <td><input type="checkbox" name="salereport_read" {{ $users->salereport_read == 0 ? 'checked' : '' }}></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td class="text-start">Stock Report</td>
                <td><input type="checkbox" name="stockreport_read" {{ $users->stockreport_read == 0 ? 'checked' : '' }}></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td class="text-start">Day Close Report</td>
                <td><input type="checkbox" name="dcreport_read" {{ $users->dcreport_read == 0 ? 'checked' : '' }}></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td class="text-start">General Ledger</td>
                <td><input type="checkbox" name="gl_read" {{ $users->gl_read == 0 ? 'checked' : '' }}></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            <tr>
                <td class="text-start">Vendor</td>
                <td><input type="checkbox" name="vend_read" {{ $users->vend_read == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="vend_add" {{ $users->vend_add == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="vend_update" {{ $users->vend_update == 0 ? 'checked' : '' }}></td>
                <td><input type="checkbox" name="vend_delete" {{ $users->vend_delete == 0 ? 'checked' : '' }}></td>
                <td></td>
            </tr>
        <tr>
  <td class="text-start">Customers</td>
  <td><input type="checkbox" name="custmers_read" {{ $users->custmers_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="custmers_add" {{ $users->custmers_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="custmers_update" {{ $users->custmers_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="custmers_delete" {{ $users->custmers_delete == 0 ? 'checked' : '' }}></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Area</td>
  <td><input type="checkbox" name="area_read" {{ $users->area_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="area_add" {{ $users->area_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="area_update" {{ $users->area_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="area_delete" {{ $users->area_delete == 0 ? 'checked' : '' }}></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Blocked Customers</td>
  <td><input type="checkbox" name="block_read" {{ $users->block_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="block_add" {{ $users->block_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="block_update" {{ $users->block_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="block_delete" {{ $users->block_delete == 0 ? 'checked' : '' }}></td>
  <td></td>
</tr>

        <tr>
  <td class="text-start">Employee</td>
  <td><input type="checkbox" name="empl_read" {{ $users->empl_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="empl_add" {{ $users->empl_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="empl_update" {{ $users->empl_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="empl_delete" {{ $users->empl_delete == 0 ? 'checked' : '' }}></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Employee Leave</td>
  <td><input type="checkbox" name="emplleave_read" {{ $users->emplleave_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="emplleave_add" {{ $users->emplleave_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="emplleave_update" {{ $users->emplleave_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="emplleave_delete" {{ $users->emplleave_delete == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="emplleave_pastdate" {{ $users->emplleave_pastdate == 0 ? 'checked' : '' }}></td>
</tr>

<tr>
  <td class="text-start">Designation</td>
  <td><input type="checkbox" name="dgnation_read" {{ $users->dgnation_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="dgnation_add" {{ $users->dgnation_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="dgnation_update" {{ $users->dgnation_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="dgnation_delete" {{ $users->dgnation_delete == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="dgnation_pastdate" {{ $users->dgnation_pastdate == 0 ? 'checked' : '' }}></td>
</tr>

<tr>
  <td class="text-start">Attendance</td>
  <td><input type="checkbox" name="atndnce_read" {{ $users->atndnce_read == 0 ? 'checked' : '' }}></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Attendance Report</td>
  <td><input type="checkbox" name="atndncereport_read" {{ $users->atndncereport_read == 0 ? 'checked' : '' }}></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Company List</td>
  <td><input type="checkbox" name="cmppny_read" {{ $users->cmppny_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="cmppny_add" {{ $users->cmppny_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="cmppny_update" {{ $users->cmppny_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="cmppny_delete" {{ $users->cmppny_delete == 0 ? 'checked' : '' }}></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Category List</td>
  <td><input type="checkbox" name="ctgry_read" {{ $users->ctgry_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="ctgry_add" {{ $users->ctgry_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="ctgry_update" {{ $users->ctgry_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="ctgry_delete" {{ $users->ctgry_delete == 0 ? 'checked' : '' }}></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Sub Category List</td>
  <td><input type="checkbox" name="subctgry_read" {{ $users->subctgry_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="subctgry_add" {{ $users->subctgry_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="subctgry_update" {{ $users->subctgry_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="subctgry_delete" {{ $users->subctgry_delete == 0 ? 'checked' : '' }}></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Product List</td>
  <td><input type="checkbox" name="product_read" {{ $users->product_read == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="product_add" {{ $users->product_add == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="product_update" {{ $users->product_update == 0 ? 'checked' : '' }}></td>
  <td><input type="checkbox" name="product_delete" {{ $users->product_delete == 0 ? 'checked' : '' }}></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Product Price List</td>
  <td><input type="checkbox" name="productprice_read" {{ $users->productprice_read == 0 ? 'checked' : '' }}></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

<tr>
  <td class="text-start">Product Import</td>
  <td><input type="checkbox" name="productimport_read" {{ $users->productimport_read == 0 ? 'checked' : '' }}></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>


         

      </tbody>
    </table>

    <button type="submit" class="submit-btn">Save Permissions</button>
  </form>
</div>


        </div>

        @include('adminpages.footer')
      </div>
    </div>


    @include('adminpages.js')
    @include('adminpages.ajax')

   <script>
  $('form').on('submit', function (e) {
    e.preventDefault();

    const pathSegments = window.location.pathname.split('/');
    const userId = pathSegments[pathSegments.length - 1]; 
    const formData = $(this).serialize();

    Swal.fire({
      title: 'Saving permissions...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    $.ajax({
      url: '/permissions/save/' + userId,
      method: 'POST',
      data: formData,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function (response) {
        Swal.close(); 

        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: response.message || 'Permissions saved successfully!',
          showConfirmButton: true,  
          confirmButtonText: 'OK'
        });
      },
      error: function (xhr) {
        Swal.close(); 

        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to save permissions.',
          footer: xhr.responseText || '',
          showConfirmButton: true,  
          confirmButtonText: 'OK'
        });
        console.error(xhr.responseText);
      }
    });
  });
</script>




      <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    
    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        const confirmPasswordInput = document.getElementById('confirm_password');
        const icon = this.querySelector('i');
        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            confirmPasswordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
            </script>

  <script>
  document.getElementById('submit').addEventListener('click', function () {
    const form = document.getElementById('usereditform');
    const formData = new FormData(form);

    const urlParts = window.location.pathname.split('/');
    const userId = urlParts[urlParts.length - 1];

    Swal.fire({
      title: 'Updating user...',
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });

    fetch(`/user/update/${userId}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      Swal.close(); 

      if (data.status === 'success') {
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: data.message,
          confirmButtonColor: '#3085d6'
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: data.message || 'An error occurred.',
          confirmButtonColor: '#d33'
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.close(); 

      Swal.fire({
        icon: 'error',
        title: 'Request Failed',
        text: 'Something went wrong while updating the user.',
        confirmButtonColor: '#d33'
      });
    });
  });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

  document.getElementById('submitttttttt').addEventListener('click', function () {
    const urlParts = window.location.pathname.split('/');
    const userId = urlParts[urlParts.length - 1];

    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    document.getElementById('passwordError').innerText = '';
    document.getElementById('confirmPasswordError').innerText = '';


    fetch(`/admin/change-password/${userId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        password: password,
        password_confirmation: confirmPassword,
      }),
    })
    .then(response => {
      if (!response.ok) return response.json().then(err => Promise.reject(err));
      return response.json();
    })
    .then(data => {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: data.message || 'Password changed successfully',
        confirmButtonColor: '#3085d6'
      });
    })
    .catch(error => {
      if (error.errors) {
        if (error.errors.password) {
          document.getElementById('passwordError').innerText = error.errors.password[0];
        }
        if (error.errors.password_confirmation) {
          document.getElementById('confirmPasswordError').innerText = error.errors.password_confirmation[0];
        }

        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: 'Please correct the highlighted fields.',
          confirmButtonColor: '#d33'
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Something went wrong',
          text: 'An unexpected error occurred.',
          confirmButtonColor: '#d33'
        });
        console.error('Unexpected error:', error);
      }
    });
  });
});
</script>

  </body>
</html>
