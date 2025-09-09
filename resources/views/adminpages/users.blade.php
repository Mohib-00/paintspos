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

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        width: 100%;
        max-width: 800px; 
        animation: slideDown 0.5s ease;
    }

    .modal-dialog {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin: 0;
        padding: 0;
    }

    @media (max-width: 767px) {
        .modal-dialog {
            max-width: 90%; 
        }

        .modal-content {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        .modal-content {
            padding: 10px;
        }
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
                    
                         @if(auth()->user()->user_add != '0')
                        <div class="d-flex align-items-center">
                            <a href="/admin/add_user" class="user" onclick="loadAddUserPage(); return false;">Add Users</a>
                        </div>
                        @endif
                    </div>
                    
                    <h1 class="mx-3 list">User List</h1>
                    
                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="add-row" 
                        class="display table table-striped table-hover"
                      >
                      
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Image</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Role</th>
                          <th>Created Date</th>
                          @if(Auth::check() && Auth::user()->userType == 1)
                              <th style="width: 10%">Action</th>
                          @endif
                      </tr>
                  </thead>
                  
                       
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($users as $user)
                            <tr class="user-row" data-user-id="{{ $user->id }}">
                              <td>{{ $counter }}</td>
                              <td>
                                @if(Auth::check() && Auth::user()->userType == 1)
                                    <img height="80" width="80" src="{{ Auth::user()->image ? asset('images/' . Auth::user()->image) : '' }}" />
                                @else
                                    <img height="80" width="80" src="{{ asset('images/dummy-image.jpg') }}" />
                                @endif
                            </td>
                            
                              <td>{{ $user->name }}</td>
                              <td>{{ $user->email }}</td>
                              <td>
                                {{ $user->role }}
                            </td>
                            
                              <td>{{$user->created_at}}</td>
                              @if(Auth::check() && Auth::user()->userType == 1)
                              <td>
                                <div class="form-button-action">
                                    @if($user->userType != 1)
                                    <a
                                        href="/admin/edit_user/{{ $user->id }}" onclick="loadeditUser(this); return false;"
                                        data-user-id="{{ $user->id }}"
                                        type="button"
                                        data-bs-toggle="tooltip"
                                        title="Edit"
                                        class="btn btn-link btn-primary btn-lg "
                                    >
                                        <i class="fa fa-edit"></i>
                                </a>
                                @endif
                                
                                @if($user->userType != 1)
                                    <button
                                        data-user-id="{{ $user->id }}"
                                        type="button"
                                        data-bs-toggle="tooltip"
                                        title=""
                                        class="btn btn-link btn-danger deluser"
                                        data-original-title="Remove"
                                    >
                                        <i class="fa fa-times"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        @endif
                              @php $counter++; @endphp
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

         <!-- Edit User Modal -->
<div class="custom-modal" id="editUserModal" aria-hidden="true" tabindex="-1" style="
display: none; 
position: fixed; 
z-index: 1050; 
left: 0; 
top: 0; 
width: 100%; 
height: 100%; 
background-color: rgba(0, 0, 0, 0.5); 
justify-content: center; 
align-items: center; 
animation: fadeIn 0.3s ease;">
<div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
    <div class="modal-content" style="
        background-color: white; 
        padding: 20px; 
        border-radius: 8px; 
        text-align: center; 
        width: 800px; 
        animation: slideDown 0.5s ease;">
        <div class="modal-header">
            <h5 class="modal-title">Edit User</h5>
             
        </div>
        <form>
        <div class="modal-body">
          <input type="hidden" id="edituserid" value=""/> 


          
        <div class="form-group">
            <label for="editName" style="text-align: left; display: block; margin-bottom: 0.5rem;">Name</label>
            <input type="text" class="form-control" id="editName">
        </div>
        <div class="form-group">
            <label for="editEmail" style="text-align: left; display: block; margin-bottom: 0.5rem;">Email</label>
            <input type="email" class="form-control" id="editEmail">
        </div>
         
        <div class="form-group">
            <label for="editUserType" style="text-align: left; display: block; margin-bottom: 0.5rem;">User Type</label>
            <input type="text" class="form-control" id="editUserType">
        </div>
        

        </div>
        
        <div class="modal-footer">
            <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button   type="button" id="submitEdit" class="btn btn-primary mx-2">Submit</button>
        </div>
        </form>
    </div>
</div>
</div>

    @include('adminpages.js')
    @include('adminpages.ajax')

    <script>

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

$(document).on('click', '.edit-user-btn', function () {
    const userId = $(this).data('user-id');
    $('#edituserid').val(userId);
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    createLoader();

    $.ajax({
        url: '/get-user-data',
        type: 'POST',
        data: { user_id: userId },
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function (response) {
            removeLoader();

            if (response.success) {
                $('#editUserId').val(response.user.id);
                $('#editName').val(response.user.name);
                $('#editEmail').val(response.user.email);
                $('#editPassword').val('');
                $('#editUserType').val(response.user.userType);

                const currentUserId = response.currentUser ? response.currentUser.id : null;
                const currentUserType = response.currentUser ? response.currentUser.userType : null;

                if (userId === currentUserId) {
                    $('#editName').parent().show();
                    $('#editEmail').parent().show();
                    $('#editPassword').parent().show();
                } else {
                    $('#editName').parent().hide();
                    $('#editEmail').parent().hide();
                    $('#editPassword').parent().hide();
                }
                $('#editUserModal').css('display', 'flex').hide().fadeIn(300);
            }
        },
        error: function (xhr) {
            removeLoader();

            console.error(xhr);
            alert('Failed to retrieve user data.');
        }
    });
});


$('#close').on('click', function() {
        $('#editUserModal').fadeOut(300);
});

//to edit user  
$(document).on('click', '#submitEdit', function() {
    const userId = $('#edituserid').val();  

    let name = $('#editName').val();
    let email = $('#editEmail').val();
    let password = $('#editPassword').val();
    let userType = $('#editUserType').val();

    createLoader();

    $.ajax({
        url: `/users/${userId}/edit`,  
        method: 'POST',
        data: {
            name: name,
            email: email,
            password: password,
            userType: userType,
            _token: '{{ csrf_token() }}' 
        },
        success: function(response) {
            removeLoader();
            const userRow = $(`tr:has(button[data-user-id="${userId}"])`);
            userRow.find('td:nth-child(3)').text(name);  
            userRow.find('td:nth-child(4)').text(email);
            userRow.find('td:nth-child(5)').text(userType); 

            $('#editUserModal').fadeOut(300);
            $('#close').click();  
            Swal.fire(
                'Success!',
                'User updated successfully.',
                'success'
            );
        },
        error: function(xhr) {
            removeLoader();
            alert('Error updating user: ' + xhr.responseJSON.message);
        }
    });
});

//to del user
$(document).on('click', '.deluser', function() {
    const userId = $(this).data('user-id');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const row = $(this).closest('tr');  

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this user?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#loader').fadeIn(300);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            $.ajax({
                url: '/delete-user',
                type: 'POST',
                data: { user_id: userId },
                dataType: 'json',
                success: function(response) {
                    $('#loader').fadeOut(300);
                    if (response.success) {
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
                    $('#loader').fadeOut(300);
                    console.error(xhr);
                    Swal.fire(
                        'Error',
                        'An error occurred while deleting the user.',
                        'error'
                    );
                }
            });
        }
    });
});
       </script>
       
  </body>
</html>
