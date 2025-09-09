<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
    .card-header {
        display: flex;
        align-items: center;
    }

    .addproduct {
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;            
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-left: auto;
    }

    .addproduct:hover {
        background-color: #45a049;  
    }


.custom-modal.product, 
.custom-modal.productedit {
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

    .input-group .form-control {
    border-right: 0;
}

.input-group .btn {
    border-left: 0;
    padding: 6px 12px;
    font-size: 14px; 
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
                            <button class="btn btn-sm btn-outline-primary me-2 print-table" >
                                <i class="fas fa-print"></i> Print
                            </button>
                    
                            <button class="btn btn-sm btn-outline-danger export-pdf">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            
                        </div>
                    
                       
                    </div>
                    
                    <h1 class="mx-3 list">Product Price List</h1>

                      <div class="card-body">
                        <div class="table-responsive">
                            <div class="mb-3">
                                <input type="text" id="searchByName" class="form-control" placeholder="Search by Item Name...">
                            </div>
                            
                            <!-- Product Table -->
                            <table id="productTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Id</th>
                                        <th>Brand</th>
                                        <th>Item Name</th>
                                        <th>BarCode</th>
                                        <th>Code</th>
                                        <th>Shade</th>
                                        {{--<th>Opening Qty</th>--}}
                                        <th>Qty</th>
                                        <th>Unit Purchase</th>
                                        <th>Unit Retail</th>
                                        <th>Purchase</th>
                                        <th>Retail</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; @endphp
                                    @foreach($products as $product)
                                        <tr class="user-row" id="product-{{ $product->id }}">
                                            <td>{{ $counter }}</td>
                                            <td>{{ $product->id }}</td>
                                             <td>
                                                <input type="text" class="form-control inline-edit item-name-input" style="width: 190px;font-weight:bolder" 
                                                    data-id="{{ $product->id }}" 
                                                    data-column="brand_name" 
                                                    value="{{ $product->brand_name }}" 
                                                    data-original-value="{{ $product->brand_name }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control inline-edit item-name-input" style="width: 190px;font-weight:bolder" 
                                                    data-id="{{ $product->id }}" 
                                                    data-column="item_name" 
                                                    value="{{ $product->item_name }}" 
                                                    data-original-value="{{ $product->item_name }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control inline-edit" style="width: 120px;font-weight:bolder" 
                                                    data-id="{{ $product->id }}" 
                                                    data-column="barcode" 
                                                    value="{{ $product->barcode }}" 
                                                    data-original-value="{{ $product->barcode }}" readonly>
                                            </td>

                                             <td>
                                                <input type="text" class="form-control inline-edit" style="width: 120px;font-weight:bolder" 
                                                    data-id="{{ $product->id }}" 
                                                    data-column="barcode" 
                                                    value="{{ $product->code }}" 
                                                    data-original-value="{{ $product->code }}" readonly>
                                            </td>
                                             <td>
                                                <input type="text" class="form-control inline-edit" style="width: 120px;font-weight:bolder" 
                                                    data-id="{{ $product->id }}" 
                                                    data-column="shade" 
                                                    value="{{ $product->shade }}" 
                                                    data-original-value="{{ $product->shade }}" readonly>
                                            </td>
                                            {{--<td id="gram{{ $product->id }}">
                                                <div class="input-group" style="width: 100px;">
                                                    <input type="number" id="opening_qty_{{ $product->id }}" class="form-control" value="{{ $product->opening_quantity }}" style="border-right: 0;">
                                                    <button class="btn btn-sm btn-primary updateOpeningQty" data-product-id="{{ $product->id }}" style="border-left: 0;">
                                                        <i class="fa fa-check"></i> 
                                                    </button>
                                                </div>
                                            </td>--}}
    <td>
    <input type="text" class="form-control inline-edit quantity" style="width: 80px;font-weight:bolder" 
        data-id="{{ $product->id }}" 
        data-column="quantity" 
        value="{{ $product->quantity }}" readonly>
</td>

<td>
    <input type="text" class="form-control inline-edit" style="width: 80px;font-weight:bolder" 
        data-id="{{ $product->id }}" 
        data-column="single_purchase_rate" 
        value="{{ $product->single_purchase_rate }}" readonly>
</td>
<td>
    <input type="text" class="form-control inline-edit" style="width: 80px;font-weight:bolder" 
        data-id="{{ $product->id }}" 
        data-column="single_retail_rate" 
        value="{{ $product->single_retail_rate }}" readonly>
</td>
<td>
    <input type="text" class="form-control purchase-rate" style="width: 80px; background-color: #f0f0f0;font-weight:bolder" 
        data-id="{{ $product->id }}" 
        data-column="purchase_rate" 
        value="{{ $product->purchase_rate }}" 
        readonly>
</td>
<td>
    <input type="text" class="form-control" style="width: 80px; background-color: #f0f0f0;font-weight:bolder" 
        data-id="{{ $product->id }}" 
        data-column="retail_rate" 
        value="{{ $product->single_retail_rate *  $product->quantity}}" 
        readonly>
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
    document.getElementById("searchByName").addEventListener("keyup", function () {
        let query = this.value.toLowerCase();
        let rows = document.querySelectorAll("#productTable tbody tr");

        rows.forEach(function (row) {
            let text = "";
            
            row.querySelectorAll("input").forEach(function (input) {
                text += input.value.toLowerCase() + " ";
            });

            row.style.display = text.includes(query) ? "" : "none";
        });
    });
</script>



    <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.quantity').forEach(function(input) {
        input.addEventListener('input', function () {
            const productId = this.dataset.id;
            const quantity = parseFloat(this.value);
            const purchaseInput = document.querySelector(`.purchase-rate[data-id="${productId}"]`);
            const purchaseRate = parseFloat(purchaseInput.value);
            const resultInput = document.getElementById(`result-${productId}`);

            if (!isNaN(purchaseRate) && !isNaN(quantity) && quantity !== 0) {
                const result = purchaseRate / quantity;
                resultInput.value = result.toFixed(2);
            } else {
                resultInput.value = '';
            }
        });

        input.dispatchEvent(new Event('input'));
    });
});
</script>


    <script>
    $(document).ready(function() {
        $(document).on('click', '.updateOpeningQty', function() {
            var productId = $(this).data('product-id');
            var newOpeningQty = $('#opening_qty_' + productId).val();
    
            if (newOpeningQty === "") {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please enter a valid opening quantity.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
    
            $.ajax({
                url: '{{ route("products.updateOpeningQuantity") }}', 
                method: 'POST',
                data: {
                    product_id: productId,
                    opening_qty: newOpeningQty,
                    _token: '{{ csrf_token() }}' 
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Opening quantity updated successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
    
                        $('#gram' + productId).html(`
                            <div class="input-group" style="width: 100px;">
                                <input type="number" id="opening_qty_${productId}" class="form-control" value="${newOpeningQty}" style="border-right: 0;">
                                <button class="btn btn-sm btn-primary updateOpeningQty" data-product-id="${productId}" style="border-left: 0;">
                                    <i class="fa fa-check"></i> 
                                </button>
                            </div>
                        `);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error updating opening quantity',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
    </script>

<script>
    $(document).ready(function () {
        $('.inline-edit').on('blur', function () {
            var input = $(this);
            var id = input.data('id');
            var column = input.data('column');
            var value = input.val();

            if (input.attr('data-original-value') !== undefined && input.attr('data-original-value') == value) {
                return;
            }

            $.ajax({
                url: "{{ route('product.updateInline') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    column: column,
                    value: value
                },
                success: function (response) {
                    if (response.status === 'success') {
                        input.attr('data-original-value', value);

                        // Update all fields
                        $('input[data-id="' + id + '"][data-column="single_purchase_rate"]').val(response.single_purchase_rate);
                        $('input[data-id="' + id + '"][data-column="single_retail_rate"]').val(response.single_retail_rate);
                        $('input[data-id="' + id + '"][data-column="quantity"]').val(response.quantity);
                        $('input[data-id="' + id + '"][data-column="purchase_rate"]').val(response.purchase_rate);
                        $('input[data-id="' + id + '"][data-column="retail_rate"]').val(response.retail_rate);
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    });
</script>




  </body>
</html>
