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
      transition: background-color 0.3s ease;
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
                  <a class="user" href="/admin/sale_list" onclick="loadsalePage(); return false;">Back</a>
                </div>
                <form id="salereuturnform">
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
                          <label for="invoice_no">Bill#</label>
                          <input class="form-control" type="text" id="id" name="id" value="S{{$sale->id}}" readonly>
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>

                       <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="invoice_no">Sale Type</label>
                          <input readonly class="form-control" type="text" id="sale_type" name="sale_type" value={{$sale->sale_type}}>
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>

                       <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="invoice_no">Payment Type</label>
                          <input readonly class="form-control" type="text" id="payment_type" name="payment_type" value={{$sale->payment_type}}>
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>

                       {{--<div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="invoice_no">Code</label>
                          <input class="form-control" type="text" id="id" name="id" value="S{{$sale->id}}">
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>--}}

                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="invoice_no">Invoice No</label>
                          <input class="form-control" type="text" id="ref" name="ref" value="{{$sale->ref}}" readonly>
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>

                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="invoice_date">Invoice Date</label>
                          <input type="date" id="from_date" name="created_at" class="form-control" value="{{ $sale->created_at->format('Y-m-d') }}" readonly>
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>

                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="customer_name">Customer Name</label>
                          <input type="text" id="customer_name" name="customer_name" class="form-control" value="{{ $sale->customer_name}}" readonly>
                          <span id="nameError" class="text-danger"></span>
                        </div>
                      </div>

                      <div class="table-responsive mt-3">
                        <table class="table table-bordered" id="productTable">
                          <thead>
                            <tr>
                              <th style="background-color: #1a2035; color: white;width:200px">Product</th>
                              <th style="background-color: #1a2035; color: white;width:100px;white-space:nowrap">R.Qty</th>
                              <th style="background-color: #1a2035; color: white;">Qty</th>
                              <th style="background-color: #1a2035; color: white;">SPR</th>
                              <th style="background-color: #1a2035; color: white;white-space:nowrap">Purchase Rate</th>
                              <th style="background-color: #1a2035; color: white;">SRR</th>
                              <th style="background-color: #1a2035; color: white;">Rate</th>
                              <th style="background-color: #1a2035; color: white;">Amount</th>
                              <th style="background-color: #1a2035; color: white;white-space:nowrap">Return Amount</th>
                            </tr>
                          </thead>
                          <tbody id="tableBody">
                            @foreach($sale->saleItems as $item)
                              @php
                                $unitPurchaseRate = $item->purchase_rate / max($item->product_quantity, 1);
                                $productStock = \DB::table('products')->where('item_name', $item->product_name)->value('quantity') ?? 0;
                              @endphp
                              <tr>
                                <td>
                                  <input type="text" name="product_name[]" class="form-control form-control-sm item-name-input" 
                                         value="{{ $item->product_name }}" readonly style="width:150px" >
                                </td>

                                @php
                                 $isDealItem = \App\Models\DealSaleItem::where('sale_item_id', $item->id)->exists();
                                @endphp
                                @if ($isDealItem)
                               <td class="text-end">
 <input 
    type="number"  
    name="return_quantity[]" 
    class="form-control form-control-sm return-qty" 
    value="{{ $item->return_qty > 0 ? $item->return_qty : 0 }}"     
    min="0" 
    data-stock="{{ $productStock }}" 
    data-type="deal"
    data-deal-name="{{ $item->product_name }}"
    data-original-deal-qty="{{ $item->product_quantity }}"
    style="text-align:right;width:60px">

