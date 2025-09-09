
 <div class="main-header" style="background: linear-gradient(45deg, #1A2035,#1A2035,#1A2035,#1A2035,#1A2035, #1A2035, #1A2035,#1A2035, #1A2035,#1A2035,#1A2035,#1A2035,#1A2035);background-size: 300% 300%;">
  <div class="main-header-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="index.html" class="logo">
        <img
          src="{{asset('lite/assets/img/kaiadmin/logo_light.svg')}}"
          alt="navbar brand"
          class="navbar-brand image"
          height="20"
        />
      </a>
      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
          <i class="gg-menu-right"></i>
        </button>
        <button class="btn btn-toggle sidenav-toggler">
          <i class="gg-menu-left"></i>
        </button>
      </div>
      <button class="topbar-toggler more">
        <i class="gg-more-vertical-alt"></i>
      </button>
    </div>
    <!-- End Logo Header -->
  </div>
  <!-- Navbar Header -->
  <nav
    class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
  >
    <div class="container-fluid">
      <nav
        class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
      >
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="dropdown w-100" style="position: relative;">
  <button
    class="btn form-control"
    type="button"
    onclick="toggleRouteDropdown()"
    style="
      border: 2px solid #3a3b3c;
      width: 100%;
      text-align: left;
      padding-left: 0.75rem;
      padding-right: 2rem;
      position: relative;
      background-color: #1e1e2f;
      color: white;
      font-weight: 500;
      border-radius: 6px;
    "
  >
    Search...
    <span style="
      position: absolute;
      right: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: white;
      font-size: 0.9rem;
    ">
      <i class="fa fa-search"></i>
    </span>
  </button>

  <ul
    id="routeDropdown"
    style="
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      max-height: 300px;
      overflow-y: auto;
      z-index: 1050;
      background-color: #2c2c3e;
      border: 1px solid #444;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      list-style: none;
      padding: 0;
      margin: 0;
      border-radius: 6px;

      /* Firefox scrollbar */
      scrollbar-width: thin;
      scrollbar-color: #555 #2c2c3e;
    "
  >
    <li style="padding: 0.75rem;">
      <input
        type="text"
        id="searchBox"
        placeholder="Search pages..."
        oninput="filterRoutes(this.value)"
        onkeydown="handleKeyDown(event)"
        style="
          width: 100%;
          padding: 8px 12px;
          background-color: #1e1e2f;
          color: white;
          border: 1px solid #555;
          border-radius: 5px;
          outline: none;
        "
      />
    </li>
    <li><hr style="margin: 0; border-color: #444;" /></li>
    <div id="routeList"></div>
  </ul>
</div>

<style>
  #routeDropdown::-webkit-scrollbar {
    width: 8px;
  }

  #routeDropdown::-webkit-scrollbar-track {
    background: #2c2c3e;
    border-radius: 10px;
  }

  #routeDropdown::-webkit-scrollbar-thumb {
    background-color: #555;
    border-radius: 10px;
    border: 2px solid #2c2c3e;
  }

  #routeDropdown::-webkit-scrollbar-thumb:hover {
    background-color: #777;
  }
</style>

