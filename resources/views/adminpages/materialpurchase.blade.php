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
                  <form id="rawmaterialproductssssform">
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
                            <label for="defaultSelect">Vendors</label>
                            <select class="form-select form-control" id="vendors" name="vendors">
                              <option>Choose Vendor</option>
                              @foreach($vendors as $vendor)
                                <option>{{$vendor->name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="invoice_no">Invoice No</label>
                            <input class="form-control" type="text" id="invoice_no" name="invoice_no" value="Invoice-{{ rand(1000, 9999) }}">
                            <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>

                         @php
                           $today = \Carbon\Carbon::today()->toDateString();
                           $yesterday = \Carbon\Carbon::yesterday()->toDateString();
                           @endphp

                          @if(auth()->user()->pur_pastdate == '1')
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Invoice Date</label>
                              <input type="date" id="from_date" name="created_at" class="form-control" >
                              <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>
                        @elseif(auth()->user()->pur_pastdate == '0')
                         <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Invoice Date</label>
                              <input type="date" id="from_date" name="created_at" class="form-control" min="{{ $yesterday }}" max="{{ $today }}">
                              <span id="nameError" class="text-danger"></span>
                          </div>
                        </div>
                        @endif
                      

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
                                <th style="background-color: #1a2035; color: white;">Select Material</th>
                                <th style="background-color: #1a2035; color: white;">Qty</th>
                           
                                <th style="background-color: #1a2035; color: white;">Purchase Rate</th>
                                <th style="background-color: #1a2035; color: white;">SubTotal</th>
                              
                                <th style="background-color: #1a2035; color: white; text-align: center;">
                                  <button type="button" class="btn btn-sm btn-light rowmaker" onclick="addRow()">+</button>
                                </th>
                              </tr>
                            </thead>
                            <tbody id="tableBody">
                              <tr>
                        <td>
  <div class="dropdown w-100 custom-product-dropdown position-relative">
    <button 
      class="btn form-control custom-dropdown-toggle selectedProductName text-start position-relative"
      type="button"
      aria-expanded="false"
      style="border: 2px solid #eff0f3; padding-left: 0.5rem; padding-right: 2rem; background-color: #fff;"
    >
      Select Product
      <span class="dropdown-arrow position-absolute" style="right: 0.5rem; top: 50%; transform: translateY(-50%); pointer-events: none;">
        ▼
      </span>
    </button>

    <ul class="dropdown-menu custom-dropdown-menu w-100" style="display: none; max-height: 300px; overflow-y: auto;">
      <li class="px-2 pt-2">
        <input
          type="text"
          class="form-control product-search-input"
          placeholder="Search..."
          autocomplete="off"
        />
      </li>
      <li><hr class="dropdown-divider" /></li>

      @foreach($products as $product)
        <li class="product-item">
          <a 
            class="dropdown-item" 
            href="#" 
            onclick="selectProduct(this, '{{ $product->id }}'); return false;"
          >
            {{ $product->item_name }}
          </a>
        </li>
        <li><hr class="dropdown-divider" /></li>
      @endforeach
    </ul>

    <input type="hidden" name="products[]" class="selectedProductId" />
  </div>
</td>



<td style="min-width: 120px; max-width: 120px;">
  <input type="number" min="1" name="quantity[]" class="form-control quantity" value="1">
</td>



  <td style="min-width: 120px; max-width: 120px;">
    <input type="number" name="single_purchase_rate[]" class="form-control single_purchase_rate" >
  </td>

  <td style="min-width: 120px; max-width: 120px;">
    <input type="number" name="purchase_rate[]" class="form-control purchase_rate" >
  </td>
 
                           
                              <td style="text-align:center">
                                  <button type="button" class="btn btn-danger btn-sm rowremover " onclick="removeRow(this)">X</button>
                              </td>
                              </tr>
                          </tbody>
                          
                          </table>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Total Quantity</label>
                            <input class="form-control" type="number" id="totalQuantity" name="totalquantity" readonly>
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
   document.addEventListener('click', function(event) {
  const toggleBtn = event.target.closest('.custom-dropdown-toggle');
  if (toggleBtn) {
    const dropdownContainer = toggleBtn.closest('.custom-product-dropdown');
    if (!dropdownContainer) return;

    const dropdownMenu = dropdownContainer.querySelector('.custom-dropdown-menu');
    const searchInput = dropdownMenu.querySelector('.product-search-input');
    if (!dropdownMenu || !searchInput) return;

    setTimeout(() => {
      const style = window.getComputedStyle(dropdownMenu);
      if (style.display !== 'none' || dropdownMenu.classList.contains('show')) {
        searchInput.focus();
      }
    }, 100);
  }
});

</script>



    <script>
      document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        document.querySelector('.rowmaker').click();
    }
});

    </script>


    <script>
      $(document).ready(function() {
       $('#submitdata').click(function(e) {
           e.preventDefault();
   
           var formData = new FormData($('#rawmaterialproductssssform')[0]);
   
           var invoiceDate = $('#from_date').val();
           if (invoiceDate) {
               invoiceDate += " 00:00:00"; 
           }
   
           formData.append('created_at', invoiceDate);
           formData.append('totalquantity', $('#totalQuantity').val());
           formData.append('gross_amount', $('#grossAmount').val());
           formData.append('discount', $('#discount').val());
           formData.append('net_amount', $('#netAmount').val());
   
           $.ajax({
               url: '{{ route("rawmaterialpurchase.store") }}',  
               type: 'POST',
               data: formData,
               processData: false,  
               contentType: false,  
               success: function(response) {
                   Swal.fire({
                       title: 'Success!',
                       text: 'Purchase saved successfully!',
                       icon: 'success',
                       confirmButtonText: 'OK'
                      }).then(() => {
                          //loadpurchasePage(); 
                      });
                   $('#rawmaterialproductssssform')[0].reset();  
               },
               error: function(xhr, status, error) {
                   Swal.fire({
                       title: 'Error!',
                       text: 'Something went wrong: ' + error,
                       icon: 'error',
                       confirmButtonText: 'Try Again'
                   });
               }
           });
       });
   });
   </script>
   
