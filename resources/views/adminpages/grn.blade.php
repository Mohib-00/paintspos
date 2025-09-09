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
                  <form id="purchasegrnform">
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
                              <input class="form-control" type="text" id="vendors" name="vendors" disabled>
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
                                <th style="background-color: #1a2035; color: white;">Qty</th>
                                 <th style="background-color: #1a2035; color: white;">UPR</th>
                                <th style="background-color: #1a2035; color: white;">URR</th>
                                <th style="background-color: #1a2035; color: white;">Purchase Rate</th>
                                <th style="background-color: #1a2035; color: white;">Retail Rate</th>
                               
                                <th style="background-color: #1a2035; color: white;">Del</th>
                              </tr>
                            </thead>
                            <tbody id="tableBody">
                            </tbody>
                          
                          </table>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Total Quantity</label>
                            <input class="form-control" type="number" id="totalQuantity"  name="total_quantity" disabled>
                            <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Gross Amount</label>
                            <input type="number" name="gross_amount" class="form-control" id="grossAmount" disabled>
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
                            <input type="number" name="net_amount" class="form-control" id="netAmount" disabled>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div class="card-action">
                      <a id="submitdata" class="btn btn-success">Submit</a>
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
      document.getElementById('vendorsSelect').addEventListener('change', function () {
        const purchaseId = this.value;
      
        if (!purchaseId) return;
      
        fetch(`/get-purchase-details/${purchaseId}`)
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
      
            document.getElementById('grossAmount').value = data.gross_amount || '';
            document.getElementById('discount').value = data.discount || '';
            document.getElementById('netAmount').value = data.net_amount || '';
      
            const products = JSON.parse(data.products || '[]');
            const productNames = data.product_names || {}; 
            const quantities = JSON.parse(data.quantity || '[]');
            const purchaserate = JSON.parse(data.purchase_rate || '[]');
            const retailRates = JSON.parse(data.retail_rate || '[]');
            const singlepurchaserate = JSON.parse(data.single_purchase_rate || '[]');
            const singleretailRates = JSON.parse(data.single_retail_rate || '[]');
            const amounts = JSON.parse(data.amount || '[]');
      
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; 
      
            for (let i = 0; i < products.length; i++) {
              const productId = products[i];
              const productName = productNames[productId] || productId;
      
              const row = `
<tr data-product-id="${productId}">
  <td style="min-width: 300px; max-width: 300px;">
    <input type="text" class="form-control" value="${productName}" disabled>
  </td>
  <td style="min-width: 120px; max-width: 120px;">
    <input type="number" name="quantity[]" class="form-control qty" oninput="updateSingleRates(this); calculateTotals();" value="${quantities[i] || ''}">
  </td>
  <td style="min-width: 120px; max-width: 120px;">
  <input type="number" name="single_purchase_rate[]" class="form-control upr" 
         value="${singlepurchaserate[i] || ''}" 
         oninput="updateFromSingleRate(this)">
</td>
<td style="min-width: 120px; max-width: 120px;">
  <input type="number" name="single_retail_rate[]" class="form-control urr"  
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


  <td>
    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
  </td>
</tr>`;

      
              tableBody.insertAdjacentHTML('beforeend', row);
            }
          })
          .catch(error => {
            console.error('Error fetching purchase details:', error);
          });
      });

      document.getElementById("submitdata").addEventListener("click", function () {
    const form = document.getElementById("purchasegrnform");
    const formData = new FormData(form);

    const purchaseId = document.getElementById("vendorsSelect").value;
    formData.append("purchase_id", purchaseId);

    const netAmount = document.getElementById("netAmount").value || 0;
    formData.append("net_amount", netAmount);

    const grossAmount = document.getElementById("grossAmount").value || 0;
    formData.append("gross_amount", grossAmount);

    const discount = document.getElementById("discount").value;
    if (discount && parseFloat(discount) > 0) {
        formData.append("discount", discount);
    }

    const rows = document.querySelectorAll("#productTable tbody tr");

    rows.forEach((row) => {
        const productId = row.getAttribute("data-product-id");
        const quantity = row.querySelector(".qty")?.value || 0;
        const purchaseRate = row.querySelector(".purchase-rate")?.value || 0;
        const retailRate = row.querySelector(".retail-rate")?.value || 0;
        const upr = row.querySelector(".upr")?.value || 0;
        const urr = row.querySelector(".urr")?.value || 0;

        formData.append("products[]", productId);
        formData.append("quantity[]", quantity);
        formData.append("purchase_rate[]", purchaseRate);
        formData.append("retail_rate[]", retailRate);
        formData.append("single_purchase_rate[]", upr);
        formData.append("single_retail_rate[]", urr);
    });

    fetch("/update-purchase-stock", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            title: 'Success!',
            text: data.message,
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                loadpurchasePage(); 
            }
        });
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            title: 'Error!',
            text: 'Something went wrong. Please try again.',
            icon: 'error',
            confirmButtonText: 'Close'
        });
    });
});

      </script>
      
   

    <script>
