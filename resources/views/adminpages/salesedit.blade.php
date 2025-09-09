<!DOCTYPE html>
<html lang="en">
  <head>
   @include('adminpages.css')
   <style>
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

    input.only-up::-webkit-inner-spin-button {
    height: 50%;        
    margin-bottom: 50%;
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
             
              <form id="editsaleForm">
                 <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12 col-md-2 mb-3">
                                            <label for="customerSelect">Choose Employee</label>
                                            <select class="form-select form-select-sm" id="customerSelect" name="employee">
                                                <option value="1" {{ $sale->employee == 'All' ? 'selected' : '' }}>All</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->employee_name }}" {{ $sale->employee == $employee->employee_name ? 'selected' : '' }}>
                                                        {{ $employee->employee_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                
                                        <div class="col-12 col-md-6 mb-3">
                                            <label for="smallSelect">Choose a Customer</label>
                                            <select class="form-select form-select-sm" id="smallSelect" name="customer_name">
                                                <option value="1" {{ $sale->customer_name == 'All' ? 'selected' : '' }}>All</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ $sale->customer_name == $customer->customer_name ? 'selected' : '' }}>
                                                        {{ $customer->customer_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                
                                        <!-- Date -->
                                        <div class="col-12 col-md-2 mb-3">
                                            <label for="dateInput">Date</label>
                                            <input class="form-control form-control-sm" value="{{ $sale->created_at->format('Y-m-d') }}" type="date" name="created_at" id="dateInput" readonly/>
                                        </div>
                
                                        <!-- Ref# -->
                                        <div class="col-12 col-md-2 mb-3">
                                            <label for="refInput">Invoice#</label>
                                            <input class="form-control form-control-sm" value="{{$sale->ref}}" type="text" name="ref" id="refInput"/>
                                        </div>
                
                                       <div class="col-lg-8 col-md-12 mb-3">
                          <div class="dropdown">
                            <button class="btn" type="button" id="productSearchDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border: 2px solid #eff0f3;width: 100%;text-align: left;padding-left: 0.5rem;padding-right: 2rem;position: relative;">
                              Search Item
                            <span style="position: absolute;right: 0.5rem;top: 50%;transform: translateY(-50%);pointer-events: none;">
                              ▼
                            </span>
                            </button>

                            <ul class="dropdown-menu" id="productDropdownMenu" style="width: 100%; max-height: 300px; overflow-y: auto;">
                            <li class="px-2">
                            <input type="text" class="form-control" id="productSearchInput" placeholder="Search..." onkeyup="filterDropdownItems()" autocomplete="off"/>
                            </li>
                            <li><hr class="dropdown-divider" /></li>
      
                           @foreach ($products as $product)
    <li class="product-item">
        <a class="dropdown-item" href="#" onclick="selectProduct('{{ $product->id }}', '{{ $product->item_name }}'); return false;">
            {{ $product->item_name }}
            @if($product->shade)
                - <span style="color: #6c757d; font-size: 12px;">{{ $product->shade }}</span>
            @endif
        </a>
    </li>
    <li><hr class="dropdown-divider" /></li>
@endforeach

                            @foreach ($deals as $deal)
                            <li class="product-item">
                            <a class="dropdown-item" href="#" onclick="selectProduct('{{ $deal->id }}', '{{ $deal->deal_name }}'); return false;">
                            {{ $deal->deal_name }}
                            </a>
                            </li>
                            <li><hr class="dropdown-divider" /></li>
                            @endforeach
                            </ul>
                            <input type="hidden" id="selectedProductId" />
                          </div>
                        </div>


                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                
                  <div class="row">

                    <div class="col-md-9">
                      <div class="card card-round">
                        <div class="card-header">
                          <div class="card-head-row card-tools-still-right">
                          </div>
                        </div>
                        <div class="card-body p-0">
                          <div class="table-responsive">
                            <table class="table align-items-center mb-0" id="productTable">
                              <thead class="thead-light">
                                <tr>
                                  <th scope="col">Name</th>
                                  <th scope="col">Quantity</th>
                                  <th style="display: none" scope="col">Purchase Rate</th>
                                  <th scope="col">Retail Rate</th>
                                  <th scope="col">Sub-Total</th>
                                  {{--<th scope="col">Delete</th>--}}
                                </tr>
                              </thead>
                               <tbody>
                                              @foreach($sale->saleItems as $item)
                                              @php
                                              $unitPurchaseRate = $item->purchase_rate / max($item->product_quantity, 1);
                                              $productStock = \DB::table('products')->where('item_name', $item->product_name)->value('quantity') ?? 0;
                                          @endphp
                                          <tr>
                                              <td>
                                                  <input type="text" name="product_name[]" class="form-control form-control-sm item-name-input" 
                                                         value="{{ $item->product_name }}" readonly style="width:150px">
                                              </td>
                                            <td class="text-end">
    @php
        $isDealItem = \App\Models\DealSaleItem::where('sale_item_id', $item->id)->exists();
    @endphp

   @if ($isDealItem)
    <input type="number"
           name="deal_quantity[]"
           class="form-control form-control-sm deal-quantity-input only-up"
           value="{{ $item->product_quantity }}"
           min="1"
           data-stock="{{ $productStock }}"
           style="text-align:right;width:60px; background-color: #e8f7ff;">
@else
    <input type="number"
           name="product_quantity[]"
           class="form-control form-control-sm quantity-input only-up"
           value="{{ $item->product_quantity }}"
           min="1"
           data-initial-qty="{{ $item->product_quantity }}"
           data-stock="{{ $productStock }}"
           style="text-align:right;width:60px;" readonly>
@endif

</td>

                                              <td style="display: none">
                                                 <input type="number" name="purchase_rate[]" class="form-control form-control-sm purchase-rate-input"
    value="{{ $item->purchase_rate }}"
    data-base-rate="{{ number_format($unitPurchaseRate, 2, '.', '') }}"
    min="0" step="0.01" style="text-align:right;width:80px">

                                              </td>
                                              <td>
                                                  <input type="number" name="product_rate[]" class="form-control form-control-sm rate-input" 
                                                         value="{{ $item->product_rate }}" style="text-align:right;width:80px">
                                              </td>
                                              <td>
                                                  <input type="text" name="product_subtotal[]" class="form-control form-control-sm subtotal-input" 
                                                         value="{{ number_format($item->product_subtotal, 2) }}" readonly style="text-align:right;width:100px">
                                              </td>
                                              {{--<td>
                                                  <button class="btn btn-icon btn-round btn-danger btn-sm delete-row">
                                                      <i class="fa fa-trash"></i>
                                                  </button>
                                              </td>--}}
                                          </tr>
                                      @endforeach
                                      
                                                                            
                                            </tbody>
                               <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold">Total Items</td>
                                                    <td class="fw-bold">
                                                        <input type="number" id="totalItems" value="{{ $sale->total_items }}" name="total_items" class="form-control form-control-sm text-end fw-bold" style="width: fit-content" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                                    <td class="fw-bold">
                                                        <input type="number" id="totalAmount" value="{{ $sale->total }}" name="total" class="form-control form-control-sm text-end fw-bold" style="width: fit-content;" readonly>
                                                    </td>
                                                </tr>
                                            </tfoot>
                            </table>
                            
                          </div>
                        </div>
                      </div>

                       <div class="card card-round dealitemsection" >
                        <div class="card-header">
                          <div class="card-head-row card-tools-still-right">
                          </div>
                        </div>
                        <div class="card-body p-0">
                          <div class="table-responsive">
                            <table class="table align-items-center mb-0" id="dealitemTable">
                              <thead class="thead-light">
                                <tr>
                                  <th scope="col">Deal Name</th>
                                  <th scope="col">Name</th>
                                  <th scope="col">Quantity</th>
                                  <th style="display: none" scope="col">P.Rate</th>
                                  <th scope="col">R.Rate</th>
                                </tr>
                              </thead>
                              <tbody>
                             @foreach($sale->saleItems as $saleItem)
    @php
        $dealName = $saleItem->product_name;
        $dealId = str_replace(' ', '-', $dealName);
    @endphp

    <tbody class="deal-items" id="deal-items-{{ $dealId }}" data-deal-name="{{ $dealName }}">
        @foreach($saleItem->dealSaleItems as $deal)
            @php
                $dealRecord = \DB::table('deals')->where('deal_name', $deal->deal_name)->first();
                $dealIdFromDeals = $dealRecord->id ?? null;

                $originalQty = 0;
                if ($dealIdFromDeals) {
                    $originalQty = \DB::table('deal_items')
                        ->where('deal_id', $dealIdFromDeals)
                        ->where('products', $deal->deal_product_name)
                        ->value('quantity') ?? 0;
                }
            @endphp

            <tr data-stock="{{ \DB::table('products')->where('item_name', $deal->deal_product_name)->value('quantity') ?? 0 }}">
                <td>
                    <input type="text" name="deal_name[]" value="{{ $deal->deal_name }}" class="form-control form-control-sm" readonly>
                </td>
                <td>
                    <input type="text" name="deal_product_name[]" value="{{ $deal->deal_product_name }}" class="form-control form-control-sm" readonly>
                </td>
                <td>
                    <input 
                        type="number" 
                        name="deal_product_quantity[]" 
                        value="{{ $deal->deal_product_quantity }}" 
                        class="form-control form-control-sm deal-item-quantity-input" 
                        readonly 
                        data-base-quantity="{{ $originalQty }}">
                </td>
               
                <td style="display: none">
                    <input type="number" name="deal_product_purchase_rate[]" value="{{ $deal->deal_product_purchase_rate }}" class="form-control form-control-sm" readonly>
                </td>
                <td>
                    <input type="number" name="deal_product_retail_rate[]" value="{{ $deal->deal_product_retail_rate }}" class="form-control form-control-sm" readonly>
                </td>
            </tr>
        @endforeach
    </tbody>
@endforeach



                              </tbody>
                               
                            </table>
                            
                          </div>
                        </div>
                      </div>
                    </div>
                  
                    <!-- Right Side Panel -->
                   <div class="col-md-3">
                            <div class="card card-round shadow-sm">
                                <div class="card-body">
                                   <div class="card-head-row card-tools-still-right mb-3">
    <div class="fw-bold" style="font-size: 16px;">Sale Type</div>
    <div class="dropdown ms-auto">
        <select 
            class="form-select form-select-sm" 
            name="sale_type" 
            id="saleTypeSelect" 
            style="width: 150px; border-radius: 8px;"
            data-original="{{ $sale->sale_type }}"
        >
            <option value="cash" {{ $sale->sale_type === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="credit" {{ $sale->sale_type === 'credit' ? 'selected' : '' }}>Credit</option>
        </select>
    </div>
</div>

<div class="card-head-row card-tools-still-right mb-3">
    <div class="fw-bold" style="font-size: 16px;">Payment Type</div>
    <div class="dropdown ms-auto">
        <select 
            class="form-select form-select-sm" 
            name="payment_type" 
            id="paymentTypeSelect" 
            style="width: 150px; border-radius: 8px;"
            data-original="{{ $sale->payment_type }}"
        >
            <option value="cash" {{ $sale->payment_type === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="bank" {{ $sale->payment_type === 'bank' ? 'selected' : '' }}>Bank</option>
        </select>
    </div>
</div>

                
                                    <hr>
                
                                    <div class="card-list py-4">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Discount</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" value="{{ $sale->discount }}" type="text" name="discount" id="discount" style="width: 150px; border-radius: 8px;" value="0" />
                                        </div>
                                    </div>
                
                                    <div class="card-list py-1">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Amount After Discount</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" type="number" value="{{ $sale->amount_after_discount }}" name="amount_after_discount" id="amountafterdiscount" style="width: 120px; border-radius: 8px;" readonly />
                                        </div>
                                    </div>
                
                                    <hr>
                
                                    <div class="card-list py-4" id="fixedDiscountSection">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Fixed Discount</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" type="number" value="{{ $sale->fixed_discount }}" name="fixed_discount" id="fixeddiscount" style="width: 150px; border-radius: 8px;" value="0" readonly/>
                                        </div>
                                    </div>
                
                                    <div class="card-list py-1">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Amount After Fix-Discount</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" type="number" value="{{ $sale->amount_after_fix_discount }}" name="amount_after_fix_discount" id="amountafterfixdiscount" style="width: 120px; border-radius: 8px;" value="0" readonly/>
                                        </div>
                                    </div>
                
                                    <hr>
                
                                    <div class="card-list py-1">
                                        <div class="item-list d-flex align-items-center">
                                            <div class="info-user">
                                                <div class="fw-bold" style="font-size: 16px;">Total Rs:</div>
                                            </div>
                                            <input class="form-control form-control-sm ms-auto" value="{{ $sale->subtotal }}" type="number" name="subtotal" id="total" style="width: 150px; border-radius: 8px;" readonly/>
                                        </div>
                                    </div>
                
                                    <hr>
                
                                     <div class="d-flex justify-content-center mt-4">
    <button type="submit" class="btn btn-primary" style="width: 100px; border-radius: 8px;">Submit</button>
  </div>
                                </div>
                            </div>
                        </div>
                  <input type="text" id="barcodeInput" placeholder="Scan barcode" style="position:absolute; left:-9999px;" onkeydown="handleBarcodeScan(event)">

                  </div>
              </form>
                  
              </div>
        </div>

        @include('adminpages.footer')
      </div>
    </div>

    



    @include('adminpages.js')
    @include('adminpages.ajax')


<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input.only-up').forEach(input => {
        const initialQty = parseFloat(input.dataset.initialQty);

        input.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
            }
        });

        input.addEventListener('input', function () {
            const currentVal = parseFloat(this.value);

            if (isNaN(currentVal) || currentVal < initialQty) {
                this.value = initialQty;
            }
        });

        input.addEventListener('blur', function () {
            const currentVal = parseFloat(this.value);
            if (isNaN(currentVal) || currentVal < initialQty) {
                this.value = initialQty;
            }
        });
    });
});
</script>



     <script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.getElementById('productDropdownMenu');
        const searchInput = document.getElementById('productSearchInput');

        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(trigger => {
            trigger.addEventListener('shown.bs.dropdown', function () {
                setTimeout(() => searchInput.focus(), 100);
            });
        });
    });
