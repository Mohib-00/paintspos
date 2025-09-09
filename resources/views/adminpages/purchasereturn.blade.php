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

      .custom-dropdown {
        position: relative;
        width: 100%;
      }

      .dropdown-selected {
        padding: 10px;
        border: 1px solid #ccc;
        cursor: pointer;
        background: #fff;
      }

      .dropdown-list {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        border: 1px solid #ccc;
        background: white;
        max-height: 200px;
        overflow-y: auto;
        display: none;
        z-index: 1000;
      }

      .dropdown-list.show {
        display: block;
      }

      .dropdown-search {
        width: 100%;
        box-sizing: border-box;
        padding: 5px 10px;
        border: none;
        border-bottom: 1px solid #ccc;
      }

      .dropdown-item {
        padding: 10px;
        cursor: pointer;
      }

      .dropdown-item:hover {
        background-color: #f0f0f0;
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
                    <a class="user" href="/admin/purchase_list" onclick="loadpurchasePage(); return false;">Back</a>
                  </div>
                  <form id="returnForm">
                    <div class="card-body">
                      <div class="row">

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="defaultSelect">Receiving Location*</label>
                            <select class="form-select form-control" id="receiving_location" name="receiving_location">
                              <option>Head Office</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="defaultSelect">Select PO</label>
                            <select class="form-select form-control" id="vendorsSelect">
                              <option value="">Choose One</option>
                              @foreach($purchases as $purchase)
                                <option value="{{ $purchase->id }}">
                                  {{ $purchase->id }} - {{ $purchase->vendors }} - {{ $purchase->invoice_no }} - {{ $purchase->created_at }}
                                </option>
                              @endforeach
                            </select>
                            
                          </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_no">Vendors</label>
                              <input class="form-control" type="text" id="vendors" name="vendors" readonly>
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="invoice_no">Invoice No</label>
                            <input class="form-control" type="text" id="invoice_no" name="invoice_no" value="Invoice-{{ rand(1000, 9999) }}">
                            <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Invoice Date</label>
                              <input type="date" id="from_date" name="created_at" class="form-control" value="{{ request('from_date') }}">
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>

                       <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Grn Status</label>
                              <input type="text" id="stockstatus" name="stock_status" class="form-control" readonly>
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>

                      <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Payment Status</label>
                              <input type="text" id="paymentstatus" name="payment_status" class="form-control" readonly>
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>
                      

                        <div class="col-md-12 col-lg-8">
                          <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <input class="form-control" type="text" id="remarks" name="remarks" placeholder="Remarks">
                            <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>

                        <div class="table-responsive mt-3">
                          <table class="table table-bordered" id="productTable">
                            <thead>
                              <tr>
                                <th style="background-color: #1a2035; color: white;">Product</th>
                                <th style="background-color: #1a2035; color: white;">Return Qty</th>
                                <th style="background-color: #1a2035; color: white;">Qty</th>
                                <th style="background-color: #1a2035; color: white;">UPR</th>
                                <th style="background-color: #1a2035; color: white;">URR</th>
                                <th style="background-color: #1a2035; color: white;">Purchase Rate</th>
                                <th style="background-color: #1a2035; color: white;">Retail Rate</th>
                              </tr>
                            </thead>
                            <tbody id="tableBody">
                            </tbody>
                          
                          </table>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Total Quantity</label>
                            <input class="form-control" type="number" id="totalQuantity"  name="total_quantity" readonly>
                            <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Gross Amount</label>
                            <input type="number" name="gross_amount" class="form-control" id="grossAmount" readonly>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Discount</label>
                            <input type="number" name="discount" class="form-control" id="discount">
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Net Amount</label>
                            <input type="number" name="net_amount" class="form-control" id="netAmount" readonly>
                          </div>
                        </div>

                           <div class="col-4">
                          <div class="form-group">
                              <label for="payment_method">Payment Method</label>
                              <select
                              class="form-select form-control"
                              id="payment_method" name="payment_method"
                            >
                           
                            <option value="cash">Cash Payment</option>
                            <option value="bank">Bank Payment</option>
                             
                             
                            </select>    
                          </div>
                      </div>


                      <div class="col-md-6 col-lg-4 noneeeeeee" style="display:none">
                        <div class="form-group">
                          <label for="remarks">Bank Name</label>
                          <input type="text" name="bank_name" class="form-control" id="bank_name">
                        </div>
                      </div>

                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="amount_payed">Amount Payed</label>
                          <input type="number" name="amount_payed_return" class="form-control" id="amountpayedreturn">
                        </div>
                      </div>

                      </div>
                    </div>
                    <div class="card-action">
                      <button type="submit" class="btn btn-success">Submit</button>
                    </div>


                    <input type="hidden" name="purchase_id" id="purchase_id">


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
document.getElementById('amountpayedreturn').addEventListener('input', function () {
    const amountPayedInput = this;
    const amountPayed = parseFloat(amountPayedInput.value) || 0;
    const netAmount = parseFloat(document.getElementById('netAmount').value) || 0;

    if (amountPayed > netAmount) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Amount',
            text: 'Amount Payed cannot be greater than Net Amount!',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });

        amountPayedInput.value = netAmount;
    }
});
</script>

     <script>
      $("#payment_method").change(function () {
        if ($(this).val() === "bank") {
          $(".noneeeeeee").show();
        } else {
          $(".noneeeeeee").hide();
          $("#bank_name").val('');
        }
      });
    </script>