<script>
  const routes = {
    "admin": "admin", "users": "users", "format": "format", "add_user": "add_user", "add_vendor": "add_vendor",
    "area": "area", "customer_list": "customer_list", "add_customer": "add_customer",
    "blocked_client_list": "blocked_client_list", "employees_list": "employees_list", "add_employee": "add_employee",
    "employees_leave": "employees_leave", "designation": "designation", "employee_attendance": "employee_attendance",
    "employee_attendance_report": "employee_attendance_report", "company_list": "company_list",
    "category_list": "category_list", "subcategory_list": "subcategory_list", "products_list": "products_list",
    "product_price_list": "product_price_list", "product_import": "product_import", "purchase_list": "purchase_list",
    "GRN": "GRN", "chart_of_account": "chart_of_account", "add_account": "add_account", "payment": "payment",
    "POS": "POS", "POS_2": "POS_2", "sale_list": "sale_list", "add_voucher": "add_voucher", "voucher": "voucher",
    "salary": "salary", "sale_report": "sale_report", "stock_report": "stock_report", "jv_voucher": "jv_voucher",
    "day_close_report": "day_close_report", "general_ledger": "general_ledger", "payed_salary": "payed_salary",
    "purchase_return": "purchase_return", "profit_loss_report": "profit_loss_report", "trial_balance": "trial_balance",
    "backup_reset": "backup_reset", "balance_sheet": "balance_sheet", "customer_report": "customer_report",
    "vendor_report": "vendor_report", "deal_list": "deal_list", "add_deal": "add_deal",
    "manufacture_company_list": "manufacture_company_list", "manufacture_category_list": "manufacture_category_list",
    "raw_material_list": "raw_material_list", "material_purchase": "material_purchase",
    "purchase_report": "purchase_report", "vehicle_list": "vehicle_list", "vehicle_record_add": "vehicle_record_add",
    "vehicle_alert": "vehicle_alert"
  };

  let currentFocus = -1;

  function toggleRouteDropdown() {
    const dropdown = document.getElementById("routeDropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    const searchBox = document.getElementById("searchBox");
    searchBox.focus();
    filterRoutes("");
  }

  function filterRoutes(query) {
    const routeList = document.getElementById("routeList");
    routeList.innerHTML = "";
    currentFocus = -1;

    const matches = Object.keys(routes).filter(key =>
      key.toLowerCase().includes(query.toLowerCase().trim())
    );

    if (matches.length === 0) {
      const li = document.createElement("li");
      li.textContent = "No match found";
      li.style.color = "#ccc";
      li.style.padding = "0.5rem 1rem";
      routeList.appendChild(li);
      return;
    }

    matches.forEach((key) => {
      const li = document.createElement("li");
      li.classList.add("route-item");
      li.setAttribute("data-key", key);
      li.innerHTML = `
        <a href="#" onclick="navigateToPage('${key}'); return false;"
           style="display: block; padding: 0.6rem 1rem; text-decoration: none; color: white; font-weight: 500;">
           ${key}
        </a>
        <hr style="margin: 0; border-color: #444;" />
      `;
      routeList.appendChild(li);
    });
  }

  function handleKeyDown(e) {
    let items = document.querySelectorAll(".route-item");

    if (e.key === "ArrowDown") {
      currentFocus++;
      addActive(items);
    } else if (e.key === "ArrowUp") {
      currentFocus--;
      addActive(items);
    } else if (e.key === "Enter") {
      e.preventDefault();
      if (items[currentFocus]) {
        const key = items[currentFocus].getAttribute("data-key");
        navigateToPage(key);
      }
    }
  }

  function addActive(items) {
    if (!items.length) return;
    removeActive(items);
    if (currentFocus >= items.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = items.length - 1;

    const link = items[currentFocus].querySelector("a");
    items[currentFocus].style.backgroundColor = "#3a3a4d";
    link.scrollIntoView({ block: "nearest" });
  }

  function removeActive(items) {
    items.forEach(item => item.style.backgroundColor = "transparent");
  }

  function navigateToPage(key) {
    const route = key === "admin" ? "/" + routes[key] : "/admin/" + routes[key];
    window.location.href = route;
  }

  document.addEventListener("click", function (e) {
    if (!e.target.closest(".dropdown")) {
      document.getElementById("routeDropdown").style.display = "none";
    }
  });
</script>

      
      </nav>

      <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
        <li
          class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
        >
          <a
            class="nav-link dropdown-toggle"
            data-bs-toggle="dropdown"
            href="#"
            role="button"
            aria-expanded="false"
            aria-haspopup="true"
          >
            <i class="fa fa-search"></i>
          </a>
          <ul class="dropdown-menu dropdown-search animated fadeIn">
            <form class="navbar-left navbar-form nav-search">
              <div class="input-group">
                <input
                  type="text"
                  placeholder="Search ..."
                  class="form-control"
                />
              </div>
            </form>
          </ul>
        </li>
     
      
    

        <li class="nav-item topbar-user dropdown hidden-caret">
          <a
            class="dropdown-toggle profile-pic"
            data-bs-toggle="dropdown"
            href="#"
            aria-expanded="false"
          >
            <div class="avatar-sm">
              @if(Auth::check() && Auth::user()->userType == 1)
              <img
              src="{{ Auth::user()->image ? asset('images/' . Auth::user()->image) : '' }}" 
                alt="..."
                class="avatar-img rounded-circle image"
              />
              @else
              <img class="avatar-img rounded-circle image" src="{{ asset('images/dummy-image.jpg') }}" />
              @endif
            </div>
            <span class="profile-username">
              <span class="op-7 text-white">Hi,</span>
              <span class="fw-bold name text-white">{{$userName}}</span>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    @if(Auth::check() && Auth::user()->userType == 1)
                    <img
                      src="{{ Auth::user()->image ? asset('public/images/' . Auth::user()->image) : '' }}" 
                      alt="image profile"
                      class="avatar-img rounded"
                    />
                    @else
                    <img class="avatar-img rounded" src="{{ asset('images/dummy-image.jpg') }}" />
                    @endif
                  </div>
                  <div class="u-text">
                    <h4 class="name">{{$userName}}</h4>
                    <p class="text-muted email">{{ $userEmail }}</p>
                    @if(Auth::check() && Auth::user()->userType == 1)
                    <a
                      href="/admin/admin_profile" onclick="loadProfilePage(); return false;"
                      class="btn btn-xs btn-secondary btn-sm "
                      >View Profile</a
                    >
                    @endif
                  </div>
                </div>
              </li>
              <li>                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item logout">Logout</a>
              </li>
            </div>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <!-- End Navbar -->
</div>