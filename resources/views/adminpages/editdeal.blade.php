<!DOCTYPE html>
<html lang="en">
<head>
  @include('adminpages.css')
  <style>
    .user {
      padding: 8px 16px;
      background: #4CAF50;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      margin-left: auto;
    }
    .user:hover {
      background: #45a049;
    }
    .custom-dropdown { position: relative; width: 100%; }
    .dropdown-selected {
      padding: 10px; border: 1px solid #ccc;
      cursor: pointer; background: #fff;
    }
    .dropdown-list {
      position: absolute; top: 100%; left: 0; right: 0;
      border: 1px solid #ccc; background: #fff;
      max-height: 200px; overflow-y: auto; display: none;
      z-index: 1000;
    }
    .dropdown-list.show { display: block; }
    .dropdown-search {
      width: 100%; box-sizing: border-box;
      padding: 5px 10px; border: none; border-bottom: 1px solid #ccc;
    }
    .dropdown-item {
      padding: 10px; cursor: pointer;
    }
    .dropdown-item:hover { background: #f0f0f0; }
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
                  <a class="user" href="/admin/deal_list" onclick="loaddealPage(); return false;">Back</a>
                </div>
                <form id="editdealform">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                          <label for="invoice_no">Deal Name</label>
                          <input class="form-control" type="text" id="dealname" name="deal_name" value="{{$deals->deal_name}}">
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>
                      <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                          <label for="dealprice">Deal Price</label>
                          <input class="form-control" type="text" id="dealprice" name="deal_price" value="{{$deals->deal_price}}">
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="remarks">Remarks</label>
                          <input class="form-control" type="text" id="remarks" name="remarks" value="{{$deals->remarks}}">
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>
                      <div class="table-responsive mt-3">
                        <table class="table table-bordered" id="productTable">
                          <thead>
                            <tr>
                              <th style="background:#1a2035; color:#fff; min-width:228px;">Product</th>
                              <th style="background:#1a2035; color:#fff;">Qty</th>
                              <th style="background:#1a2035; color:#fff;display:none">Purchase Rate</th>
                              <th style="background:#1a2035; color:#fff;display:none">Retail Rate</th>
                              <th style="background:#1a2035; color:#fff;">
                                <button type="button" class="btn btn-sm btn-light rowmaker" onclick="addRow()">+</button>
                              </th>
                            </tr>
                          </thead>
                          <tbody id="tableBody">
                        @foreach($deals->dealItems as $item)
                        <tr>
                        <td style="min-width:470px; position:absolute;">
                        <div class="dropdown w-100 custom-product-dropdown" style="position: relative">
                           <button class="btn form-control custom-dropdown-toggle selectedProductName" type="button" aria-expanded="false" style="border:2px solid #eff0f3; text-align:left; background:#fff;">
                           {{ $item->products ?? 'Select Product' }} 
                           <span style="position:absolute; right:0.5rem; top:50%; transform:translateY(-50%); pointer-events:none;">▼</span>
                           </button>

                           <ul class="dropdown-menu custom-dropdown-menu" style="display:none; position:absolute; top:100%; left:0; width:100%; max-height:300px; overflow-y:auto; z-index:1050; background:#fff; border:1px solid #ccc; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                           <li class="px-2 pt-2">
                           <input type="text" class="form-control product-search-input" placeholder="Search..." autocomplete="off"/>
                           </li>
                           <li><hr class="dropdown-divider"/></li>

                        @foreach($products as $product)
                           <li class="product-item">
                           <a class="dropdown-item" href="#" onclick="selectProduct(this, '{{ $product->id }}'); return false;">
                           {{ $product->item_name }}
                           </a>
                           </li>
                           <li><hr class="dropdown-divider"/></li>
                        @endforeach
                           </ul>

                       <input type="hidden" name="products[]" class="selectedProductName" value="{{ $item->products }}">
                        </div>
                        </td>

                        <td>
                            <input type="number" min="1" name="quantity[]" class="form-control quantity" value="{{ $item->quantity }}">
                        </td>

                        <td style="display: none">
                            <input type="number" name="single_purchase_rate[]" class="form-control single_purchase_rate" value="{{ $item->single_purchase_rate }}" readonly>
                        </td>
                         <td style="display: none">
                            <input type="number" name="single_retail_rate[]" class="form-control single_retail_rate" value="{{ $item->single_retail_rate }}" readonly>
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
                        </td>
                        </tr>
                        @endforeach

                          </tbody>
                        </table>
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


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    function getDealIdFromURL() {
        const urlParts = window.location.pathname.split('/');
        return urlParts[urlParts.length - 1];
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $('#submitdata').click(function(e) {
        e.preventDefault();

        const dealId = getDealIdFromURL();
        const formData = new FormData($('#editdealform')[0]);

        Swal.fire({
            title: 'Updating...',
            text: 'Please wait!',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            type: "POST",
            url: "/edit_deals/" + dealId,
            data: formData,
            processData: false,
            contentType: false,
            method: 'POST',
            headers: {
                'X-HTTP-Method-Override': 'PUT' 
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                    }).then(() => {
                       loaddealPage();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message,
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                let errorMsg = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors).join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error!',
                    text: errorMsg,
                });
            }
        });
    });
});
</script>


  <script>
    document.addEventListener('keydown', e => {
      if (e.key === "Escape") document.querySelector('.rowmaker').click();
    });

    document.addEventListener("click", e => {
      const toggle = e.target.closest(".custom-dropdown-toggle");
      const allDropdowns = document.querySelectorAll(".custom-dropdown-menu");
      if (toggle) {
        const menu = toggle.closest(".custom-product-dropdown").querySelector(".custom-dropdown-menu");
        allDropdowns.forEach(d => d !== menu && (d.style.display = "none"));
        menu.style.display = menu.style.display === "block" ? "none" : "block";
      } else if (!e.target.closest(".custom-dropdown-menu")) {
        allDropdowns.forEach(d => d.style.display = "none");
      }
    });

    document.addEventListener("input", e => {
      if (e.target.classList.contains("product-search-input")) {
        const input = e.target;
        const filter = input.value.toLowerCase();
        const menu = input.closest(".custom-dropdown-menu");
        menu.querySelectorAll(".product-item").forEach(item => {
          item.style.display = item.textContent.toLowerCase().includes(filter) ? "" : "none";
        });
        menu.querySelectorAll("li").forEach((li, i, arr) => {
          const hr = li.querySelector("hr.dropdown-divider");
          if (hr) {
            const prev = arr[i - 1], next = arr[i + 1];
            li.style.display = (!prev || (prev.classList.contains("product-item") && prev.style.display === "none")) &&
                               (!next || (next.classList.contains("product-item") && next.style.display === "none")) ? "none" : "";
          }
        });
      }
    });

  function selectProduct(el, id) {
  const dropdown = el.closest(".custom-product-dropdown");
  const menu = dropdown.querySelector(".custom-dropdown-menu");
  $.ajax({
    url: `/api/productssssssssssssssssssssss/${id}`,
    type: 'GET',
    success(data) {
      dropdown.querySelector('.selectedProductName').textContent = data.item_name;

      dropdown.querySelector('input[name="products[]"]').value = data.item_name;

      const row = el.closest('tr');
      const qty = parseFloat(row.querySelector('.quantity').value) || 1;
      row.querySelector('.single_purchase_rate').value = data.single_purchase_rate;
      row.querySelector('.single_retail_rate').value = data.single_retail_rate;
      menu.style.display = 'none';
    },
    error() { alert('Failed to fetch product data'); }
  });
}


    function addRow() {
      const tableBody = document.getElementById('tableBody');
      const newRow = document.createElement('tr');
      newRow.innerHTML = `
        <td style="min-width:470px; position:absolute;">
          <div class="dropdown w-100 custom-product-dropdown" style="position:relative">
            <button class="btn form-control custom-dropdown-toggle selectedProductName" type="button" aria-expanded="false" style="border:2px solid #eff0f3; text-align:left; background:#fff;">
              Select Product
              <span style="position:absolute; right:0.5rem; top:50%; transform:translateY(-50%); pointer-events:none;">▼</span>
            </button>
            <ul class="dropdown-menu custom-dropdown-menu" style="display:none; position:absolute; top:100%; left:0; width:100%; max-height:300px; overflow-y:auto; z-index:1050; background:#fff; border:1px solid #ccc; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
              <li class="px-2 pt-2"><input type="text" class="form-control product-search-input" placeholder="Search..." autocomplete="off"/></li>
              <li><hr class="dropdown-divider"/></li>
              @foreach($products as $product)
                <li class="product-item"><a class="dropdown-item" href="#" onclick="selectProduct(this, '{{ $product->id }}'); return false;">{{ $product->item_name }}</a></li>
                <li><hr class="dropdown-divider"/></li>
              @endforeach
            </ul>
            <input type="hidden" name="products[]" class="selectedProductId"/>
          </div>
        </td>
        <td><input type="number" min="1" name="quantity[]" class="form-control quantity" value="1"></td>
        <td style="display: none"><input type="number" name="single_purchase_rate[]" class="form-control single_purchase_rate" readonly></td>
        <td style="display: none"><input type="number" name="single_retail_rate[]" class="form-control single_retail_rate" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
      `;
      tableBody.appendChild(newRow);
    }

    function removeRow(btn) {
      btn.closest('tr').remove();
    }
  </script>
</body>
</html>
