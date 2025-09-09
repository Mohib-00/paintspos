<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Document</title>
   <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
   
   <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
//register
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#register').on('click', function () {
        Register();
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

        $.ajax({
            url: '/registerrr',
            type: 'POST',
            data: formData,
            success: function (response) {
                if (response.status) {
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
    //login
    $('#login').on('click', function (e) {
        e.preventDefault();
        Login();
    });

    
    $('#loginForm').on('keypress', function (e) {
        if (e.which === 13) {  
            e.preventDefault(); 
            Login();
        }
    });

    function Login() {
    $('.text-danger').text('');  

    var formData = {
        email: $('#loginEmail').val(),
        password: $('#loginPassword').val()
    };

    var valid = true;

    
    if (!formData.email) {
        $('#loginEmailError').text('The email field is required.');
        valid = false;
    }

    if (!formData.password) {
        $('#loginPasswordError').text('The password field is required.');
        valid = false;
    }

    if (!valid) {
        return;  
    }

    $.ajax({
        url: '/login',
        type: 'POST',
        data: formData,
        success: function (response) {
            if (response.status) {
                localStorage.setItem('token', response.token); 
                $('meta[name="csrf-token"]').attr('content', response.csrfToken);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': response.csrfToken
                    }
                });

              
              var url = (response.userType === '1' || response.userType === '2' || response.userType === '0') ? '/admin' : '/';

                window.location.href = url;
            } else {
                
                if (response.errors) {
                   
                    if (response.errors.email) {
                        $('#loginEmailError').text(response.errors.email[0]);   
                    }
                    if (response.errors.password) {
                        $('#loginPasswordError').text(response.errors.password[0]);   
                    }
                } else {
                    
                    $('#loginEmailError').text(response.message); 
                    $('#loginPasswordError').text(response.message); 
                }
            }
        },
        error: function (xhr) {
            
            if (xhr.status === 401) {
                var response = xhr.responseJSON;
                if (response.errors) {
                    
                    if (response.errors.email) {
                        $('#loginEmailError').text(response.errors.email[0]);
                    } 
                    if (response.errors.password) {
                        $('#loginPasswordError').text(response.errors.password[0]);
                    }
                } else {
                    $('#loginEmailError').text('Invalid credentials');
                    $('#loginPasswordError').text('Invalid credentials');
                }
            }
        }
    });
}

});
     
//logout
$(document).ready(function () {
       
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });

     
    $('.logout').on('click', function () {
   
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    $.ajax({
        url: '/logout',
        type: 'POST',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        
        success: function (response) {
            if (response.status) {
                localStorage.removeItem('token');
                window.location.href = '/';

                
                $.ajax({
                    url: '/',
                    type: 'GET',
                    success: function (content) {
                        $('body').html(content);
                    },
                    error: function () {
                        alert('Failed to load content.');
                    }
                });
            } else {
                alert('Logout failed. Please try again.');
            }
        },
        error: function (xhr) {
            console.error(xhr);
            alert('An error occurred while logging out.');
        }
    });
});

$(document).ready(function() {
  
  $('.logoutuser').click(function(e) {
      e.preventDefault();  

       
      $.ajax({
          url: '/logoutuser',   
          type: 'POST',
          headers: {
              'Authorization': 'Bearer ' + localStorage.getItem('token') 
          },
          success: function(response) {
              
              if (response.status) {
                  localStorage.removeItem('token');
                  window.location.href ='/';
              }
          },
          error: function(xhr, status, error) {
               alert('There was an error logging out.');
          }
      });
  });
});


        
if ((window.location.pathname === '/admin' || window.location.pathname === '/admin/add-graphic-details' || window.location.pathname === '/admin/add-marketing-details' || window.location.pathname === '/admin/add-pos-details' || window.location.pathname === '/admin/add-web-details' || window.location.pathname === '/admin/add-about-service'  || window.location.pathname === '/admin/admin-profile' || window.location.pathname === '/admin/add-feedback' || window.location.pathname === '/admin/add-blog' || window.location.pathname === '/admin/add-service' || window.location.pathname === '/admin/users' || window.location.pathname === '/admin/customer-messages' || window.location.pathname === '/admin/website-settings' || window.location.pathname === '/admin/add-slider-data' || window.location.pathname === '/admin/add-projects') && !localStorage.getItem('token')) {
        window.location.href = '/';  
    }
 


 

     //to open admin page
   $('.admin').click(function () {
    if (!localStorage.getItem('token')) {
        alert('You need to be logged in to access this page.');
        window.location.href = '/';   
        return;
    }

    var baseUrl = "{{ url('') }}";  
    $.ajax({
        url: baseUrl + '/admin',   
        type: 'GET',
        success: function (response) {
            window.location.href = '/admin';   
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error: ', status, error);
        }
    });
});

    
});



//to open login page
$(document).ready(function() {
    $('.signIn').on('click', function() {
        $.ajax({
            url: '/login',
            method: 'GET',
            success: function(response) {
                window.location.href = '/login';
            },
            error: function(xhr) {
                alert('Error: ' + xhr.statusText);
            }
        });
    });
});

//to open register page
$(document).ready(function() {
    $('.signUp').on('click', function() {
        $.ajax({
            url: '/register',
            method: 'GET',
            success: function(response) {
                window.location.href = '/register';
            },
            error: function(xhr) {
                alert('Error: ' + xhr.statusText);
            }
        });
    });
});

//to open frontend user page
$(document).ready(function() {
    $('.userpage').on('click', function() {
        $.ajax({
            url: '/',
            method: 'GET',
            success: function(response) {
                window.location.href = '/';
            },
            error: function(xhr) {
                alert('Error: ' + xhr.statusText);
            }
        });
    });
});
 
</script>
</body>