</td>
                                @else
                                <td class="text-end">
                                  <input type="number"  name="return_quantity[]" class="form-control form-control-sm return-qty"
                                     value="{{ $item->return_qty > 0 ? $item->return_qty : 0 }}"     min="0" data-stock="{{ $productStock }}" style="text-align:right;width:60px">
                                </td>
                                @endif

                                @if ($isDealItem)
                                <td class="text-end">
                                  <input readonly type="number" name="product_quantity[]" class="form-control form-control-sm quantity-input"
                                         value="{{ $item->product_quantity }}" min="1" data-stock="{{ $productStock }}"
                                         style="text-align:right;width:60px">
                                </td>
                                @else
                                 <td class="text-end">
                                  <input readonly type="number" name="product_quantity[]" class="form-control form-control-sm quantity-input"
                                         value="{{ $item->product_quantity }}" min="1" data-stock="{{ $productStock }}"
                                         style="text-align:right;width:60px">
                                </td>
                                 @endif

                                 @if ($isDealItem)
                                <td>
                                  <input readonly type="text" name="single_purchase_rate[]" 
                                         class="form-control form-control-sm" 
                                          value="{{ $item->purchase_rate / $item->product_quantity * $item->return_qty }}" style="width:100px">
                                </td>
                                 @else
                                   <td>
                                  <input readonly type="text" name="single_purchase_rate[]" 
                                         class="form-control form-control-sm" 
                                         value="{{ $item->single_purchase_rate }}" style="width:100px">
                                </td>
                                @endif

                                <td>
                                  <input readonly type="number" name="purchase_rate[]" class="form-control form-control-sm purchase-rate-input"
                                         value="{{ $item->purchase_rate }}"
                                         data-unit-purchase="{{ number_format($unitPurchaseRate, 2, '.', '') }}"
                                         min="0" step="0.01" style="text-align:right;width:80px">
                                </td>

                                <td>
                                  <input readonly type="number" name="product_rate[]" class="form-control form-control-sm rate-input"
                                         value="{{ $item->product_rate }}" style="text-align:right;width:80px">
                                </td>

                                <td>
                                  <input readonly type="number" name="product_rate[]" class="form-control form-control-sm rate-input" 
                                         value="{{ $item->product_subtotal }}" style="text-align:right;width:80px">
                                </td>

                                <td>
                                  <input readonly type="text" name="product_subtotal[]" class="form-control form-control-sm subtotal-input" 
                                         value="{{ number_format($item->product_subtotal, 2) }}" style="text-align:right;width:100px">
                                </td>

                               <td>
                                  <input readonly type="text" value="{{ $item->return_amount > 0 ? $item->return_amount : 0 }}" name="return_amount[]" class="form-control form-control-sm salereturn" style="text-align:right;width:100px">
                                </td>
                              </tr>

                              
                            @endforeach
                          </tbody>
                        </table>
                      </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Net Amount</label>
                            <input class="form-control" type="number" id="total" name="total" value="{{ number_format($sale->total, 2, '.', '') }}" readonly>
                          </div>
                        </div>

                         <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Discount</label>
                            @php
                            $discount = 0;

                            /*if (!empty($sale->discount)) {
                            $discount += $sale->discount;
                            }*/

                            if (!empty($sale->fixed_discount)) {
                            $discount += $sale->fixed_discount;
                            }

                            $discountValue = $discount > 0 ? number_format($discount, 2, '.', '') : '';
                            @endphp

                           <input type="number" class="form-control" name="discount" id="discount"  readonly value="{{ $discountValue }}">
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                            <label for="remarks">Amount Recieved</label>
                            <input type="number" name="gross_amount" class="form-control" id="grossAmount" value="{{ $sale->status === 'complete' ? number_format($sale->subtotal, 2, '.', '') : '' }}" readonly>                          </div>
                        </div>


                       <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                          <label for="salereturn">Gross Amount</label>
                          <input type="number" value="{{ $sale->sale_return }}" name="sale_return" class="form-control" id="salereturn" readonly>
                          </div>
                       </div>


                         <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                          <label for="remarks">Amount Payed</label>
                          <input type="number" name="amount_payed_return" value="{{ $sale->amount_payed_return }}" class="form-control" id="amountpayed" >
                          </div>
                        </div>



                        @php
    $hasDeals = false;
    foreach ($sale->saleItems as $saleItem) {
        if (count($saleItem->dealSaleItems)) {
            $hasDeals = true;
            break;
        }
    }