<script>
  document.addEventListener("click", function (e) {
    const toggle = e.target.closest(".custom-dropdown-toggle");
    const allDropdowns = document.querySelectorAll(".custom-dropdown-menu");

    if (toggle) {
      const dropdown = toggle.closest(".custom-product-dropdown");
      const menu = dropdown.querySelector(".custom-dropdown-menu");

      allDropdowns.forEach((d) => {
        if (d !== menu) d.style.display = "none";
      });

      menu.style.display = menu.style.display === "block" ? "none" : "block";
    } else {
      if (!e.target.closest(".custom-dropdown-menu")) {
        allDropdowns.forEach((d) => d.style.display = "none");
      }
    }
  });

  document.addEventListener("input", function (e) {
    if (e.target.classList.contains("product-search-input")) {
      const input = e.target;
      const filter = input.value.toLowerCase();
      const dropdownMenu = input.closest(".custom-dropdown-menu");
      const items = dropdownMenu.querySelectorAll(".product-item");

      items.forEach((item) => {
        const text = item.textContent || item.innerText;
        item.style.display = text.toLowerCase().includes(filter) ? "" : "none";
      });

      const allListItems = dropdownMenu.querySelectorAll("li");
      allListItems.forEach((li, index) => {
        const hr = li.querySelector("hr.dropdown-divider");
        if (hr) {
          const prev = allListItems[index - 1];
          const next = allListItems[index + 1];
          const prevHidden = !prev || (prev.classList.contains("product-item") && prev.style.display === "none");
          const nextHidden = !next || (next.classList.contains("product-item") && next.style.display === "none");
          li.style.display = (prevHidden && nextHidden) ? "none" : "";
        }
      });
    }
  });

 function selectProduct(element, productId) {
  const dropdown = element.closest(".custom-product-dropdown");
  const menu = dropdown.querySelector(".custom-dropdown-menu");

  $.ajax({
    url: '/api/rawmaterialproducts/' + productId,
    type: 'GET',
    success: function (data) {
      dropdown.querySelector('.selectedProductName').textContent = data.item_name;
      dropdown.querySelector('.selectedProductId').value = productId;

      const row = element.closest('tr');
      const quantityInput = row.querySelector('.quantity');
      const singleRateInput = row.querySelector('.single_purchase_rate');
      const totalRateInput = row.querySelector('.purchase_rate');

      const quantity = parseFloat(quantityInput.value) || 1;
      const purchaseRate = parseFloat(data.purchase_rate) || 0;

      singleRateInput.value = purchaseRate;
      totalRateInput.value = (purchaseRate * quantity).toFixed(2);

      menu.style.display = 'none';
      recalculateSummary();
    },
    error: function () {
      alert('Failed to fetch product data');
    }
  });
}

