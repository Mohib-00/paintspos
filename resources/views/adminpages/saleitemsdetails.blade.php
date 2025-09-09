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

    <div>
        <a onclick="loadsalereport(this)" class="btn btn-sm btn-outline-secondary back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

                      
                      <h1 class="mx-3 list">Sale Items</h1>
  
                        <div class="card-body">
                          <div class="table-responsive">
                           <table id="add-row" class="display table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Products</th>
            <th>Product Qty</th> 
            <th>Rate</th>
            <th>Subtotal</th>
            <th>Created Date</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $counter = 1; 
            $total = 0;
        @endphp

        @foreach ($sale->saleItems as $item)
            @php
                $subtotal = $item->product_subtotal ?? 0;
                $total += $subtotal;
            @endphp
            <tr class="user-row sale-row">
                <td>{{ $counter }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->product_quantity }}</td>
                <td>{{ $item->product_rate }}</td>
                <td>{{ number_format($subtotal, 2) }}</td>
                <td style="white-space: nowrap;">{{ $item->created_at->format('d-m-Y') }}</td>
            </tr>
            @php $counter++; @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="text-end"><strong>Total:</strong></td>
            <td><strong>{{ number_format($total, 2) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
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

    <!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="completeSaleModal" tabindex="-1" aria-labelledby="completeSaleLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="completeSaleForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Complete Sale</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="saleId" name="sale_id">
            <input type="hidden" id="customerName" name="customer_name">
            <input type="hidden" id="subtotal" name="subtotal">
  
            <label for="payment_type" class="form-label">Payment Type</label>
            <select class="form-select" name="payment_type" id="paymentType" required>
              <option value="">Select Type</option>
              <option value="1">Cash In Hand</option>
              <option value="2">Cash At Bank</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  
  


    @include('adminpages.js')
    @include('adminpages.ajax')


    <script>
        document.querySelectorAll('.completesale').forEach(button => {
          button.addEventListener('click', function () {
            document.getElementById('saleId').value = this.dataset.saleId;
            document.getElementById('customerName').value = this.dataset.customerName;
            document.getElementById('subtotal').value = this.dataset.subtotal;
          });
        });
      
        document.getElementById('completeSaleForm').addEventListener('submit', function (e) {
          e.preventDefault();
      
          const formData = new FormData(this);
      
          fetch("{{ route('complete.sale') }}", {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            alert(data.message);
            location.reload(); 
          })
          .catch(err => {
            console.error('Error:', err);
            alert('Something went wrong.');
          });
        });
      </script>
      

    <script>
        $(document).on('click', '.delsale', function(e) {
            e.preventDefault();
    
            var saleId = $(this).data('sale-id'); 
    
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will delete the sale and its associated records!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/saledelete/' + saleId, 
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}', 
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message, 
                                icon: 'success',
                                confirmButtonText: 'OK' 
                            });
    
                            $('[data-sale-id="'+ saleId +'"]').closest('tr').remove();                        
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK' 
                            });
                        }
                    });
                }
            });
        });
    </script>
    

    <script>
        function loadeditSalePage(element) {
    const saleId = element.getAttribute('data-sale-id');
    loadPage(`/admin/edit_sale_list/${saleId}`, `/admin/edit_sale_list/${saleId}`);
}

    </script>
  
  </body>
</html>
