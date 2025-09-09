<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .user {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .user:hover {
        background-color: #45a049;  
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
                    <div class="card-header">
                        <a class="user" href="/admin/users" onclick="loadUsersPage(); return false;">Back</a>
                    </div>
                    <form id="registrationForm">      
                    <div class="card-body">
                      <div class="row">

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                              <label for="email2">Name</label>
                              <input class="form-control" type="text" id="name" name="name">
                              <span id="nameError" class="text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="email2">Email Address</label>
                            <input class="form-control" type="email" id="email" name="email">
                            <span id="emailError" class="text-danger"></span>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input class="form-control" type="password" id="password" name="password">
                            <span id="passwordError" class="text-danger"></span>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="password">Confirm Password</label>
                                <input class="form-control" type="password" id="confirmPassword" name="confirmPassword">
                                <span id="confirmPasswordError" class="text-danger"></span>
                              </div>
                            </div>
                      </div>
                    </div>
                    <div class="card-action">
                      <a id="registerrrrr" class="btn btn-success">Submit</a>
                    </div>
                    </form>
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#registerrrrr').on('click', function () {
        Register();
    });

     $(document).on('keydown', function (e) {
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                Register();
            }
        });

    function Register() {
        $('.text-danger').text('');

        var formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            confirmPassword: $('#confirmPassword').val()
        };

        var valid = true;

        if (!formData.name) {
            $('#nameError').text('The name field is required.');
            valid = false;
        }

        if (!formData.email) {
            $('#emailError').text('The email field is required.');
            valid = false;
        }

        if (!formData.password) {
            $('#passwordError').text('The password field is required.');
            valid = false;
        } else if (formData.password.length < 8) {
            $('#passwordError').text('Password must be at least 8 characters long.');
            valid = false;
        }

        if (!formData.confirmPassword) {
            $('#confirmPasswordError').text('The confirm password field is required.');
            valid = false;
        } else if (formData.password !== formData.confirmPassword) {
            $('#confirmPasswordError').text('Passwords do not match.');
            valid = false;
        }

        if (!valid) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please correct the highlighted fields.'
            });
            return;
        }
        createLoader();

        $.ajax({
            url: '/registerrr',
            type: 'POST',
            data: formData,
            success: function (response) {
                removeLoader();
                if (response.status) {
                    removeLoader();
                    $('#registrationForm')[0].reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        text: 'You have successfully registered!'
                    });
                } else {
                    if (response.errors) {
                        $.each(response.errors, function (key, error) {
                            $('#' + key + 'Error').text(error[0]);
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: 'Please fix the errors and try again.'
                        });
                    }
                }
            },
            error: function (xhr) {
                removeLoader()
                if (xhr.status === 401 || xhr.status === 422) {
                    var response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function (key, error) {
                            $('#' + key + 'Error').text(error[0]);
                        });
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: 'The email has already been taken.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Something went wrong. Please try again later.'
                    });
                }
            }
        });
    }
});


</script>
   
  </body>
</html>