</script>


    <script>
      $('#customerSelect').on('change', function () {
  var userName = $(this).val();

  $.ajax({
    url: '/get-customers-by-username/' + userName,
    type: 'GET',
    success: function (response) {
      var customers = response.customers;

      var $select = $('#smallSelect');
      $select.empty();
      $select.append('<option value="1">All</option>');

      customers.forEach(function (customer) {
        $select.append(`<option value="${customer.id}">${customer.customer_name}</option>`);
      });

      $('#fixeddiscount').val('');
      $('#fixedDiscountSection').hide();
    },
    error: function (xhr) {
      console.error('Error fetching customers:', xhr);
    }
  });
});


$('#smallSelect').on('change', function () {
  var customerId = $(this).val();

  if (customerId !== '1') {
    $.ajax({
      url: '/get-customer-discount/' + customerId,
      type: 'GET',
      success: function (response) {
        if (response.fixed_discount !== null) {
          $('#fixeddiscount').val(response.fixed_discount); 
          $('#fixedDiscountSection').show();
        } else {
          $('#fixeddiscount').val('');
          $('#fixedDiscountSection').hide();
        }

        updateTotals();
      },
      error: function (xhr) {
        console.error('Error fetching discount:', xhr);
      }
    });
  } else {
    $('#fixeddiscount').val('');
    $('#fixedDiscountSection').hide();
    updateTotals(); 
  }
});



 function updateTotals() {
  let totalItems = 0;
  let totalAmount = 0;

  $('#productTable tbody tr').each(function (index) {
    const qtyInput = $(this).find('.quantity-input, .deal-quantity-input');
    const quantity = parseInt(qtyInput.val()) || 0;

    const subtotalRaw = $(this).find('.subtotal-input').val() || '0';
    const subtotal = parseFloat(subtotalRaw.replace(/,/g, ''));

    console.log(`Row ${index + 1} subtotal:`, subtotal);

    totalItems += quantity; 
    totalAmount += subtotal;
  });


  $('#totalItems').val(totalItems); 
  $('#totalAmount').val(totalAmount.toFixed(2));

  const discount = parseFloat($('#discount').val()) || 0;
  const fixedDiscount = parseFloat($('#fixeddiscount').val()) || 0;

  const amountAfterDiscount = totalAmount - discount;
  $('#amountafterdiscount').val(amountAfterDiscount.toFixed(2));

  const amountAfterFixDiscount = amountAfterDiscount - fixedDiscount;
  $('#amountafterfixdiscount').val(amountAfterFixDiscount.toFixed(2));

  $('#total').val(amountAfterFixDiscount.toFixed(2));
}
    </script>
    