<script>
document.getElementById('returnForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  const purchaseId = document.getElementById('vendorsSelect').value;

  fetch(`/save-return-quantities/${purchaseId}`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Return quantities saved successfully!');
      } else {
        alert(data.error || 'Failed to save return quantities.');
      }
    })
    .catch(err => {
      console.error('Error saving return quantities:', err);
    });
});
</script>



    <script>
      document.getElementById('vendorsSelect').addEventListener('change', function () {
        const purchaseId = this.value;
      
        if (!purchaseId) return;
      
        fetch(`/get-purchasereturn-details/${purchaseId}`)
          .then(response => response.json())
          .then(data => {
            if (data.error) {
              alert(data.error);
              return;
            }
      
            document.getElementById('receiving_location').value = data.receiving_location || '';
            document.getElementById('vendors').value = data.vendors || '';
            document.getElementById('invoice_no').value = data.invoice_no || '';
            document.getElementById('from_date').value = data.created_at ? new Date(data.created_at).toISOString().split('T')[0] : '';
            document.getElementById('remarks').value = data.remarks || '';
      
            document.getElementById('totalQuantity').value = data.totalquantity || '';
      
            document.getElementById('discount').value = data.discount || '';
            document.getElementById('stockstatus').value = data.stock_status || '';
            document.getElementById('paymentstatus').value = data.payment_status || '';
            document.getElementById('amountpayedreturn').value = data.amount_payed_return || '';

            const products = JSON.parse(data.products || '[]');
            const productNames = data.product_names || {}; 
            const quantities = JSON.parse(data.quantity || '[]');
            const purchaserate = JSON.parse(data.purchase_rate || '[]');
            const retailRates = JSON.parse(data.retail_rate || '[]');
            const singlepurchaserate = JSON.parse(data.single_purchase_rate || '[]');
            const singleretailRates = JSON.parse(data.single_retail_rate || '[]');
            const returnQuantity = JSON.parse(data.return_quantity || '[]');
            const amounts = JSON.parse(data.amount || '[]');
      
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; 
      
            for (let i = 0; i < products.length; i++) {
              const productId = products[i];
              const productName = productNames[productId] || productId;
      
              const row = `
<tr data-product-id="${productId}">
  <td style="min-width: 300px; max-width: 300px;">
    <input type="hidden" name="product_ids[]" value="${productId}">
    <input type="text" name="products[]" class="form-control" value="${productName}" disabled>
  </td>
   <td style="min-width: 120px; max-width: 120px;">
    <input type="number" name="return_quantity[]" class="form-control returnqty" value="${returnQuantity[i] || ''}"  data-original="${returnQuantity[i] || '0'}">
  </td>
  
  <td style="min-width: 120px; max-width: 120px;">
    <input type="number" name="quantity[]" class="form-control qty" oninput="updateSingleRates(this); calculateTotals();" value="${quantities[i] || ''}" readonly>
  </td>
  <td style="min-width: 120px; max-width: 120px;">
  <input readonly type="number" name="single_purchase_rate[]" class="form-control upr" 
         value="${singlepurchaserate[i] || ''}" 
         oninput="updateFromSingleRate(this)">
</td>
<td style="min-width: 120px; max-width: 120px;">
  <input readonly type="number" name="single_retail_rate[]" class="form-control urr"  
         value="${singleretailRates[i] || ''}" 
         oninput="updateFromSingleRate(this)">
</td>
<td style="min-width: 120px; max-width: 120px;">
  <input type="number" name="purchase_rate[]" class="form-control purchase-rate" 
         readonly value="${purchaserate[i] || ''}">
</td>
<td style="min-width: 120px; max-width: 120px;">
  <input type="number" name="retail_rate[]" class="form-control retail-rate" 
         readonly value="${retailRates[i] || ''}">
</td>


</tr>`;

      
              tableBody.insertAdjacentHTML('beforeend', row);
            }
          })
          .catch(error => {
            console.error('Error fetching purchase details:', error);
          });
      });

   

      </script>
      
<script>
 document.addEventListener('input', function (e) {
  if (e.target.classList.contains('returnqty')) {
    const row = e.target.closest('tr');
    const returnQtyInput = e.target;
    const qtyInput = row.querySelector('.qty');

    const newReturnQty = parseFloat(returnQtyInput.value) || 0;
    const availableQty = parseFloat(qtyInput.value) || 0;
    const originalReturnQty = parseFloat(returnQtyInput.dataset.original) || 0;

    const qtyDifference = newReturnQty - originalReturnQty;

    if (qtyDifference > availableQty) {
      Swal.fire({
        icon: 'warning',
        title: 'Invalid Quantity',
        text: 'Return quantity cannot be greater than available quantity!',
      });

      returnQtyInput.value = originalReturnQty; 
      calculateGrossAndNetAmount();
      return;
    }

    calculateGrossAndNetAmount();
  }

  if (e.target.id === 'discount') {
    calculateGrossAndNetAmount();
  }
});


  function calculateGrossAndNetAmount() {
  let totalGross = 0;

  document.querySelectorAll('tr[data-product-id]').forEach(row => {
    const returnQtyInput = row.querySelector('.returnqty');
    const purchaseRateInput = row.querySelector('.upr');

    const qty = parseFloat(returnQtyInput.value) || 0;
    const rate = parseFloat(purchaseRateInput.value) || 0;

    totalGross += qty * rate;
  });

  document.getElementById('grossAmount').value = totalGross.toFixed(2);

  const discount = parseFloat(document.getElementById('discount').value) || 0;
  let netAmount = totalGross - discount;
  if (netAmount < 0) netAmount = 0;

  document.getElementById('netAmount').value = netAmount.toFixed(2);
}

</script>

  
  </body>
</html>