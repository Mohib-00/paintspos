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
                  <form id="vouchercompletejvform">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="receiving_location">Receiving Location*</label>
                    <select class="form-select form-control" id="receiving_location" name="receiving_location">
                        <option>Head Office</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="voucher_type">Voucher Type</label>
                    <select class="form-select form-control" name="voucher_type">
                        <option value="">Choose One</option>
                        <option value="JV" {{ $vouchers->voucher_type == 'JV' ? 'selected' : '' }}>JV</option>

                    </select>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="cashinhand">Cash In Hand</label>
                    <input type="number" id="cashinhand" name="cash_in_hand" class="form-control" value="{{ $vouchers->cash_in_hand }}" readonly>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="created_at">Voucher Date</label>
                    <input type="date" id="created_at" name="created_at" class="form-control" value="{{ $vouchers->created_at->format('Y-m-d') }}">
                </div>
            </div>

            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <input class="form-control" type="text" name="remarks" value="{{ $vouchers->remarks }}">
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered" id="productTable">
                    <thead>
                        <tr>
                            <th style="background-color: #1a2035; color: white; min-width: 400px; max-width: 400px; width: 400px;">Account</th>
                            <th style="background-color: #1a2035; color: white;">Narration</th>
                            <th style="background-color: #1a2035; color: white;">Credit</th>
                            <th style="background-color: #1a2035; color: white;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($vouchers->voucherItems as $item)
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
      <span class="selected-account">
        {{ $accounts->firstWhere('id', $item->account)?->sub_head_name ?? 'Choose One' }}
      </span>
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
        padding-left: 0;
        margin: 0;
        list-style: none;
      "
    >
      <li class="px-2 pt-2">
        <input type="text" class="form-control account-search-input" placeholder="Search..." onkeyup="filterAccounts(this)" />
      </li>
      <li><hr class="dropdown-divider" /></li>

      @foreach($accounts as $account)
<li class="account-item">
  <a
    href="#"
    class="dropdown-item"
    data-account-id="{{ $account->id }}"
    data-account-name="{{ $account->sub_head_name }}"
    onclick="selectAccount(this, '{{ $account->id }}', '{{ $account->sub_head_name }}'); return false;"
  >
    {{ $account->sub_head_name }}
  </a>
</li>
<li><hr class="dropdown-divider" /></li>
@endforeach

    </ul>

    <input type="hidden" name="account[]" class="selectedAccountId" value="{{ $item->account }}" onchange="updateProductData(this)" />
  </div>
</td>
                            <td>
                                <input type="text" name="narration[]" value="{{ $item->narration }}" class="form-control">
                            </td>
                            <td>
                                <input type="number" name="amount[]" value="{{ $item->amount }}" class="form-control amount" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total:</strong></td>
                            <td><input type="number" id="totalAmount" class="form-control" value="{{ $vouchers->totalAmount }}" readonly></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="card-action">
        <button type="button" id="submitcomplatejvvoucherdata" class="btn btn-success">Submit</button>
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
  <div class="dropdown w-100" style="position: relative;">
    <button
      class="btn form-control"
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
            $('#submitcomplatejvvoucherdata').click();
        }
    });

    $('#submitcomplatejvvoucherdata').on('click', function(e) {
        e.preventDefault();
        let voucherId = window.location.pathname.split('/').pop();
        let formData = $('#vouchercompletejvform').serializeArray();


        let totalAmount = $('#totalAmount').val();
        formData.push({ name: 'totalAmount', value: totalAmount });

        $.ajax({
            url: '/complatejv_voucher/' + voucherId,
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
                        text: 'Voucher Completed successfully',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#totalAmount').val('0');
                        loadvoucher();
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error while completing voucher.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});


  </script>
  
  
  
      
      
  </body>
</html>