<script>
document.addEventListener("DOMContentLoaded", () => {
  const barcodeInput = document.getElementById("barcodeInput");

  barcodeInput.focus();

  document.addEventListener("click", (e) => {
    if (!['INPUT', 'SELECT', 'TEXTAREA'].includes(e.target.tagName)) {
      barcodeInput.focus();
    }
  });

  barcodeInput.addEventListener("keydown", handleBarcodeScan);
});

function handleBarcodeScan(e) {
  if (e.key === "Enter") {
    e.preventDefault(); 

    const barcode = e.target.value.trim();
    if (barcode) {
      fetchProductByBarcode(barcode);
      e.target.value = ""; 
    }
  }
}

function fetchProductByBarcode(barcode) {
  fetch(`/get-product-by-barcode/${barcode}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        selectProduct(data.product.id, data.product.item_name);
      } else {
        alert("Product not found.");
      }
    })
    .catch(error => {
      console.error('Fetch error:', error);
    });
}
</script>






<script>
 function filterDropdownItems() {
  const input = document.getElementById("productSearchInput");
  const filter = input.value.toLowerCase();

  const items = document.querySelectorAll("#productDropdownMenu li.product-item");

  items.forEach((item) => {
    const text = item.textContent || item.innerText;
    item.style.display = text.toLowerCase().includes(filter) ? "" : "none";
  });

  const allListItems = document.querySelectorAll("#productDropdownMenu li");

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


  $(document).ready(function () {
window.selectProduct = function (id, name) {
  const dropdownButton = document.getElementById("productSearchDropdown");
  dropdownButton.textContent = name;

  document.getElementById("selectedProductId").value = id;

  const dropdownInstance = bootstrap.Dropdown.getInstance(dropdownButton);
  if (dropdownInstance) dropdownInstance.hide();

  fetchProductDetails(id);
};

function fetchProductDetails(productId) {
  if (!productId) return;

  $.ajax({
    url: "/get-product-details/" + productId,
    type: "GET",
    success: function (response) {
      if (response.type === "product") {
        /*if (response.quantity <= 0) {
          Swal.fire({ icon: "warning", title: "Out of Stock", text: `The item \"${response.item_name}\" is currently out of stock.`, confirmButtonText: "OK" });
          return;
        }*/
        addOrUpdateProductRow(response);
        $("#dealName").text('');
        $("#dealPrice").text('');
      } else if (response.type === "deal") {
  $(".dealitemsection").show();
  const dealName = response.deal_name;
  const dealId = dealName.replace(/\s+/g, '-');

  addOrUpdateProductRow({
    item_name: dealName,
    single_retail_rate: response.single_retail_rate,
    single_purchase_rate: response.single_purchase_rate,
    quantity: response.quantity,
    type: "deal"
  });

  $("#dealName").text(dealName);
  $("#dealPrice").text(response.deal_price);

  let dealGrandTotal = 0;
  let tbodyId = `deal-items-${dealId}`;

  if (!$(`#${tbodyId}`).length) {
    $("#dealitemTable").append(`<tbody class="deal-items" data-deal-name="${dealName}" id="${tbodyId}"></tbody>`);
  }

  const $tbody = $(`#${tbodyId}`);
  const allProducts = @json($products);

  response.products.forEach(function (item) {
    const purchaseRate = parseFloat(item.single_purchase_rate);
    const retailRate = parseFloat(item.single_retail_rate);
    const baseQuantity = parseFloat(item.quantity);
    const itemTotal = purchaseRate * baseQuantity;

    const existingRow = $tbody.find(`input[name="deal_product_name[]"][value="${item.products}"]`).closest("tr");

    if (existingRow.length) {
      const qtyInput = existingRow.find('.deal-item-quantity-input');
      const oldQty = parseFloat(qtyInput.val()) || 0;
      const newQty = oldQty + baseQuantity;
      qtyInput.val(newQty);
      qtyInput.attr("data-base-quantity", newQty);
    } else {
      const product = allProducts.find(p => p.item_name === item.products) || { quantity: 0 };

      $tbody.append(`
        <tr data-stock="${product.quantity}">
          <td><input type="text" class="form-control form-control-sm" name="deal_name[]" value="${dealName}" readonly></td>
          <td><input type="text" class="form-control form-control-sm" name="deal_product_name[]" value="${item.products}" readonly></td>
          <td><input type="number" class="form-control form-control-sm deal-item-quantity-input" name="deal_product_quantity[]" value="${baseQuantity}" readonly min="1" data-base-quantity="${baseQuantity}"></td>
          <td style="display: none"><input type="number" class="form-control form-control-sm" name="deal_product_purchase_rate[]" readonly value="${purchaseRate.toFixed(2)}" step="0.01" min="0"></td>
          <td><input type="number" class="form-control form-control-sm" name="deal_product_retail_rate[]" readonly value="${retailRate.toFixed(2)}" step="0.01" min="0"></td>
        </tr>
      `);
    }
  });

  $tbody.find('.deal-total-row').remove();
  $tbody.find('tr').each(function () {
    const $row = $(this);
    const qty = parseFloat($row.find('input[name="deal_product_quantity[]"]').val()) || 0;
    const rate = parseFloat($row.find('input[name="deal_product_purchase_rate[]"]').val()) || 0;
    dealGrandTotal += qty * rate;
  });

  $tbody.append(`
    <tr class="deal-total-row">
      <td colspan="2" style="text-align: right;"><strong>Deal Total:</strong></td>
      <td><input type="number" id="deal-total-value-${dealId}" class="form-control form-control-sm" readonly value="${dealGrandTotal.toFixed(2)}"></td>
    </tr>
  `);

  updateTotals();

      }
    },
    error: function (xhr) {
      let errMsg = xhr.responseJSON?.message || "An error occurred";
      Swal.fire({ icon: "error", title: "Error", text: errMsg, confirmButtonText: "OK" });
    }
  });
}

