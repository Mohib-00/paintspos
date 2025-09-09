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
            .account-item.highlighted {
  background-color: #dbeafe !important;
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
                    <a class="user" href="/admin/voucher" onclick="loadvoucher(); return false;">Back</a>
                  </div>
                  <form id="jvvoucherform">
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
                            <label for="defaultSelect">Voucher Type</label>
                            <select class="form-select form-control" id="vendors" name="voucher_type">
                              <option>JV</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6 col-lg-4" style="display: none">
                          <div class="form-group">
                              <label for="cash_in_hand">Cash In Hand</label>
                              <input type="number" id="cashinhand" name="cash_in_hand" class="form-control" disabled>
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>

                       
                           @php
                           $today = \Carbon\Carbon::today()->toDateString();
                           $yesterday = \Carbon\Carbon::yesterday()->toDateString();
                           @endphp
                        @if(auth()->user()->vo_pastdate == '1')
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Voucher Date</label>
                              <input type="date" id="from_date" name="created_at" class="form-control" >
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>
                       @elseif(auth()->user()->vo_pastdate == '0')
                        <div class="col-md-6 col-lg-4">
                          <div class="form-group">
                              <label for="invoice_date">Voucher Date</label>
                              <input type="date" id="from_date" name="created_at" class="form-control" min="{{ $yesterday }}" max="{{ $today }}">
                              <span id="nameError" class="text-danger"></span>
                          </div>
                      </div>
                      @endif

                        <div class="col-md-12 col-lg-12">
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
<th style="background-color: #1a2035; color: white; width: 400px; min-width: 400px; max-width: 400px;">
  Account
</th>
                              <th style="background-color: #1a2035; color: white;">Balance</th>
                                    <th style="background-color: #1a2035; color: white;">Narration</th>
                                    <th style="background-color: #1a2035; color: white;width: 100px; min-width: 100px; max-width: 100px;">Debit</th>
                                    
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                     <tr>
                                    <td style="min-width: 400px; max-width: 400px; position: absolute;">
  <div class="dropdown w-100 account-product-dropdown" style="position: relative;">
    <button
      class="btn form-control account-dropdowm-toggle"
      type="button"
      onclick="toggleAccountDropdown(this)"
      style="
        border: 2px solid #eff0f3;
        width: 100%;
        text-align: left;
        padding-left: 0.5rem;
        padding-right: 2rem;
        position: relative;
        background-color: #fff;
      "
    >
      <span class="selected-account">Choose One</span>
      <span style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); pointer-events: none;">
        ▼
      </span>
    </button>

    <ul
      class="dropdown-menu account-dropdown-menu"
      style="
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1050;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      "
    >
      <li class="px-2 pt-2">
        <input type="text" class="form-control account-search-input" placeholder="Search..." onkeyup="filterAccounts(this)" />
      </li>
      <li><hr class="dropdown-divider" /></li>

      @foreach($accounts as $account)
        <li class="account-item">
          <a
            class="dropdown-item"
            href="#"
            onclick="selectAccount(this, '{{ $account->id }}', '{{ $account->sub_head_name }}'); return false;"
          >
            {{ $account->sub_head_name }}
          </a>
        </li>
        <li><hr class="dropdown-divider" /></li>
      @endforeach
    </ul>

    <input type="hidden" name="account[]" class="selectedAccountId" />
  </div>