function updateProductData(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];

    const cartonQty = selectedOption.getAttribute('data-carton-qty');
    const purchaseRate = selectedOption.getAttribute('data-purchase-rate');
    const retailRate = selectedOption.getAttribute('data-retail-rate');
    const singlePurchaseRate = selectedOption.getAttribute('data-single-purchase-rate');
    const singleRetailRate = selectedOption.getAttribute('data-single-retail-rate');

    const row = selectElement.closest('tr');
    row.querySelector('[name="purchase_rate[]"]').value = purchaseRate;
    row.querySelector('[name="retail_rate[]"]').value = retailRate;
    row.querySelector('[name="single_purchase_rate[]"]').value = singlePurchaseRate;
    row.querySelector('[name="single_retail_rate[]"]').value = singleRetailRate;
    row.querySelector('[name="quantity[]"]').value = cartonQty;

    calculateTotals();
}

function updateFromSingleRate(inputElement) {
    const row = inputElement.closest('tr');
    const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 1;

    const singlePurchase = parseFloat(row.querySelector('[name="single_purchase_rate[]"]').value) || 0;
    const singleRetail = parseFloat(row.querySelector('[name="single_retail_rate[]"]').value) || 0;

    row.querySelector('[name="purchase_rate[]"]').value = (singlePurchase * quantity).toFixed(2);
    row.querySelector('[name="retail_rate[]"]').value = (singleRetail * quantity).toFixed(2);

    calculateTotals();
}

function updateFromQuantity(inputElement) {
    const row = inputElement.closest('tr');

    updateFromSingleRate(row.querySelector('[name="single_purchase_rate[]"]'));
}

function calculateTotals() {
    let totalQuantity = 0;
    let grossAmount = 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;

    const rows = document.querySelectorAll('#tableBody tr');
    rows.forEach((row) => {
        const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
        const purchaseRate = parseFloat(row.querySelector('[name="purchase_rate[]"]').value) || 0;

        totalQuantity += quantity;
        grossAmount += purchaseRate;
    });

    const netAmount = grossAmount - discount;

    document.getElementById('totalQuantity').value = totalQuantity;
    document.getElementById('grossAmount').value = grossAmount.toFixed(2);
    document.getElementById('netAmount').value = netAmount.toFixed(2);
}

function addRow() {
    const tableBody = document.getElementById('tableBody');
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td style="min-width: 270px; max-width: 300px;"></td>
        <td style="min-width: 120px; max-width: 120px;">
            <input type="number" name="quantity[]" class="form-control" min="1"
                   oninput="updateFromQuantity(this)">
        </td>
        <td style="min-width: 120px; max-width: 120px;">
            <input type="number" name="single_purchase_rate[]" class="form-control upr"
                   oninput="updateFromSingleRate(this)">
        </td>
        <td style="min-width: 120px; max-width: 120px;">
            <input type="number" name="single_retail_rate[]" class="form-control urr"
                   oninput="updateFromSingleRate(this)">
        </td>
        <td style="min-width: 120px; max-width: 120px;">
            <input type="number" name="purchase_rate[]" class="form-control purchase-rate" readonly>
        </td>
        <td style="min-width: 120px; max-width: 120px;">
            <input type="number" name="retail_rate[]" class="form-control retail-rate" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
        </td>
    `;

    tableBody.appendChild(newRow);
}

function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
    calculateTotals();
}

function calculateNetAmount() {
    const grossAmount = parseFloat(document.getElementById('grossAmount').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    document.getElementById('netAmount').value = (grossAmount - discount).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('discount').addEventListener('input', calculateNetAmount);
    calculateTotals();
});
</script>

  
  
  
      
      
  </body>
</html>