$(document).off('input.dealQty').on('input.dealQty', '.deal-quantity-input', function () {
    console.log('hit');
    const $this = $(this);
    const newDealQty = parseInt($this.val()) || 1;

    const currentDealName = $this.closest('tr').find('.item-name-input').val() || '';
    const dealIdScoped = currentDealName.replace(/\s+/g, '-');

    let hasInsufficientStock = false;
    let insufficientProductName = '';

    $(`#deal-items-${dealIdScoped} tr`).each(function () {
        const $row = $(this);
        const dealName = $row.find('input[name="deal_name[]"]').val();

        if (dealName === currentDealName) {
            const $qtyInput = $row.find('input[name="deal_product_quantity[]"]');

            const baseQty = parseInt($qtyInput.attr('data-base-quantity')) || 0;

            const updatedQty = baseQty * newDealQty;

            const productStock = parseInt($row.attr('data-stock')) || 0;

            if (updatedQty > productStock) {
                hasInsufficientStock = true;
                insufficientProductName = $row.find('input[name="deal_product_name[]"]').val();
                return false; 
            }

            $qtyInput.val(updatedQty);
        }
    });

   /* if (hasInsufficientStock) {
        Swal.fire({
            icon: 'error',
            title: 'Insufficient Stock',
            text: `Not enough stock for "${insufficientProductName}". Please reduce quantity.`,
        });

        $this.val(1);
        return;
    }*/

    let newTotal = 0;
    $(`#deal-items-${dealIdScoped} .deal-item-quantity-input`).each(function () {
        const updatedQty = parseInt($(this).val()) || 0;
        const itemRate = parseFloat($(this).closest("tr").find('input[name="deal_product_purchase_rate[]"]').val()) || 0;
        newTotal += updatedQty * itemRate;
    });

    $(`#deal-total-value-${dealIdScoped}`).val(newTotal.toFixed(2));

    const $dealRow = $("#productTable tbody tr").filter(function () {
        return $(this).find(".item-name-input").val() === currentDealName;
    });

    const basePurchaseRate = parseFloat($dealRow.find(".purchase-rate-input").attr("data-base-rate")) || 0;
    const updatedPurchaseRate = basePurchaseRate * newDealQty;
    $dealRow.find(".purchase-rate-input").val(updatedPurchaseRate.toFixed(2));

    const retailRate = parseFloat($dealRow.find(".rate-input").val()) || 0;
    const newSubtotal = newDealQty * retailRate;
    $dealRow.find(".subtotal-input").val(newSubtotal.toFixed(2));

    updateTotals();
});


