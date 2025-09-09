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

                     <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="mx-3 list">Deal List</h1>
                            <a href="/admin/add_deal" onclick="loadadddealPage(); return false;" class="btn btn-sm btn-outline-primary me-2">
                              <i class="icon-plus"></i> Add deal
                            </a>
                     </div>

  
                        <div class="card-body">
                          <div class="table-responsive">
                              <table id="add-row" class="display table table-striped table-hover">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>Deal Name</th>
                                          <th>Deal Price</th> 
                                          <th>Deal Items Qty</th>
                                          <th>Deal Notes</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @php $counter = 1; @endphp
                                      @foreach($deals as $deal)
                                        <tr class="user-row" id="deal-{{ $deal->id }}">
                                                <td>{{$counter}}</td>
                                                <td>{{$deal->deal_name}}</td>
                                                <td>{{$deal->deal_price}}</td> 
                                                <td>{{$deal->deal_items_count}}</td>
                                                <td>{{$deal->remarks}}</td>
                                                
                                                <td>
                                                    <div class="form-button-action" style="display: flex; gap: 8px; align-items: center;">
                                                       
                                                        <a href="/admin/edit_deal_list" onclick="loadeditdealPage(this); return false;" data-deal-id="{{ $deal->id }}" class="btn btn-link btn-primary btn-lg edit-deal-btn icon-btn">
                                                           <i class="fa fa-edit"></i>
                                                        </a>
                                                     
                                                        <a href="javascript:void(0);" 
                                                           data-deal-id="{{ $deal->id }}" 
                                                           class="btn btn-link btn-danger deldeal icon-btn">
                                                            <i class="fa fa-times"></i>
                                                        </a>
                                                    </div>
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


    @include('adminpages.js')
    @include('adminpages.ajax')
    <script>
        $(document).on('click', '.deldeal', function(e) {
            e.preventDefault();
    
            var dealId = $(this).data('deal-id');
            var button = $(this);
    
            Swal.fire({
                title: 'Are you sure?',
                text: "This deal will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
    
                    $.ajax({
                        url: '/deals/' + dealId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
    
                            button.closest('tr').remove();
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Something went wrong while deleting.',
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
