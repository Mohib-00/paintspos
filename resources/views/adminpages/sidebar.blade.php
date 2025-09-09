   <!-- Sidebar -->
   <div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
      <!-- Logo Header -->
      <div class="logo-header" data-background-color="dark">
        <a href="/admin" onclick="loadHomePage(); return false;" class="logo" style="color:white">
          <img style="margin-left:-20px"  width=200 height=80
            src="{{asset('jerry.png')}}"
            alt="navbar brand"
            class="navbar-brand"
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
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        <ul class="nav nav-secondary">
          <li class="nav-item active">
            <a
              data-bs-toggle="collapse"
              href="/admin" onclick="loadHomePage(); return false;"
              class="collapsed"
              aria-expanded="false"
            >
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="dashbo">
              <ul class="nav nav-collapse">
                <li>
                  <a href="/admin" onclick="loadHomePage(); return false;">
                    <span class="sub-item">Home</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          
          @if(auth()->user()->user_read != '0')
          <li class="nav-item">
            <a href="/admin/users" onclick="loadUsersPage(); return false;">
              <i class="icon-user"></i>
              <p>Users</p>
            </a>
          </li>
          @endif
          
           @if(auth()->user()->pos_read != '0')
          <li class="nav-item">
            <a href="/admin/POS" onclick="loadposPage(); return false;">
              <i style="color:white" class="fas fa-dolly-flatbed"></i>
              <p>POS</p>
            </a>
          </li>
          {{--<li class="nav-item">
            <a href="/admin/POS_2" onclick="loadpos2Page(); return false;">
              <i style="color:white" class="fas fa-dolly-flatbed"></i>
              <p>POS 2</p>
            </a>
          </li>--}}
          @endif

          @if(auth()->user()->sale_read != '0')
          <li class="nav-item">
            <a href="/admin/sale_list" onclick="loadsalelistPage(); return false;">
              <i style="color:purple" class="fas fa-dolly-flatbed"></i>
              <p>Sale List</p>
            </a>
          </li>
          @endif

            <li class="nav-item">
            <a href="/admin/alerts" onclick="loadalertsPage(); return false;">
              <i class="icon-bell"></i>
              <p>Alerts</p>
            </a>
          </li>
          
           <li class="nav-item">
            <a href="/admin/sale_graph" onclick="loadsalegraphPage(); return false;">
              <i class="icon-graph"></i>
              <p>Sale Graph</p>
            </a>
          </li>

           <li class="nav-item">
            <a href="/admin/alerts_list" onclick="loadalertslistPage(); return false;">
              <i class="icon-bell"></i>
              <p>Alerts List</p>
            </a>
          </li>

            <li class="nav-item active" >
            <a data-bs-toggle="collapse" href="#maps">
              <i style="color:purple" class="far fa-window-maximize"></i>
              <p>PUR</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="maps">
              <ul class="nav nav-collapse">
                @if(auth()->user()->pur_read != '0')
                <li>
                  <a href="/admin/purchase_list" onclick="loadpurchasePage(); return false;">
                    <span class="sub-item">Purchase</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->purchase_return_read != '0')
                <li>
                  <a href="/admin/purchase_return" onclick="loadpurchasereturnPage(); return false;">
                    <span class="sub-item">Purchase Return</span>
                  </a>
                </li>
                @endif
              </ul>
            </div>
          </li>

          <li class="nav-item active" >
            <a data-bs-toggle="collapse" href="#charts">
              <i style="color:purple" class="far fa-chart-bar"></i>
              <p>ACC</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav nav-collapse">
                @if(auth()->user()->acc_read != '0')
                <li>
                  <a href="/admin/chart_of_account" onclick="loadaccountPage(); return false;">
                    <span class="sub-item">Chart Of Account</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->vo_read != '0')
                <li>
                  <a href="/admin/voucher" onclick="loadvoucher(); return false;">
                    <span class="sub-item">Add Voucher</span>
                  </a>
                </li>
                @endif
                
              </ul>
            </div>
          </li>


           <li class="nav-item active">
                <a
                  data-bs-toggle="collapse"
                  href="#dashboard"
                  class="collapsed"
                  aria-expanded="false"
                >
                  <i class="icon-social-stumbleupon"></i>
                  <p>Payroll</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    @if(auth()->user()->paysalary_read != '0')
                  <li>
                  <a href="/admin/salary" onclick="loadsalary(); return false;">
                    <span class="sub-item">Pay Salary</span>
                  </a>
                </li>
                @endif
                  @if(auth()->user()->payedsalary_read != '0')
                 <li>
                  <a href="/admin/payed_salary" onclick="loadpaidsalary(); return false;">
                    <span class="sub-item">Payed Salary</span>
                  </a>
                </li>
                @endif

                 
                  </ul>
                </div>
              </li>

        @if(auth()->user()->reports_read != '0')
          <li class="nav-item active">
                <a data-bs-toggle="collapse" href="#submenu">
                  <i class="fas fa-bars"></i>
                  <p>Reports</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="submenu">
                  <ul class="nav nav-collapse">
                          @if(auth()->user()->salereport_read != '0')
                          <li>
                            <a href="/admin/sale_report" onclick="loadsalereport(); return false;">
                              <span class="sub-item">Sale Report</span>
                            </a>
                          </li>
                          @endif
                            @if(auth()->user()->stockreport_read != '0')
                           <li>
                            <a href="/admin/stock_report" onclick="loadstockeport(); return false;">
                              <span class="sub-item">Stock Report</span>
                            </a>
                          </li>
                          @endif
                            @if(auth()->user()->dcreport_read != '0')
                           <li>
                            <a href="/admin/day_close_report" onclick="loaddayclosereport(); return false;">
                              <span class="sub-item">Day Close Report</span>
                            </a>
                          </li>
                           <li>
                            <a href="/admin/profit_loss_report" onclick="loadprofitlossreport(); return false;">
                              <span class="sub-item">Profit & Loss Report</span>
                            </a>
                          </li>
                           <li>
                            <a href="/admin/trial_balance" onclick="loadtrialbalancereport(); return false;">
                              <span class="sub-item">Trial Balance</span>
                            </a>
                          </li>
                           <li>
                            <a href="/admin/balance_sheet" onclick="loadbalancesheet(); return false;">
                              <span class="sub-item">Balance Sheet</span>
                            </a>
                          </li>
                           <li>
                            <a href="/admin/customer_report" onclick="loadcustomerreport(); return false;">
                              <span class="sub-item">Customer Report</span>
                            </a>
                          </li>
                           <li>
                            <a href="/admin/vendor_report" onclick="loadvendorreport(); return false;">
                              <span class="sub-item">Vendor Report</span>
                            </a>
                          </li>
                          <li>
                            <a href="/admin/purchase_report" onclick="loadpurchaseereport(); return false;">
                              <span class="sub-item">Purchase Report</span>
                            </a>
                          </li>
                          @endif
                            @if(auth()->user()->gl_read != '0')
                           <li>
                            <a href="/admin/general_ledger" onclick="loadledgerreport(); return false;">
                              <span class="sub-item">General Ledger</span>
                            </a>
                          </li>
                          @endif
                  </ul>
                </div>
          </li>
        @endif