@endphp

@if($hasDeals)
<div class="table-responsive mt-3">
  <table class="table table-bordered" id="dealItemsTable">
    <thead>
      <tr>
        <th style="background-color: #1a2035; color: white;width:100px;white-space:nowrap">Deal Name</th>
        <th style="background-color: #1a2035; color: white;">Name</th>
        <th style="background-color: #1a2035; color: white;">R.Qty</th>
         <th style="background-color: #1a2035; color: white;">Qty</th>
        <th style="background-color: #1a2035; color: white;white-space:nowrap;">P.Rate</th>
        <th style="background-color: #1a2035; color: white;">R.Rate</th>
      </tr>
    </thead>
    <tbody id="tableBody">
      @php
          $dealsMap = \DB::table('deals')->pluck('id', 'deal_name'); 
          $dealItemsMap = \DB::table('deal_items')
              ->select('deal_id', 'products', 'quantity')
              ->get()
              ->keyBy(fn($item) => $item->deal_id . '_' . $item->products); 
          $productStockMap = \DB::table('products')->pluck('quantity', 'item_name'); 
      @endphp

      @foreach($sale->saleItems as $saleItem)
          @php
              $dealName = $saleItem->product_name;
              $dealId = str_replace(' ', '-', $dealName);
          @endphp

          <tbody class="deal-items" id="deal-items-{{ $dealId }}" data-deal-name="{{ $dealName }}">
              @foreach($saleItem->dealSaleItems as $deal)
                  @php
                      $dealIdFromDeals = $dealsMap[$deal->deal_name] ?? null;
                      $originalQty = 0;
                      if ($dealIdFromDeals) {
                          $key = $dealIdFromDeals . '_' . $deal->deal_product_name;
                          $originalQty = $dealItemsMap[$key]->quantity ?? 0;
                      }
                      $stock = $productStockMap[$deal->deal_product_name] ?? 0;
                  @endphp

                  <tr data-stock="{{ $stock }}">
                      <td>
                        <input type="hidden" name="deal_sale_item_id[]" value="{{ $deal->id }}">
    <input type="text" name="deal_name[]" value="{{ $deal->deal_name }}" class="form-control form-control-sm" readonly>
  </td>
                      <td>
                          <input type="text" name="deal_product_name[]" value="{{ $deal->deal_product_name }}" class="form-control form-control-sm" readonly>
                      </td>

                      

                       <td>
    <input 
      type="number" 
      name="return_qty[]" 
      class="form-control form-control-sm deal-item-quantity-input" 
      value="{{ $deal->return_qty }}" 
      data-type="deal" 
      readonly 
      >
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
                      <td>
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
@endif


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
  $('#submitdata').on('click', function () {
    const saleId = window.location.pathname.split('/').pop();
    const $form = $('#salereuturnform');

    // Remove any existing dynamically added item_type[] inputs
    $form.find('input[name="item_type[]"]').remove();

    // For each return_quantity[] input, check data-type
    $form.find('input[name="return_quantity[]"]').each(function () {
      const type = $(this).data('type') === 'deal' ? 'deal' : 'normal';

      // Create hidden input and append to form
      const hiddenInput = $('<input>', {
        type: 'hidden',
        name: 'item_type[]',
        value: type
      });
      $form.append(hiddenInput);
    });

    // Now serialize the form including the new hidden inputs
    const formData = $form.serialize();

    $.ajax({
      url: `/sale_return/${saleId}`,
      type: 'POST',
      data: formData,
      headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
      },
      success: function (response) {
        if (response.status === 'success') {
          alert(response.message);
        } else {
          alert('Error processing return.');
        }
      },
      error: function (xhr) {
        console.error(xhr.responseText);
        alert('AJAX error occurred.');
      }
    });
  });
</script>