$(document).on('input', '.quantity', function () {
  const row = this.closest('tr');
  const quantity = parseFloat(this.value) || 1;
  const singleRate = parseFloat(row.querySelector('.single_purchase_rate').value) || 0;

  const total = quantity * singleRate;
  row.querySelector('.purchase_rate').value = total.toFixed(2);
});

function recalculateSummary() {
  let totalQuantity = 0;
  let grossAmount = 0;

  document.querySelectorAll('tr').forEach(row => {
    const qty = parseFloat(row.querySelector('.quantity')?.value) || 0;
    const rate = parseFloat(row.querySelector('.purchase_rate')?.value) || 0;

    totalQuantity += qty;
    grossAmount += rate;
  });

  document.getElementById('totalQuantity').value = totalQuantity;
  document.getElementById('grossAmount').value = grossAmount.toFixed(2);

  const discount = parseFloat(document.getElementById('discount').value) || 0;
  const netAmount = grossAmount - discount;

  document.getElementById('netAmount').value = netAmount.toFixed(2);
}

$(document).on('input', '.quantity, .single_purchase_rate, .purchase_rate', function () {
  const row = this.closest('tr');
  const quantity = parseFloat(row.querySelector('.quantity').value) || 1;
  const rate = parseFloat(row.querySelector('.single_purchase_rate').value) || 0;

  row.querySelector('.purchase_rate').value = (quantity * rate).toFixed(2);

  recalculateSummary();
});

$(document).on('input', '#discount', function () {
  recalculateSummary();
});


 
  function addRow() {
    const tableBody = document.getElementById('tableBody');
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
               <td>
  <div class="dropdown w-100 custom-product-dropdown position-relative">
    <button 
      class="btn form-control custom-dropdown-toggle selectedProductName text-start position-relative"
      type="button"
      aria-expanded="false"
      style="border: 2px solid #eff0f3; padding-left: 0.5rem; padding-right: 2rem; background-color: #fff;"
    >
      Select Product
      <span class="dropdown-arrow position-absolute" style="right: 0.5rem; top: 50%; transform: translateY(-50%); pointer-events: none;">
        ▼
      </span>
    </button>

    <ul class="dropdown-menu custom-dropdown-menu w-100" style="display: none; max-height: 300px; overflow-y: auto;">
      <li class="px-2 pt-2">
        <input
          type="text"
          class="form-control product-search-input"
          placeholder="Search..."
          autocomplete="off"
        />
      </li>
      <li><hr class="dropdown-divider" /></li>

      @foreach($products as $product)
        <li class="product-item">
          <a 
            class="dropdown-item" 
            href="#" 
            onclick="selectProduct(this, '{{ $product->id }}'); return false;"
          >
            {{ $product->item_name }}
          </a>
        </li>
        <li><hr class="dropdown-divider" /></li>
      @endforeach
    </ul>

    <input type="hidden" name="products[]" class="selectedProductId" />
  </div>
</td>
        <td style="min-width: 120px; max-width: 120px;">
  <input type="number" min="1" name="quantity[]" class="form-control quantity" value="1">
</td>



  <td style="min-width: 120px; max-width: 120px;">
    <input type="number" name="single_purchase_rate[]" class="form-control single_purchase_rate" >
  </td>

  <td style="min-width: 120px; max-width: 120px;">
    <input type="number" name="purchase_rate[]" class="form-control purchase_rate" >
  </td>
      <td style="text-align:center"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
    `;

    tableBody.appendChild(newRow);
  }

  
</script>

    
  </body>
</html>