</td>
                                    <td style="min-width: 5px; max-width: 5px;">
                                        <input type="number" name="balance[]" class="form-control balance" disabled>
                                    </td>
                                    <td style="min-width: 250px; max-width: 250px;">
                                        <input type="text" name="narration[]" class="form-control">
                                    </td>
                                    <td style="min-width: 200px; max-width: 200px;">
                                      <input type="number" name="amount[]" class="form-control amount" oninput="calculateTotal()">
                                  </td>
                                   
                                </tr>
                            </tbody>
                            <tfoot>
                              <tr>
                                <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                <td>
                                    <input type="number" class="form-control" id="totalAmount" readonly>
                                </td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                        
                        </div>
                      </div>
                    </div>
                    <div class="card-action">
                      <a id="submitjvdata" class="btn btn-success">Submit</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>







         <div class="container" style="margin-top:-20px">
          <div class="page-inner">
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                          <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                              <thead>
                                  <tr>
                                    <th style="background-color: #1a2035; color: white;">Account</th>
                    <th style="background-color: #1a2035; color: white;">Balance</th>
                    <th style="background-color: #1a2035; color: white;">Narration</th>
                    <th style="background-color: #1a2035; color: white;">Amount</th>
                    <th style="background-color: #1a2035; color: white;">Action</th>
                                  </tr>
                              </thead>
                             <tbody>
                @php $counter = 1; @endphp

                @foreach($vouchers as $voucher)
                    @if($voucher->voucher_type === 'JV')
                        @foreach($voucher->voucherItems as $item)
                            <tr id="voucher-row-{{ $voucher->id }}">
                                <td>{{ $accounts->firstWhere('id', $item->account)->sub_head_name ?? 'N/A' }}</td>
                                <td>{{ $item->balance }}</td>
                                <td>{{ $item->narration }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>
                                    @if($voucher->voucher_type === 'JV')
                                        <a data-voucher-id="{{ $voucher->id }}" onclick="loadcompletejvvoucherPage(this)" 
                                           class="btn btn-success btn-sm d-inline-flex align-items-center px-3 py-2 shadow-sm rounded">
                                           <i class="fa fa-check-circle me-2"></i> Complete
                                        </a>
                                         <a 
          data-voucher-id="{{ $voucher->id }}" 
           class="btn btn-danger btn-sm d-inline-flex align-items-center px-3 py-2 shadow-sm rounded delvoucher">
            <i class="fa fa-trash me-2"></i> Delete
        </a>

     
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
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
      $(document).on('click', '.delvoucher', function (e) {
    e.preventDefault();

    let voucherId = $(this).data('voucher-id');
    let url = `/voucher/${voucherId}`;
    let rowSelector = `#voucher-row-${voucherId}`;

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        $(rowSelector).remove();

                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function (xhr) {
                    Swal.fire(
                        'Error!',
                        xhr.responseJSON.message || 'Something went wrong!',
                        'error'
                    );
                }
            });
        }
    });
});
</script>


         <script>
   document.addEventListener('click', function(event) {
  const toggleBtn = event.target.closest('.account-dropdowm-toggle');
  if (toggleBtn) {
    const dropdownContainer = toggleBtn.closest('.account-product-dropdown');
    if (!dropdownContainer) return;

    const dropdownMenu = dropdownContainer.querySelector('.account-dropdown-menu');
    const searchInput = dropdownMenu.querySelector('.account-search-input');
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
    $('#vendors').on('change', function() {
        let voucherType = $(this).val();

        $.ajax({
            url: '/get-cash-in-hand',
            method: 'POST',
            data: {
                voucher_type: voucherType,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#cashinhand').val(response.cash_in_hand);
                $('.col-md-6.col-lg-4[style="display: none"]').show();
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
            }
        });
    });
});



  function toggleAccountDropdown(button) {
    const menu = button.nextElementSibling;
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  }

  function filterAccounts(input) {
  const filter = input.value.toLowerCase();
  const dropdownMenu = input.closest(".account-dropdown-menu");
  const items = dropdownMenu.querySelectorAll(".account-item");

  items.forEach(item => {
    const text = item.textContent.toLowerCase();
    item.style.display = text.includes(filter) ? "" : "none";
  });

  const allListItems = dropdownMenu.querySelectorAll("li");

  allListItems.forEach((li, index) => {
    const hr = li.querySelector("hr.dropdown-divider");
    if (hr) {
      const prev = allListItems[index - 1];
      const next = allListItems[index + 1];

      const prevHidden = !prev || (prev.classList.contains("account-item") && prev.style.display === "none");
      const nextHidden = !next || (next.classList.contains("account-item") && next.style.display === "none");

      li.style.display = (prevHidden && nextHidden) ? "none" : "";
    }
  });
}



document.addEventListener("keydown", function (e) {
  const input = document.activeElement;
  if (!input.classList.contains("account-search-input")) return;

  const dropdownMenu = input.closest(".account-dropdown-menu");
  const visibleItems = Array.from(dropdownMenu.querySelectorAll(".account-item"))
    .filter(item => item.style.display !== "none");

  if (visibleItems.length === 0) return;

  let currentIndex = visibleItems.findIndex(item => item.classList.contains("highlighted"));

  if (e.key === "ArrowDown") {
    e.preventDefault();
    if (currentIndex >= 0) visibleItems[currentIndex].classList.remove("highlighted");
    currentIndex = (currentIndex + 1) % visibleItems.length;
    visibleItems[currentIndex].classList.add("highlighted");
    visibleItems[currentIndex].scrollIntoView({ block: "nearest" });
  }

  if (e.key === "ArrowUp") {
    e.preventDefault();
    if (currentIndex >= 0) visibleItems[currentIndex].classList.remove("highlighted");
    currentIndex = (currentIndex - 1 + visibleItems.length) % visibleItems.length;
    visibleItems[currentIndex].classList.add("highlighted");
    visibleItems[currentIndex].scrollIntoView({ block: "nearest" });
  }

  if (e.key === "Enter") {
    e.preventDefault();
    if (currentIndex >= 0) {
      const a = visibleItems[currentIndex].querySelector("a");
      if (a) a.click();
    }
  }
});



  function selectAccount(element, id, name) {
    const wrapper = element.closest(".dropdown");
    wrapper.querySelector(".selected-account").textContent = name;
    wrapper.querySelector(".selectedAccountId").value = id;

    wrapper.querySelector(".account-dropdown-menu").style.display = "none";

    if (typeof updateProductData === "function") {
      updateProductData(wrapper.querySelector(".selectedAccountId"));
    }
  }

  document.addEventListener("click", function(e) {
    if (!e.target.closest(".dropdown")) {
      document.querySelectorAll(".account-dropdown-menu").forEach(menu => {
        menu.style.display = "none";
      });
    }
  });
  
function updateProductData(selectElement) {
    const accountId = selectElement.value;
    const row = selectElement.closest('tr');
    const balanceInput = row.querySelector('.balance');

    if (accountId) {
        $.ajax({
            url: '/get-account-balance',
            method: 'GET',
            data: { account_id: accountId },
            success: function(response) {
                if (response.balance !== undefined) {
                    balanceInput.value = response.balance;
                } else {
                    balanceInput.value = "0";
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                balanceInput.value = "0";
            }
        });
    } else {
        balanceInput.value = "0";
    }
}

function calculateTotal() {
    let total = 0;

    document.querySelectorAll('.amount').forEach(function(input) {
        const amount = parseFloat(input.value);
        if (!isNaN(amount)) {
            total += amount;
        }
    });

    document.getElementById('totalAmount').value = total.toFixed(2);
}

function addRow() {
    const rowHTML = `
        <tr>
            <td style="min-width: 400px; max-width: 400px; position: absolute;">
  <div class="dropdown w-100 account-product-dropdown" style="position: relative;">
    <button
      class="btn form-control account-dropdowm-toggle"
      type="button"
      onclick="toggleAccountDropdown(this)"
      style="
        border: 2px solid #eff0f3;
        width: 100%;
        text-align: left;
        padding-left: 0.5rem;
        padding-right: 2rem;
        position: relative;
        background-color: #fff;
      "
    >
      <span class="selected-account">Choose One</span>
      <span style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); pointer-events: none;">
        ▼
      </span>
    </button>

    <ul
      class="dropdown-menu account-dropdown-menu"
      style="
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1050;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      "
    >
      <li class="px-2 pt-2">
        <input type="text" class="form-control account-search-input" placeholder="Search..." onkeyup="filterAccounts(this)" />
      </li>
      <li><hr class="dropdown-divider" /></li>

      @foreach($accounts as $account)
        <li class="account-item">
          <a
            class="dropdown-item"
            href="#"
            onclick="selectAccount(this, '{{ $account->id }}', '{{ $account->sub_head_name }}'); return false;"
          >
            {{ $account->sub_head_name }}
          </a>
        </li>
        <li><hr class="dropdown-divider" /></li>
      @endforeach
    </ul>

    <input type="hidden" name="account[]" class="selectedAccountId" />
  </div>
</td>

            <td>
                <input type="number" name="balance[]" class="form-control balance" disabled>
            </td>
            <td>
                <input type="text" name="narration[]" class="form-control">
            </td>
            <td>
                <input type="number" name="amount[]" class="form-control amount" oninput="calculateTotal()">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
            </td>
        </tr>
    `;

    document.getElementById('tableBody').insertAdjacentHTML('beforeend', rowHTML);
}

function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
    calculateTotal();
}

$(document).ready(function() {
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            $('#submitjvdata').click();
        }
    });

    $('#submitjvdata').on('click', function(e) {
        e.preventDefault();

        let formData = $('#jvvoucherform').serializeArray();
        let totalAmount = $('#totalAmount').val();
        let cashInHand = $('#cashinhand').val();
        
        $('.balance').each(function() {
            formData.push({ name: 'balance[]', value: $(this).val() });
        });

        formData.push({ name: 'cash_in_hand', value: cashInHand });
        formData.push({ name: 'totalAmount', value: totalAmount });

        $.ajax({
            url: '/save-jv-voucher',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        confirmButtonText: 'OK',
                        allowOutsideClick: false
                    }).then(() => {
                        $('#jvvoucherform')[0].reset();
                        $('#totalAmount').val('0');
                        $('#cashinhand').val('0');
                        loadjvvoucherPage();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error while creating JV voucher.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});

  </script>
  
  
  
      
      
  </body>
</html>