<script>
document.getElementById('amountpayed').addEventListener('input', function () {
    const amountPayed = parseFloat(this.value);
    const grossField = document.getElementById('salereturn');
    const currentGross = parseFloat(grossField.value);

    if (!grossField.value && !isNaN(amountPayed)) {
        grossField.value = amountPayed;
    } 
    else if (!isNaN(amountPayed) && amountPayed > currentGross) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Amount',
            text: 'Amount Payed cannot be more than Gross Amount.',
        });
        this.value = '';
    }
});
</script>


  <script>
  function updateSalereturnTotal() {
    let total = 0;
    document.querySelectorAll('.salereturn').forEach(function (input) {
      let val = parseFloat(input.value);
      if (!isNaN(val)) {
        total += val;
      }
    });
    document.getElementById('salereturn').value = total.toFixed(2);
  }

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.return-qty').forEach(function (qtyInput) {
    qtyInput.addEventListener('input', function () {
      const row = qtyInput.closest('tr');
      const rateInput = row.querySelector('.rate-input');
      const saleReturnInput = row.querySelector('.salereturn');
      const maxQtyInput = row.querySelector('.quantity-input');

      const qty = parseFloat(qtyInput.value) || 0;
      const maxQty = parseFloat(maxQtyInput?.value) || 0;

      if (qty > maxQty) {
        Swal.fire({
          icon: 'warning',
          title: 'Quantity Exceeded',
          text: 'Return quantity cannot be greater than available stock!',
          confirmButtonColor: '#3085d6'
        });

        qtyInput.value = maxQty;
        if (saleReturnInput && rateInput) {
          saleReturnInput.value = (maxQty * parseFloat(rateInput.value || 0)).toFixed(2);
          updateSalereturnTotal();
        }
        return;
      }

      const rate = parseFloat(rateInput?.value) || 0;
      if (saleReturnInput) {
        saleReturnInput.value = (qty * rate).toFixed(2);
        updateSalereturnTotal();
      }

      const type = qtyInput.dataset.type;
      const dealName = qtyInput.dataset.dealName;
      const originalDealQty = parseFloat(qtyInput.dataset.originalDealQty) || 0;

     if (type === 'deal' && dealName) {
  const newDealQty = originalDealQty - qty;

  document.querySelectorAll('#dealItemsTable tbody tr').forEach(function (dealRow) {
    const dealNameInput = dealRow.querySelector('input[name="deal_name[]"]');
    const dealReturnQtyInput = dealRow.querySelector('input[name="return_qty[]"]');

    if (dealNameInput && dealReturnQtyInput && dealNameInput.value === dealName) {
      const dealQtyInput = dealRow.querySelector('input[name="deal_product_quantity[]"]');
      const baseQty = parseFloat(dealQtyInput?.dataset.baseQuantity) || 0;

      const totalReturnQty = qty * baseQty;

      dealReturnQtyInput.value = totalReturnQty >= 0 ? totalReturnQty : 0;
    }
  });
}

    });
  });
});


document.addEventListener("DOMContentLoaded", function () {
    const dealReturnInputs = document.querySelectorAll('input.return-qty[data-type="deal"]');

    dealReturnInputs.forEach(input => {
        input.addEventListener('input', function () {
            const dealName = input.dataset.dealName;
            const dealTable = document.querySelector(`#deal-items-${dealName.replace(/\s+/g, '-')}`);
            const purchaseRateInputs = dealTable.querySelectorAll('input[name="deal_product_purchase_rate[]"]');
            const returnQtyInputs = dealTable.querySelectorAll('input[name="return_qty[]"]');

            let total = 0;

            purchaseRateInputs.forEach((rateInput, index) => {
                const rate = parseFloat(rateInput.value) || 0;
                const qty = parseFloat(returnQtyInputs[index]?.value) || 0;
                total += rate * qty;
            });

            const mainRow = input.closest('tr');
            const purchaseRateInput = mainRow.querySelector('input[name="single_purchase_rate[]"]');
            if (purchaseRateInput) {
                purchaseRateInput.value = total.toFixed(2);
            }
        });
    });
});


   function loadsalePage() {
       loadPage('/admin/sale_list', '/admin/sale_list');
   }
</script>

</body>
</html>
