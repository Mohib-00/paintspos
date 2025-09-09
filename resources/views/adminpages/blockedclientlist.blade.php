
<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addcustomer {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addcustomer:hover {
        background-color: #45a049;  
    }


    .custom-modal.customer, 
.custom-modal.customeredit {
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
                                <i class="fas fa-file-pdf"></i> Excel
                            </button>
                        </div>

                    </div>
                    <h1 class="mx-3 list">Blocked Customer List</h1>
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
                                <th>customer_name</th>
                                {{--<th>Area</th>--}}
                                <th>client_type</th>
                                <th>Assign User</th>
                                <th>phone_1</th>
                                <th>phone_2</th>
                                {{--<th>client_gender</th>
                                <th>client_cnic</th>
                                <th>client_father_name</th>
                                <th>client_residence</th>
                                <th>client_occupation</th>
                                <th>client_salary</th>--}}
                                <th>client_fixed_discount</th>
                                <th>distributor_fix_margin</th>
                                {{--<th>client_permanent_address</th>
                                <th>client_residential_address</th>
                                <th>client_office_address</th>--}}
                                
                                <th>Created At</th>
                                <th style="width: 10%">Action</th>
                                <th>UnBlock</th>
                              </tr>
                            </thead>
                           
                            <tbody>
                                @php $counter = 1; @endphp
                                @foreach($customers as $customer)
                                        <tr class="user-row" id="customer-{{ $customer->id }}">
                                            <td>{{$counter}}</td>
                                            <td>{{$customer->id}}</td>
                                            <td>{{$customer->customer_name}}</td>
                                            <td>{{$customer->client_type}}</td>
                                            <td>{{$customer->assigned_user_id}}</td>
                                            {{--<td>{{$customer->area_id}}</td>--}}
                                            <td>{{$customer->phone_1}}</td>  
                                            <td>{{$customer->phone_2}}</td>
                                            {{--<td>{{$customer->client_gender}}</td>
                                            <td>{{$customer->client_cnic}}</td>
                                            <td>{{$customer->client_father_name}}</td>
                                            <td>{{$customer->client_residence}}</td>
                                            <td>{{$customer->client_occupation}}</td>
                                            <td>{{$customer->client_salary}}</td>--}}
                                            <td>{{$customer->client_fixed_discount}}</td>
                                            <td>{{$customer->distributor_fix_margin}}</td>
                                            {{--<td>{{$customer->client_permanent_address}}</td>
                                            <td>{{$customer->client_residential_address}}</td>
                                            <td>{{$customer->client_office_address}}</td>--}}
                                            
                                            <td>{{$customer->created_at}}</td>
                                                <td>
                                                    <div class="form-button-action">
                                                    <a data-customer-id="{{ $customer->id }}" class="btn btn-link btn-primary btn-lg edit-customer-btn">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                
                                                    <a data-customer-id="{{ $customer->id }}" class="btn btn-link btn-danger delcustomer mt-2">
                                                        <i class="fa fa-times"></i>                    
                                                    </a>
                                                </div>
                                                </td>
                                                <td>
                                                    <a data-customer-id="{{ $customer->id }}" class="btn btn-link unblockcustomer mt-2" style="color:black">
                                                        <i class="fas fa-ban"></i>                    
                                                    </a>
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

     <!-- Add customer edit Modal -->
     <div style="display:none" class="custom-modal customeredit" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="font-weight: bolder" class="modal-title">Edit customer</h2>
                    <button type="button" class="close closeModal" style="background: transparent; border: none; font-size: 2.5rem; color: #333;">
                        &times;
                    </button>
                </div>
    
                <form id="customereditform">
                    <input type="hidden" id="customerforminput_edit" value=""/>
                    <div class="row mt-5">
                        
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="email2">Name</label>
                              <input class="form-control" type="text" id="name" name="customer_name" placeholder="Name">
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="defaultSelect">Area</label>
                            <select
                              class="form-select form-control"
                              id="defaultSelect" name="area_id"
                            >
                              <option>Choose Area</option>
                              @foreach($areas as $area)
                              <option>{{$area->area_name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="defaultSelect">Assign User</label>
                                <select
                                  class="form-select form-control"
                                  id="defaultSelectt" name="assigned_user_id"
                                >
                                  <option>Select User</option>
                                  @foreach($users as $user)
                                  <option>{{$user->name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>


                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                  <label for="client_type">Client Type</label>
                                  <input class="form-control" type="text" id="client_type" name="client_type" placeholder="Client Type">
                                  <span id="nameError" class="text-danger"></span>
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
                                      id="defaultSelecttt" name="client_gender"
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
                                          id="defaultSelectttttt" name="client_residence"
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
                                          <label for="client_occupation">Client Occupation</label>
                                          <input class="form-control" type="text" id="client_occupation" name="client_occupation" placeholder="Client Occupation*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_salary">Client Salary</label>
                                          <input class="form-control" type="number" id="client_salary" name="client_salary" placeholder="Client Salary*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_salary">Client Fixed discount %</label>
                                          <input class="form-control" type="number" id="client_fixed_discount" name="client_fixed_discount" placeholder="Client Fixed Discount*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="distributor_fix_margin">Distributor Fix Margin</label>
                                          <input class="form-control" type="number" id="distributor_fix_margin" name="distributor_fix_margin" placeholder="Fix Margin*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_permanent_address">Client Permanent Address</label>
                                          <input class="form-control" type="text" id="client_permanent_address" name="client_permanent_address" placeholder="Permanent Address*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_residential_address">Client Residential Address</label>
                                          <input class="form-control" type="text" id="client_residential_address" name="client_residential_address" placeholder="Residential Address*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                          <label for="client_office_address">Client Office Address</label>
                                          <input class="form-control" type="text" id="client_office_address" name="client_office_address" placeholder="Office Address*">
                                          <span id="nameError" class="text-danger"></span>
                                        </div>
                                    </div>

                       
                       
                    </div>
                    <div class="modal-footer mt-5" style="justify-content: flex-end; display: flex;">
                        <button id="customereditForm" type="submit" class="btn btn-primary" style="margin-right: 10px;">Submit</button>
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
        $('.addcustomer').click(function() {
            $('.custom-modal.customer').fadeIn();  
       });
   
        $('.closeModal').click(function() {
           $('.custom-modal.customer').fadeOut(); 
       });
   
        $(document).click(function(event) {
           if (!$(event.target).closest('.modal-content').length && !$(event.target).is('.addcustomer')) {
               $('.custom-modal.customer').fadeOut(); 
           }
       });
   });
   
   //to del customer
   $(document).on('click', '.delcustomer', function() {
       const customerId = $(this).data('customer-id');
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
                   url: '/delete-customer',
                   type: 'POST',
                   data: { customer_id: customerId },  
                   dataType: 'json',
                   success: function(response) {
                    removeLoader();
                       if (response.success) {
                        removeLoader();
                           $('.addcustomer').show();
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
   
   

   
   // get customer data
   $(document).on('click', '.edit-customer-btn', function () {
       var customerId = $(this).data('customer-id');
       createLoader();
       $.ajax({
           url: "{{ route('customer.show', '') }}/" + customerId, 
           type: "GET",  
           success: function (response) {
            removeLoader();
               if (response.success) {
                removeLoader();
                   $('#customereditform #customerforminput_edit').val(response.customer.id);
                   if (response.customer.image) {
                       $('#customereditform #icon_edit').attr('src', "{{ asset('images') }}/" + response.customer.image);
                   }
                   $('#customereditform #name').val(response.customer.customer_name);
                   $('#customereditform #defaultSelect').val(response.customer.area_id);
                   $('#customereditform #client_type').val(response.customer.client_type);
                   $('#customereditform #defaultSelectt').val(response.customer.assigned_user_id);
                   $('#customereditform #phone_1').val(response.customer.phone_1);
                   $('#customereditform #phone_2').val(response.customer.phone_2);
                   $('#customereditform #defaultSelecttt').val(response.customer.client_gender);
                   $('#customereditform #client_cnic').val(response.customer.client_cnic);
                   $('#customereditform #client_father_name').val(response.customer.client_father_name);
                   $('#customereditform #defaultSelectttttt').val(response.customer.client_residence);
                   $('#customereditform #client_occupation').val(response.customer.client_occupation);
                   $('#customereditform #client_salary').val(response.customer.client_salary);
                   $('#customereditform #client_fixed_discount').val(response.customer.client_fixed_discount);
                   $('#customereditform #distributor_fix_margin').val(response.customer.distributor_fix_margin);
                   $('#customereditform #client_permanent_address').val(response.customer.client_permanent_address);
                   $('#customereditform #client_residential_address').val(response.customer.client_residential_address);
                   $('#customereditform #client_office_address').val(response.customer.client_office_address);
                   $('.custom-modal.customeredit').fadeIn();
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
   
   
   // Edit customer 
   $('#customereditform').on('submit', function (e) {
       e.preventDefault();
   
       var formData = new FormData(this); 
       var customerId = $('#customerforminput_edit').val(); 
       createLoader();
     
       $.ajax({
           url: "{{ route('customer.update', '') }}/" + customerId,  
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
                       $('#customereditform')[0].reset();
                       $('.custom-modal.customeredit').fadeOut();
   
                       const customer = $(`a[data-customer-id="${customerId}"]`).closest('tr');
                       customer.find('td:nth-child(2)').text(response.customer.id);
                       customer.find('td:nth-child(3)').text(response.customer.customer_name);
                       //customer.find('td:nth-child(4)').text(response.customer.area_id);
                       customer.find('td:nth-child(5)').text(response.customer.client_type);
                       customer.find('td:nth-child(6)').text(response.customer.assigned_user_id); 
                       customer.find('td:nth-child(7)').text(response.customer.phone_1); 
                       customer.find('td:nth-child(8)').text(response.customer.phone_2); 
                       /*customer.find('td:nth-child(9)').text(response.customer.client_gender); 
                       customer.find('td:nth-child(10)').text(response.customer.client_cnic); 
                       customer.find('td:nth-child(11)').text(response.customer.client_father_name); 
                       customer.find('td:nth-child(12)').text(response.customer.client_residence); 
                       customer.find('td:nth-child(13)').text(response.customer.client_occupation); 
                       customer.find('td:nth-child(14)').text(response.customer.client_salary); */
                       customer.find('td:nth-child(15)').text(response.customer.client_fixed_discount); 
                       customer.find('td:nth-child(16)').text(response.customer.distributor_fix_margin); 
                       /*customer.find('td:nth-child(17)').text(response.customer.client_permanent_address); 
                       customer.find('td:nth-child(17)').text(response.customer.client_residential_address);
                       customer.find('td:nth-child(18)').text(response.customer.client_office_address);*/
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
       $('.custom-modal.customeredit').fadeOut();
   });

   $(document).on('click', '.unblockcustomer', function (e) {
    e.preventDefault();
    var $this = $(this);
    var customerId = $this.data('customer-id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to unblock this customer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, unblock it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/customer/unblock/' + customerId,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Unblocked!', response.message, 'success');
                        // Remove row from DOM
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
  </body>
</html>