function addOrUpdateProductRow(product) {
  const itemName = product.item_name || product.products;
  const quantity = 1;
  const retailRate = parseFloat(product.single_retail_rate || 0);
  const purchaseRate = parseFloat(product.single_purchase_rate || 0);
  const subtotal = quantity * retailRate;
  const isDeal = product.type === "deal" || product.item_name === product.deal_name;

  const existingRow = $("#productTable tbody tr").filter(function () {
    return $(this).find(".item-name-input").val() === itemName;
  });

  if (existingRow.length > 0) {
    const qtyInputSelector = isDeal ? ".deal-quantity-input" : ".quantity-input";
    let currentQuantity = parseInt(existingRow.find(qtyInputSelector).val());
    let newQuantity = currentQuantity + 1;

    /*if (newQuantity > product.quantity) {
      Swal.fire({ icon: "warning", title: "Stock Limit Reached", text: `Only ${product.quantity} units available for \"${itemName}\".` });
      return;
    }*/

    existingRow.find(qtyInputSelector).val(newQuantity).attr("data-stock", product.quantity);

    const rate = parseFloat(existingRow.find(".rate-input").val());
    const newSubtotal = newQuantity * rate;
    existingRow.find(".subtotal-input").val(newSubtotal.toFixed(2));

    const basePurchaseRate = parseFloat(existingRow.find(".purchase-rate-input").attr("data-base-rate"));
    const newPurchaseRate = basePurchaseRate * newQuantity;
    existingRow.find(".purchase-rate-input").val(newPurchaseRate.toFixed(2));

    updateTotals();
  } else {
    const quantityInput = isDeal
      ? `<input type="number" name="deal_quantity[]" class="form-control form-control-sm deal-quantity-input" value="${quantity}" min="1" style="text-align:right;width:60px" data-stock="${product.quantity}">`
      : `<input type="number" name="product_quantity[]" class="form-control form-control-sm quantity-input" value="${quantity}" min="1" style="text-align:right;width:60px" data-stock="${product.quantity}">`;

    $("#productTable tbody").append(`
      <tr>
        <td><input type="text" name="product_name[]" class="form-control form-control-sm item-name-input" value="${itemName}" readonly style="width:150px"></td>
        <td>${quantityInput}</td>
        <td style="display: none"><input type="number" name="purchase_rate[]" class="form-control form-control-sm purchase-rate-input" value="${purchaseRate.toFixed(2)}" min="0" step="0.01" style="text-align:right;width:80px" data-base-rate="${purchaseRate.toFixed(2)}"></td>
        <td><input type="number" name="product_rate[]" class="form-control form-control-sm rate-input" value="${retailRate.toFixed(2)}" min="0" step="0.01" style="text-align:right;width:80px"></td>
        <td><input type="text" name="product_subtotal[]" class="form-control form-control-sm subtotal-input" value="${subtotal.toFixed(2)}" readonly style="text-align:right;width:100px"></td>
        <!--<td><button class="btn btn-icon btn-round btn-danger btn-sm delete-row"><i class="fa fa-trash"></i></button></td>-->
      </tr>
    `);

    updateTotals();
  }
}