{{--<li class="nav-item">
  <a data-bs-toggle="collapse" href="#sidebarVehicleRecord">
    <i class="icon-layers"></i> 
    <p>Vehicle Record</p>
    <span class="caret"></span>
  </a>
  <div class="collapse" id="sidebarVehicleRecord">
    <ul class="nav nav-collapse">
      <li>
        <a href="/admin/vehicle_list" onclick="loadVehiclelistPage(); return false;">
          <span class="sub-item">Vehicle List</span>
        </a>
      </li>
     
      <li>
        <a href="/admin/vehicle_alert" onclick="loadVehiclealertPage(); return false;">
          <span class="sub-item">Alerts</span>
        </a>
      </li>
    </ul>
  </div>
</li>--}}
          

         @if(auth()->user()->vend_read != '0')
          <li class="nav-item">
            <a href="/admin/add_vendor" onclick="loadVendorsPage(); return false;">
              <i class="icon-user"></i>
              <p>Vendors</p>
            </a>
          </li>
          @endif

          <li class="nav-item"> 
            <a data-bs-toggle="collapse" href="#tables">
              <i class="icon-people"></i>
              <p>Customers</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="tables">
              <ul class="nav nav-collapse">
                @if(auth()->user()->custmers_read != '0')
                <li>
                  <a href="/admin/customer_list" onclick="loadCustomerPage(); return false;">
                    <span class="sub-item">Client List</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->area_read != '0')
                <li>
                  <a href="/admin/area" onclick="loadAreaPage(); return false;">
                    <span class="sub-item">Area</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->block_read != '0')
                <li>
                  <a href="/admin/blocked_client_list" onclick="loadblockedclientPage(); return false;">
                    <span class="sub-item">Blocked Client List</span>
                  </a>
                </li>
                @endif
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#base">
              <i class="icon-people"></i>
              <p>Employees</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="base">
              <ul class="nav nav-collapse">
                @if(auth()->user()->empl_read != '0')
                <li>
                  <a href="/admin/employees_list" onclick="loadEmployeesPage(); return false;">
                    <span class="sub-item">Employee List</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->emplleave_read != '0')
                <li>
                  <a href="/admin/employees_leave" onclick="loadEmployeleavePage(); return false;">
                    <span class="sub-item">Employee Leave</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->dgnation_read != '0')
                <li>
                  <a href="/admin/designation" onclick="loaddesignationPage(); return false;">
                    <span class="sub-item">Designation List</span>
                  </a>
                </li>
                @endif
                
                @if(auth()->user()->atndnce_read != '0')
                <li>
                  <a href="/admin/employee_attendance" onclick="loadAttendancePage(); return false;">
                    <span class="sub-item">Employee Attendance</span>
                  </a>
                </li>
                @endif

                 @if(auth()->user()->atndncereport_read != '0')
                <li>
                  <a href="/admin/employee_attendance_report" onclick="loadAttendanceReportPage(); return false;">
                    <span class="sub-item">Attendance Report</span>
                  </a>
                </li>
                @endif
                 <li>
                  <a href="/admin/fine" onclick="loadfinePage(); return false;">
                    <span class="sub-item">Fine & Penalty</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#sidebarLayouts">
              <i class="icon-diamond"></i>
              <p>Products</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="sidebarLayouts">
              <ul class="nav nav-collapse">
                 @if(auth()->user()->cmppny_read != '0')
                <li>
                  <a href="/admin/company_list" onclick="loadCompanyPage(); return false;">
                    <span class="sub-item">Company List</span>
                  </a>
                </li>
                @endif

                @if(auth()->user()->ctgry_read != '0')
                <li>
                  <a href="/admin/category_list" onclick="loadCategoryPage(); return false;">
                    <span class="sub-item">Category List</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->subctgry_read != '0')
                <li>
                  <a href="/admin/subcategory_list" onclick="loadsubPage(); return false;">
                    <span class="sub-item">Sub Category List</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->product_read != '0')
                <li>
                  <a href="/admin/products_list" onclick="loadProductPage(); return false;">
                    <span class="sub-item">Product List</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->productprice_read != '0')
                <li>
                  
                  <a href="/admin/product_price_list" onclick="loadpricePage(); return false;">
                    <span class="sub-item">Product Price List</span>
                  </a>
                </li>
                @endif
                @if(auth()->user()->productimport_read != '0')
                <li>
                  <a href="/admin/product_import" onclick="loadimportPage(); return false;">
                    <span class="sub-item">Product Import</span>
                  </a>
                </li>
                @endif
                <li>
                  <a href="/admin/deal_list" onclick="loaddealPage(); return false;">
                    <span class="sub-item">Deal List</span>
                  </a>
                </li>
                <li>
                  <a href="/admin/format" onclick="loadformatPage(); return false;">
                    <span class="sub-item">Format Excel File</span>
                  </a>
                </li>
               
              </ul>
            </div>
          </li>


          {{--<li class="nav-item">
  <a data-bs-toggle="collapse" href="#sidebarInventory">
    <i class="icon-diamond"></i>
    <p>ManuFacture</p>
    <span class="caret"></span>
  </a>
  <div class="collapse" id="sidebarInventory">
    <ul class="nav nav-collapse">
      <li>
        <a href="/admin/manufacture_company_list" onclick="loadmanufactureCompanyPage(); return false;">
          <span class="sub-item">Company List</span>
        </a>
      </li>

       <li>
                  <a href="/admin/manufacture_category_list" onclick="loadmanufactureCategoryPage(); return false;">
                    <span class="sub-item">Category List</span>
                  </a>
                </li>


                <li>
                  <a href="/admin/raw_material_list" onclick="loadrawmaterialPage(); return false;">
                    <span class="sub-item">Raw Material List</span>
                  </a>
                </li>

                 <li>
                  <a href="/admin/material_purchase" onclick="loadaddmaterialpurchasePage(); return false;">
                    <span class="sub-item">Purchase</span>
                  </a>
                </li>
                
                

     
    </ul>
  </div>
</li>--}}

          
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#forms">
              <i class="icon-settings"></i>
              <p>Backup/Restore</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="forms">
              <ul class="nav nav-collapse">
                <li>
                  <a href="/admin/backup_reset" onclick="loadbackupPage(); return false;">
                    <span class="sub-item">Backup/Reset</span>
                  </a>
                </li>
                <li>
                  <a href="">
                    <span class="sub-item">Restore</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

       
        </ul>
      </div>
    </div>
  </div>
  <!-- End Sidebar -->