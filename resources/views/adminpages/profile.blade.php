<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    body{
    color: #1a202c;
    text-align: left;
    background-color: #e2e8f0;    
}
.main-body {
    padding: 15px;
}
.card {
    box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1rem;
}

.gutters-sm {
    margin-right: -8px;
    margin-left: -8px;
}

.gutters-sm>.col, .gutters-sm>[class*=col-] {
    padding-right: 8px;
    padding-left: 8px;
}
.mb-3, .my-3 {
    margin-bottom: 1rem!important;
}

.bg-gray-300 {
    background-color: #e2e8f0;
}
.h-100 {
    height: 100%!important;
}
.shadow-none {
    box-shadow: none!important;
}
    </style>
  </head>
  <body>
    <div class="wrapper">
    @include('adminpages.sidebar')

      <div class="main-panel">
        @include('adminpages.header')

        <div class="container">
            <div class="container">
                <div class="main-body">
                
                      <div class="row gutters-sm">
                        <div class="col-md-4 mb-3">
                          <div class="card">
                            <div class="card-body">
                              <div class="d-flex flex-column align-items-center text-center">
                                <img width=100 height=100 src="{{ Auth::user()->image ? asset('images/' . Auth::user()->image) : 'https://bootdey.com/img/Content/avatar/avatar7.png' }}" 
                                 alt="Admin" class="rounded-circle image" width="150">

                                 <div class="mt-3">
                                 <h4 class="name">{{ Auth::user()->name }}</h4>
                                 <p class="text-secondary mb-1 email">{{ Auth::user()->email }}</p>
                                 </div>

                              </div>
                            </div>
                          </div>
                        
                        </div>
                        <div class="col-md-8">
                          <div class="card mb-3">
                            <div class="card-body">
                                <div class="white_shd full margin_bottom_30 mt-4">
                                    <div class="full graph_head">
                                       <div class="heading1 margin_0">
                                          <h2>Change Profile</h2>
                                       </div>
                                    </div>
                                    <div class="full price_table padding_infor_info">
                                        <form>
                                        <div class="row">
                                           
                                            <!-- Input Field 1 -->
                                            <div class="col-sm-6">
                                                <label for="image" style="text-align: left; display: block; margin-bottom: 0.5rem;">Image</label>
                                                <div class="input-group">
                                                    <input type="file" class="form-control" id="image" name="image">
                                                </div>
                                            </div>
                                            
                                            <!-- Input Field 2 -->
                                            <div class="col-sm-6">
                                                <label for="name" style="text-align: left; display: block; margin-bottom: 0.5rem;">Name</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="name" name="name">
                                                   
                                                </div>
                                            </div>

                                             <!-- Input Field 3 -->
                                             <div class="col-sm-6">
                                                <label for="email" style="text-align: left; display: block; margin-bottom: 0.5rem;">Email</label>
                                                <div class="input-group">
                                                    <input type="email" class="form-control" id="email" name="email">
                                                   
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Submit Button -->
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button type="button" id="submitprofile" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                    
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
                                   <div class="heading1 margin_0">
                                      <h2>Change Password</h2>
                                   </div>
                                </div>
                                <div class="full price_table padding_infor_info">
                                    <div class="row">
                                        <!-- Input Field 1 -->
                                        <div class="col-sm-6">
                                            <label for="password" style="text-align: left; display: block; margin-bottom: 0.5rem;">Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password" name="password">
                                                <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                            <span class="text-danger" id="passwordError"></span>
                                        </div>
                                        
                                        <!-- Input Field 2 -->
                                        <div class="col-sm-6">
                                            <label for="confirm_password" style="text-align: left; display: block; margin-bottom: 0.5rem;">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="confirm_password" name="password_confirmation">
                                                <span class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;">
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                            <span class="text-danger" id="confirmPasswordError"></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button type="button" id="submitpassword" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                
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
    $(document).ready(function () {
        $('#submitprofile').on('click', function (e) {
            e.preventDefault();
    
            let formData = new FormData();
            
            let name = $('#name').val().trim();
            if (name !== '') formData.append('name', name);
    
            let email = $('#email').val().trim();
            if (email !== '') formData.append('email', email);
    
            let image = $('#image')[0].files[0];
            if (image) formData.append('image', image);
    
            formData.append('_token', '{{ csrf_token() }}');
    
            $.ajax({
                url: "{{ route('update.profile') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success) {
                        if (response.image) $('.image').attr('src', response.image);
                        if (response.name) $('.name').text(response.name);
                        if (response.email) $('.email').text(response.email);
    
                        Swal.fire({
                            icon: 'success',
                            title: 'Profile Updated!',
                            text: response.message,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function (xhr) {
                    let errorMessage = 'Error updating profile. Please check your inputs.';
    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMessage = errors.join('\n'); 
                    }
    
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed!',
                        text: errorMessage,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
    </script>
    
    
  </body>
</html>