const deletedItems = [];
const deletedDealItems = [];

$(document).on('click', '.delete-row', function () {
  const productRow = $(this).closest('tr');
  const productName = productRow.find('.item-name-input').val();

  if (productName) {
    deletedItems.push(productName);
  }

  productRow.remove();

  $('#dealitemTable tbody tr').each(function () {
    const row = $(this);
    const dealInput = row.find('input[name="deal_name[]"]');
    if (dealInput.length && dealInput.val() === productName) {
      const dealItemName = row.find('input[name="deal_product_name[]"]').val();
      if (dealItemName) {
        deletedDealItems.push(dealItemName);
      }
      row.remove();
    }
  });

  console.log('Deleted deal items:', deletedDealItems);

  updateTotals();
});

$(document).on("keydown", function (e) {
  if (e.ctrlKey && e.key === "s") {
    e.preventDefault();
    handleSaleFormSubmit();
  }
});

$("#editsaleForm").on("submit", function (e) {
  e.preventDefault();
  handleSaleFormSubmit();
});

function handleSaleFormSubmit() {
  const items = [];
  $("#productTable tbody tr").each(function () {
    const row = $(this);
    const name = row.find(".item-name-input").val();
    const isDeal = row.find(".deal-quantity-input").length > 0;

    if (name.trim() !== "") {
      items.push({
        product_name: name,
        product_quantity: !isDeal
          ? parseInt(row.find(".quantity-input").val()) || 1
          : undefined,
        deal_quantity: isDeal
          ? parseInt(row.find(".deal-quantity-input").val()) || 1
          : undefined,
        purchase_rate: parseFloat(row.find(".purchase-rate-input").val().replace(/,/g, '')) || 0,
        product_rate: parseFloat(row.find(".rate-input").val().replace(/,/g, '')) || 0,
        product_subtotal: parseFloat(row.find(".subtotal-input").val().replace(/,/g, '')) || 0,
      });
    }
  });

  const customerSelect = document.getElementById("smallSelect");
  const customerId = customerSelect.value;
  const customerName = customerSelect.options[customerSelect.selectedIndex].text;

  const dealProductNames = [];
  const dealProductQuantities = [];
  const dealProductPurchaseRates = [];
  const dealProductRetailRates = [];
  const dealnames = [];

  $("input[name='deal_product_name[]']").each(function () {
    dealProductNames.push($(this).val());
  });
  $("input[name='deal_product_quantity[]']").each(function () {
    dealProductQuantities.push(parseInt($(this).val()) || 0);
  });
  $("input[name='deal_product_purchase_rate[]']").each(function () {
    dealProductPurchaseRates.push(parseFloat($(this).val().replace(/,/g, '')) || 0);
  });
  $("input[name='deal_product_retail_rate[]']").each(function () {
    dealProductRetailRates.push(parseFloat($(this).val().replace(/,/g, '')) || 0);
  });
  $("input[name='deal_name[]']").each(function () {
    const name = $(this).val();
    if (name.trim() !== '') {
      dealnames.push(name);
    }
  });

  const paymentInput = document.querySelector('[name="payment_type"]');

  const formData = {
    employee: document.querySelector('[name="employee"]').value,
    customer_id: customerId,
    customer_name: customerName,
    created_at: document.querySelector('[name="created_at"]').value,
    ref: document.querySelector('[name="ref"]').value,
    sale_type: document.querySelector('[name="sale_type"]').value,
    payment_type: paymentInput ? paymentInput.value : null,
    discount: document.querySelector('[name="discount"]').value,
    total_items: document.querySelector('[name="total_items"]').value,
    total: document.querySelector('[name="total"]').value,
    amount_after_discount: document.querySelector('[name="amount_after_discount"]').value,
    fixed_discount: document.querySelector('[name="fixed_discount"]').value,
    amount_after_fix_discount: document.querySelector('[name="amount_after_fix_discount"]').value,
    subtotal: document.querySelector('[name="subtotal"]').value,
    items: items,
    deal_product_name: dealProductNames,
    deal_product_quantity: dealProductQuantities,
    deal_product_purchase_rate: dealProductPurchaseRates,
    deal_product_retail_rate: dealProductRetailRates,
    deal_name: dealnames,
    deleted_items: deletedItems,
    deleted_deal_items: deletedDealItems, 
  };

  const urlParts = window.location.pathname.split("/");
  const saleId = urlParts[urlParts.length - 1];

  fetch(`/submit-sale-form/${saleId}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify(formData),
  })
    .then((response) => {
      if (!response.ok) throw new Error("Network response was not ok");
      return response.json();
    })
    .then((data) => {
      Swal.fire({
        title: "Success!",
        text: "Sale updated successfully!",
        icon: "success",
        confirmButtonText: "OK",
      }).then(() => {
        loadsalelistPage();
      });
    })
    .catch((error) => {
      console.error("Error:", error);
      Swal.fire({
        title: "Error!",
        text: "There was an error updating the sale.",
        icon: "error",
        confirmButtonText: "OK",
      });
    });
}


  $(document).on("input", ".quantity-input, .rate-input", function () {
  const $row = $(this).closest("tr");

  let quantity = parseInt($row.find(".quantity-input").val()) || 1;
  let rate = parseFloat($row.find(".rate-input").val()) || 0;

  const prevQty = parseInt($row.find(".quantity-input").attr("data-initial-qty")) || 0;
  const stock = parseInt($row.find(".quantity-input").attr("data-stock")) || 0;

  const maxQty = prevQty + stock;

  if (quantity > maxQty) {
    Swal.fire({
      icon: "warning",
      title: "Stock Limit Reached",
      text: `You can only add up to ${stock} more items. Maximum allowed quantity is ${maxQty}.`,
    });
    quantity = maxQty;
    $row.find(".quantity-input").val(quantity);
  }


  const subtotal = quantity * rate;
  $row.find(".subtotal-input").val(subtotal.toFixed(2));

  const basePurchaseRate = parseFloat($row.find(".purchase-rate-input").attr("data-base-rate")) || 0;
  const newPurchaseRate = basePurchaseRate * quantity;
  $row.find(".purchase-rate-input").val(newPurchaseRate.toFixed(2));

  updateTotals();
});


  function updateTotals() {
  let totalItems = 0;
  let totalAmount = 0;

  $('#productTable tbody tr').each(function (index) {
    const qtyInput = $(this).find('.quantity-input, .deal-quantity-input');
    const quantity = parseInt(qtyInput.val()) || 0;

    const subtotalRaw = $(this).find('.subtotal-input').val() || '0';
    const subtotal = parseFloat(subtotalRaw.replace(/,/g, ''));

    console.log(`Row ${index + 1} subtotal:`, subtotal);

    totalItems += quantity; 
    totalAmount += subtotal;
  });


  $('#totalItems').val(totalItems); 
  $('#totalAmount').val(totalAmount.toFixed(2));

  const discount = parseFloat($('#discount').val()) || 0;
  const fixedDiscount = parseFloat($('#fixeddiscount').val()) || 0;

  const amountAfterDiscount = totalAmount - discount;
  $('#amountafterdiscount').val(amountAfterDiscount.toFixed(2));

  const amountAfterFixDiscount = amountAfterDiscount - fixedDiscount;
  $('#amountafterfixdiscount').val(amountAfterFixDiscount.toFixed(2));

  $('#total').val(amountAfterFixDiscount.toFixed(2));
}
});

$('#discount').on('input', function () {
  updateTotals();
});
</script>

  </body>
</html>