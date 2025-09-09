<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>POS Sale</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="icon" href="{{asset('lite/assets/img/kaiadmin/favicon.ico')}}" type="image/x-icon"/>

  <style>
    body, html {
      height: 100%;
      margin: 0;
      background: #f0f4f8;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #33475b;
    }
    .wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .main-panel {
      flex: 1;
      padding-bottom: 2rem;
    }
    .header, .footer {
      background-color: #2c3e50;
      color: #f0f4f8;
      padding: 1.25rem 1rem;
      text-align: center;
      font-weight: 600;
      letter-spacing: 1.1px;
      box-shadow: 0 2px 8px rgb(44 62 80 / 0.4);
    }
    .footer {
      font-size: 14px;
    }
    .card-header {
      font-weight: 600;
      font-size: 1.1rem;
      background: #2c3e50 !important;
      color: #f0f4f8 !important;
      border-bottom: none;
      box-shadow: inset 0 -2px 0 #1a2838;
    }
    .product-item {
      border-radius: 12px;
      background-color: #e7f1ff; 
      border: 1.5px solid #a8c6ff;
      color: #1e3a8a;
      transition:
        box-shadow 0.3s ease,
        transform 0.3s ease;
      cursor: pointer;
      display: flex;
      flex-direction: column;
      margin: 0;
      height: 100%;
    }
    .product-item:hover {
      background-color: #d0e4ff;
      border-color: #5b8def;
      box-shadow: 0 8px 20px rgba(91, 141, 239, 0.5);
      transform: translateY(-6px);
      color: #1e3a8a;
    }
    .product-item > .card {
      border: none !important;
      box-shadow: none !important;
      height: 100%;
      display: flex;
      flex-direction: column;
      background: transparent !important;
      color: inherit;
    }
    .product-item:hover > .card {
      box-shadow: none !important;
      transform: none;
      color: inherit;
    }
    .product-item .card-img-top {
      width: 130px;
      height: 130px;
      object-fit: cover;
      margin: 1.25rem auto 0;
      border-radius: 14px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: box-shadow 0.3s ease, filter 0.3s ease;
    }
    .product-item:hover .card-img-top {
      box-shadow: 0 8px 25px rgba(91, 141, 239, 0.7);
      filter: brightness(1.05);
    }
    .product-item .card-body {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 1.25rem 1rem 1.5rem;
      transition: color 0.3s ease;
    }
    .product-item .card-title {
      font-weight: 700;
      font-size: 1.1rem;
      margin-bottom: 0.25rem;
      color: #1e3a8a;
    }
    .product-item .card-text {
      font-weight: 600;
      font-size: 0.95rem;
      color: #3b82f6;
      margin-bottom: 0.75rem;
    }

    .input-group .btn {
      min-width: 32px;
      padding: 0 8px;
      border-radius: 0.25rem;
      border-color: #1e88e5;
      background-color: #e3f2fd;
      color: #1e88e5;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .input-group .btn:hover {
      background-color: #1e88e5;
      color: white;
    }
    .input-group input {
      max-width: 50px;
      border-color: #90caf9;
      text-align: center;
      font-weight: 600;
      color: #33475b;
      background-color: #f0f4f8;
    }
    .btn-success {
      min-width: 55px;
      font-weight: 600;
      background-color: #43a047;
      border-color: #43a047;
      transition: background-color 0.3s ease;
      color: white;
    }
    .btn-success:hover {
      background-color: #388e3c;
      border-color: #2e7d32;
      color: white;
    }
    table.table thead {
      background-color: #e3f2fd;
      font-weight: 600;
      color: #1e88e5;
    }
    #productList::-webkit-scrollbar {
      width: 7px;
    }
    #productList::-webkit-scrollbar-thumb {
      background-color: #1e88e5aa;
      border-radius: 10px;
    }


    #categoryList {
    scrollbar-width: thin;
    scrollbar-color: #888 #f1f1f1;
  }

  #categoryList::-webkit-scrollbar {
    height: 8px;
  }

  #categoryList::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }

  #categoryList::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 10px;
    border: 2px solid #f1f1f1;
  }

  #categoryList::-webkit-scrollbar-thumb:hover {
    background: #555;
  }


  @media (min-width: 942px) {
  .header {
    height: 50px !important;
  }
  .possale{
    margin-top:-10px
  }
  .roundedpillll{
    margin-top:-10px
  }
}

.classyinputsstyle{
  height:2px;
}
.classyinputsstyleselect{
  height:30px;
}

  </style>
  
</head>
<body>
  <div class="wrapper">
   <header class="header d-flex justify-content-between align-items-center p-3 flex-wrap gap-2" style="background-color: #1a2838;">
  <h3 class="mb-0 possale" style="font-size:20px">Point of Sale System</h3>

  <!-- Action Buttons -->
  <div class="d-flex flex-wrap gap-2">
    <a style="display: flex;justify-content:center;align-items:center" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-white roundedpillll" onclick="loadHomePage(); return false;">
      <i class="fas fa-home me-1 text-white"></i> Dashboard
    </a>

    @if(auth()->user()->sale_read != '0')
    <a style="display: flex;justify-content:center;align-items:center" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-white roundedpillll" href="/admin/sale_list" onclick="loadsalelistPage(); return false;">
      <i class="fas fa-chart-line me-1 text-white"></i> Sale List
    </a>
    @endif

     @if(auth()->user()->productprice_read != '0')
    <a style="display: flex;justify-content:center;align-items:center" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-white roundedpillll" href="/admin/product_price_list" onclick="loadpricePage(); return false;">
      <i class="fas fa-cogs me-1 text-white"></i> Products Price List
    </a>
    @endif

    @if(auth()->user()->stockreport_read != '0')
     <a style="display: flex;justify-content:center;align-items:center" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-white roundedpillll"  href="/admin/stock_report" onclick="loadstockeport(); return false;">
      <i class="fas fa-cogs me-1 text-white"></i> Stock Report
    </a>
    @endif

    <a style="display: flex;justify-content:center;align-items:center"
   class="btn btn-sm btn-outline-primary rounded-pill px-3 text-white roundedpillll"
   href="#"
   data-bs-toggle="modal"
   data-bs-target="#addProductModal">
   <i class="fas fa-cogs me-1 text-white"></i> Add Product
</a>

<!-- Product Add Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl"> <!-- modal-xl for wide form -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductModalLabel" style="color:black">Add Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- Your Form -->
        <form id="productform">
          <input type="hidden" id="productforminput_add" value=""/>
          <div class="row mt-3">

            <!-- Brand -->
            <div class="col-4">
              <div class="form-group">
                <label for="brand_name" style="color:black">Brand Name</label>
                <select class="form-select form-control" name="brand_name">
                  <option>Choose One</option>
                  @foreach ($brands as $brand )
                    <option>{{ $brand->designation_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Category -->
            <div class="col-4">
              <div class="form-group">
                <label for="category_name" style="color:black">Category Name</label>
                <select class="form-select form-control" name="category_name">
                  <option>Choose One</option>
                  @foreach ($categorys as $category )
                    <option>{{ $category->category_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Sub Category -->
            <div class="col-4">
              <div class="form-group">
                <label for="subcategory_name" style="color:black">Sub Category Name</label>
                <select class="form-select form-control" name="subcategory_name">
                  <option>Choose One</option>
                  @foreach ($subs as $sub )
                    <option>{{ $sub->category_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Item Name -->
            <div class="col-4">
              <div class="form-group">
                <label for="item_name" style="color:black">Item Name</label>
                <input type="text" id="item_name" name="item_name" class="form-control">
              </div>
            </div>

            <!-- Shade -->
            <div class="col-4">
              <div class="form-group">
                <label for="shade" style="color:black">Shade</label>
                <input type="text" id="shade" name="shade" class="form-control">
              </div>
            </div>

            <!-- Code -->
            <div class="col-4">
              <div class="form-group">
                <label for="code" style="color:black">Code</label>
                <input type="text" id="code" name="code" class="form-control">
              </div>
            </div>

            <!-- Barcode -->
            <div class="col-4">
              <div class="form-group">
                <label for="barcode" style="color:black">Barcode</label>
                <input type="number" id="barcode" name="barcode" class="form-control">
              </div>
            </div>

            <!-- Purchase Rate -->
            <div class="col-4">
              <div class="form-group">
                <label for="purchase_rate" style="color:black">Purchase Rate</label>
                <input type="number" id="purchase_rate" name="purchase_rate" class="form-control" value="0">
              </div>
            </div>

            <!-- Retail Rate -->
            <div class="col-4">
              <div class="form-group">
                <label for="retail_rate" style="color:black">Retail Rate</label>
                <input type="number" id="retail_rate" name="retail_rate" class="form-control" value="0">
              </div>
            </div>

            <!-- Image -->
            <div class="col-8">
              <div class="form-group">
                <label for="image" style="color:black">Image</label>
                <input class="form-control" type="file" id="image" name="image">
                <span id="nameError" class="text-danger"></span>
              </div>
            </div>

          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button id="productadd" type="submit" class="btn btn-primary" form="productform">Submit</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



    <a class="btn btn-sm btn-outline-primary rounded-pill text-white d-flex justify-content-center align-items-center roundedpillll"
   id="showCalculator" data-bs-toggle="modal" data-bs-target="#calculatorModal"
   style="width: 40px; height: 40px;" title="Open Calculator">
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
       class="bi bi-calculator" viewBox="0 0 16 16">
    <path d="M4 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H4zm0-1h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z"/>
    <path d="M5 3h6v2H5V3zM5 6h2v2H5V6zm3 0h2v2H8V6zm-3 3h2v2H5V9zm3 0h2v2H8V9zm-3 3h2v2H5v-2zm3 0h2v2H8v-2z"/>
  </svg>
</a>


    <div class="modal fade" id="calculatorModal" tabindex="-1" aria-labelledby="calculatorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="calculatorModalLabel">Calculator</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Calculator UI -->
        <input type="text" id="calcDisplay" class="form-control mb-2 text-end" readonly>

        <div class="row g-1">
          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('7')">7</button></div>
          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('8')">8</button></div>
          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('9')">9</button></div>
          <div class="col-3"><button class="btn btn-warning w-100" onclick="appendValue('/')">÷</button></div>

          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('4')">4</button></div>
          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('5')">5</button></div>
          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('6')">6</button></div>
          <div class="col-3"><button class="btn btn-warning w-100" onclick="appendValue('*')">×</button></div>

          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('1')">1</button></div>
          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('2')">2</button></div>
          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('3')">3</button></div>
          <div class="col-3"><button class="btn btn-warning w-100" onclick="appendValue('-')">−</button></div>

          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('0')">0</button></div>
          <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendValue('.')">.</button></div>
          <div class="col-3"><button class="btn btn-success w-100" onclick="calculate()">=</button></div>
          <div class="col-3"><button class="btn btn-warning w-100" onclick="appendValue('+')">+</button></div>

          <div class="col-6 mt-2"><button class="btn btn-danger w-100" onclick="clearDisplay()">C</button></div>
          <div class="col-6 mt-2"><button class="btn btn-light w-100" onclick="deleteLast()">⌫</button></div>
        </div>
      </div>
    </div>
  </div>
</div>


    <a style="display: flex;justify-content:center;align-items:center" class="btn btn-sm btn-outline-primary rounded-pill px-3 text-white logout roundedpillll">
      <i class="fas fa-sign-out-alt me-1 text-white logout"></i> Logout
    </a>
  </div>
</header>

    <main class="main-panel container-fluid py-4" style="margin-top:-1.5%">
      <form id="saleForm">

         <div class="mb-3 d-none">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="productSearchDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          Select Product
        </button>
        <input type="hidden" id="selectedProductId" name="selectedProductId" />
      </div>
        <!-- Filters Row -->
        <div class="row g-3 mb-4">
             <div class="col-md-2">
      <label for="customerSelect">Choose Employee</label>
      <select class="form-select form-select-sm" id="customerSelect" name="employee" style="padding:10px;border-radius:5px">
        <option value="1">All</option>
        @foreach ($employees as $employee)
        <option value="{{ $employee->employee_name }}">{{ $employee->employee_name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label for="smallSelect">Choose a Customer</label>
      <select class="form-select form-select-sm" id="smallSelect" name="customer_id" style="padding:10px;border-radius:5px">
        <option value="1">Blank</option>
        @foreach ($customers as $customer)
        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
        @endforeach
      </select>
    </div>

    @php
      $today = \Carbon\Carbon::today()->toDateString();
      $yesterday = \Carbon\Carbon::yesterday()->toDateString();
    @endphp

    <div class="col-md-2">
      <label for="dateInput">Date</label>
      <input class="form-control form-control-sm" type="date" name="created_at" id="dateInput" style="padding:10px;border-radius:5px"
             @if(auth()->user()->pos_pastdate == '0')
               min="{{ $yesterday }}" max="{{ $today }}"
             @endif />
    </div>

    <div class="col-md-2">
      <label for="refInput">Ref#</label>
      <input style="padding:10px;border-radius:5px" class="form-control form-control-sm" type="text" name="ref" id="refInput" />
    </div>
  </div>

        <div class="row g-3" style="margin-top:-2.3%">
       
           <div class="col-lg-4">

               <div class="col-lg-12">
<div class="card shadow-sm rounded mb-3" style="height: 120px">
  <div class="card-header bg-light d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Categories</h5>
    <input type="text" class="form-control form-control-sm w-auto" placeholder="Search Category..." onkeyup="filterCategoryItems()" />
  </div>

  <div class="card-body py-2 px-1">
    <div class="d-flex flex-row flex-nowrap gap-3"
         id="categoryList"
         style="overflow-x: auto; -ms-overflow-style: none; scrollbar-width: none; height: 100px;">
         
      <style>
        #categoryList::-webkit-scrollbar {
          display: none;
        }
      </style>

      <div class="text-center cursor-pointer category-item"
           onclick="showAllProducts()"
           style="min-width: 100px; max-width: 100px; border: 1px solid #ddd; border-radius: 10px; padding: 5px; background-color: #e6f2ff; height:60px">
        <img src="{{ asset('dummy.jpg') }}" class="img-fluid mb-2"
             style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;">
        <div style="font-size: 10px;">All</div>
      </div>

      @foreach ($categories as $category)
    <div class="text-center cursor-pointer category-item"
         onclick="filterProductsByCategory({{ json_encode($category->category_name) }})"
         style="min-width: 100px; max-width: 100px; border: 1px solid #ddd; border-radius: 10px; padding: 5px; background-color: #e6f2ff; height:60px">
      <img src="{{ $category->image ? asset('public/images/' . $category->image) : asset('dummy.jpg') }}"
           class="img-fluid mb-2"
           style="height: 30px; width: 30px; object-fit: cover; border-radius: 50%;">
      <div style="font-size: 10px;">{{ $category->category_name }}</div>
    </div>
@endforeach


    </div>
  </div>
</div>

</div>

          <div class="card shadow-sm rounded" style="margin-top:-10px">
            <div class="card-header d-flex justify-content-between align-items-center" style="padding:5px">
              <h5 style="white-space: nowrap" class="mb-0">Available Products</h5>
              <input style="padding:5px"
                type="text"
                class="form-control form-control-sm ms-3"
                style="max-width: 200px;"
                id="productSearchInput"
                placeholder="Search Products or Deals"
                onkeyup="filterProductList()"
                autocomplete="off"
              />
            </div>
            <div class="card-body p-2" style="max-height: 59vh; overflow-y: auto;" id="productList">
            <div class="row g-3" >
  @foreach ($products as $product)
  <div class="col-6 col-md-3 col-lg-4 product-item"
       data-category="{{ $product->category_name }}"
       onclick="selectProduct('{{ $product->id }}', '{{ $product->item_name }}'); return false;">
    <div class="card shadow-sm border rounded cursor-pointer h-100">
      <img
        src="{{ $product->image ? asset('public/images/' . $product->image) : asset('dummy.jpg') }}"
        class="card-img-top mx-auto"
        alt="{{ $product->item_name }}"
        style="height: 100px; width: 100px; object-fit: cover; margin-top: 10px;"
      />
      <div class="card-body py-2 px-1 d-flex flex-column align-items-center text-center">
        <h6 class="card-title mb-1" style="font-size: 14px;">{{ $product->item_name }}</h6>
        <h6 class="card-title mb-1 code" style="font-size: 14px;display:none">{{ $product->code }}</h6>
        <h6 class="card-title mb-1 brand" style="font-size: 14px;display:none">{{ $product->brand_name }}</h6>
        <h6 class="card-title mb-1 shade" style="font-size: 14px;">{{ $product->shade }}</h6>
        <p class="card-text mb-1" style="font-size: 13px;">Rs: {{ $product->single_retail_rate }}</p>
      </div>
    </div>
  </div>
@endforeach


  @foreach ($deals as $deal)
    <div class="col-6 col-md-3 col-lg-4 product-item"
         onclick="selectProduct('{{ $deal->id }}', '{{ $deal->deal_name }}'); return false;">
      <div class="card shadow-sm border rounded cursor-pointer h-100">
        <img
          src="{{ asset('dummy.jpg') }}"
          class="card-img-top mx-auto"
          alt="{{ $deal->deal_name }}"
          style="height: 100px; width: 100px; object-fit: cover; margin-top: 10px;"
        />
        <div class="card-body py-2 px-1 d-flex flex-column align-items-center text-center">
          <h6 class="card-title mb-1" style="font-size: 14px;">{{ $deal->deal_name }}</h6>
          <p class="card-text mb-1" style="font-size: 13px;">Rs: {{ $deal->deal_price ?? 'N/A' }}</p>
        </div>
      </div>
    </div>
  @endforeach
</div>

            </div>
          </div>
          </div>

          <!-- Center Panel: Cart Items -->
          <div class="col-lg-6">
            <div class="card shadow-sm rounded" >
              <div class="card-header">Selected Products</div>
              <div class="card-body p-2">
                <div class="table-responsive">
                  <table class="table table-bordered align-middle mb-0" id="productTable">
                    <thead class="table-light">
                      <tr>
                        <th style="width: 130px;">Name</th>
                                  <th style="width: 80px;">Quantity</th>
                                  <th style="display: none" scope="col">Purchase Rate</th>
                                  <th style="width:100px;white-space:nowrap">Retail Rate</th>
                                  <th style="width: 80px;">Sub-Total</th>
                                  <th style="width: 80px;">Delete</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                                <tr>
                                  <td colspan="3" class="text-end fw-bold">Total Items</td>
                                  <td class=" fw-bold" >
                                    <input type="number" id="totalItems"  name="total_items" class="form-control form-control-sm text-end fw-bold" readonly>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="3" class="text-end fw-bold">Total</td>
                                  <td class=" fw-bold" >
                                    <input type="number" id="totalAmount" name="total" class="form-control form-control-sm text-end fw-bold" readonly>

                                  </td>
                                </tr>
                              </tfoot>
                  </table>
                </div>
              </div>
            </div>


            <div class="card shadow-sm rounded mt-5 dealitemsection" style="display: none;">
              <div class="card-header">Deal Items</div>
              <div class="card-body p-2">
                <div class="table-responsive">
                  <table class="table table-bordered align-middle mb-0" id="dealitemTable">
                    <thead class="table-light">
                     <tr>
                                  <th style="width: 130px;">Deal Name</th>
                                  <th style="width: 80px;">Name</th>
                                  <th style="width: 80px;">Quantity</th>
                                  <th style="width: 80px;display: none" scope="col">P.Rate</th>
                                  <th style="width: 80px;">R.Rate</th>
                                </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    
                  </table>
                </div>
              </div>
            </div>


           


          </div>

          <div class="col-lg-2 ms-auto">
      <div class="card shadow-sm rounded" style="height:600px">
  <h5 class="fw-bold mb-3 card-header" style="font-size: 16px; padding: 8px 12px;">Sale Summary</h5>

  <div class="card-body" style="padding: 10px; overflow-y: auto;">
    <div class="mb-2">
      <label for="saleTypeSelect" class="form-label fw-semibold" style="font-size: 13px;">Sale Type</label>
      <select class="form-select" name="sale_type" id="saleTypeSelect" style="font-size: 13px; padding: 4px 8px;">
        <option value="1">Cash</option>
        <option value="2">Credit</option>
      </select>
    </div>

    <div class="mb-2">
      <label for="paymentTypeSelect" class="form-label fw-semibold" style="font-size: 13px;">Payment Type</label>
      <select class="form-select" name="payment_type" id="paymentTypeSelect" style="font-size: 13px; padding: 4px 8px;">
        <option value="1">Cash</option>
        <option value="2">Bank</option>
      </select>
    </div>

    <hr style="margin: 10px 0;" />

    <div class="mb-2">
      <label for="discount" class="form-label fw-semibold" style="font-size: 13px;">Discount</label>
      <input type="text" class="form-control" name="discount" id="discount" value="0" style="font-size: 13px; padding: 4px 8px;" />
    </div>

    <div class="mb-2">
      <label for="amountafterdiscount" class="form-label fw-semibold" style="font-size: 13px;">Amount After Discount</label>
      <input type="number" class="form-control" name="amount_after_discount" id="amountafterdiscount" value="0" readonly style="font-size: 13px; padding: 4px 8px;" />
    </div>

    <div class="mb-2">
      <label for="fixeddiscount" class="form-label fw-semibold" style="font-size: 13px;">Fixed Discount</label>
      <input type="number" class="form-control" name="fixed_discount" id="fixeddiscount" value="0" readonly style="font-size: 13px; padding: 4px 8px;" />
    </div>

    <div class="mb-2">
      <label for="amountafterfixdiscount" class="form-label fw-semibold" style="font-size: 13px;">Amount After Fixed Discount</label>
      <input type="number" class="form-control" name="amount_after_fix_discount" id="amountafterfixdiscount" value="0" readonly style="font-size: 13px; padding: 4px 8px;" />
    </div>

    <div class="mb-2">
      <label for="total" class="form-label fw-semibold" style="font-size: 13px;">Total Rs:</label>
      <input type="number" class="form-control" name="subtotal" id="total" value="0" readonly style="font-size: 13px; padding: 4px 8px;" />
    </div>

    <div class="d-grid mt-2">
      <button class="btn btn-primary" type="submit" style="font-size: 13px; padding: 6px;">Submit</button>
    </div>
  </div>
</div>

    </div>
                      <input type="text" id="barcodeInput" placeholder="Scan barcode" style="position:absolute; left:-9999px;" onkeydown="handleBarcodeScan(event)">

        </div>
      </form>
    </main>

    <footer class="footer">
      &copy; {{ date('Y') }} All rights reserved.
    </footer>
  </div>

  @include('adminpages.js')
  @include('adminpages.ajax')

  <script>

    function createLoader() {
    const loader = document.createElement('div');
    loader.id = 'loader';
    loader.style.position = 'fixed';
    loader.style.top = '0';
    loader.style.left = '0';
    loader.style.width = '100%';
    loader.style.height = '100%';
    loader.style.backgroundColor = 'rgba(128, 128, 128, 0.6)';
    loader.style.display = 'flex';
    loader.style.alignItems = 'center';
    loader.style.justifyContent = 'center';
    loader.style.zIndex = '9999';

    const spinner = document.createElement('div');
    spinner.style.border = '6px solid #f3f3f3';
    spinner.style.borderTop = '6px solid #3498db';
    spinner.style.borderRadius = '50%';
    spinner.style.width = '50px';
    spinner.style.height = '50px';
    spinner.style.animation = 'spin 0.8s linear infinite';

    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);

    loader.appendChild(spinner);
    document.body.appendChild(loader);
}

function removeLoader() {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.remove();
    }
}


    $('#productform').on('submit', function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    createLoader();
    $.ajax({
        url: "{{ route('product.store') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            removeLoader();
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Added!',
                    text: response.message || 'Added successfully.',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    $('#productform')[0].reset();
                    $('.custom-modal.product').fadeOut();
                    loadposPage(); 
                });
            }
        },
        error: function (xhr) {
            removeLoader();
            let errors = xhr.responseJSON.errors;
            if (errors) {
                let errorMessages = Object.values(errors)
                    .map(err => err.join('\n'))
                    .join('\n');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessages,
                    confirmButtonText: 'Ok'
                });
            }
        }
    });
});

  </script>


  <script>
  const calculator = document.getElementById("calculator");
  const display = document.getElementById("calcDisplay");

  document.getElementById("showCalculator").addEventListener("click", () => {
    calculator.style.display = calculator.style.display === "none" ? "block" : "none";
  });

  function appendValue(val) {
    display.value += val;
  }

  function clearDisplay() {
    display.value = "";
  }

  function deleteLast() {
    display.value = display.value.slice(0, -1);
  }

  function calculate() {
    try {
      display.value = eval(display.value);
    } catch {
      display.value = "Error";
    }
  }
</script>


<script>
  function filterProductsByCategory(categoryName) {
    const allProducts = document.querySelectorAll('.product-item');

    allProducts.forEach(product => {
      const productCategory = product.getAttribute('data-category')?.trim();
      if (productCategory === categoryName) {
        product.style.display = 'block';
      } else {
        product.style.display = 'none';
      }
    });
  }

  function showAllProducts() {
    document.querySelectorAll('.product-item').forEach(product => {
      product.style.display = 'block';
    });
  }
</script>



  <script>
  function filterCategoryItems() {
    const input = event.target.value.toLowerCase();
    const categories = document.querySelectorAll('#categoryList .category-item');

    categories.forEach(cat => {
      const name = cat.textContent.toLowerCase();
      cat.style.display = name.includes(input) ? 'inline-block' : 'none';
    });
  }
</script>


 <script>
  function filterProductList() {
    const search = document.getElementById('productSearchInput').value.toLowerCase();

    document.querySelectorAll('#productList .product-item').forEach(item => {
      const code = item.querySelector('.code')?.textContent.toLowerCase() || '';
      const brand = item.querySelector('.brand')?.textContent.toLowerCase() || '';
      const shade = item.querySelector('.shade')?.textContent.toLowerCase() || '';
      const itemName = item.querySelector('.item_name')?.textContent.toLowerCase() || ''; 

      if (
        code.includes(search) ||
        brand.includes(search) ||
        shade.includes(search) ||
        itemName.includes(search)
      ) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  }
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

  dealGrandTotal += itemTotal;

  const existingRow = $tbody.find(`input[name="deal_product_name[]"][value="${item.products}"]`).closest("tr");

  if (existingRow.length) {
    const qtyInput = existingRow.find('.deal-item-quantity-input');
    const oldQty = parseFloat(qtyInput.val()) || 0;
    const newQty = oldQty + baseQuantity;
    qtyInput.val(newQty);
    qtyInput.attr("data-base-quantity", newQty);

    const newItemTotal = purchaseRate * newQty;
    dealGrandTotal += newItemTotal - itemTotal; 
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

$tbody.append(`
  <tr class="deal-total-row">
    <td colspan="2" style="text-align: right;"><strong>Deal Total:</strong></td>
    <td><input type="number" id="deal-total-value-${dealId}" class="form-control form-control-sm" readonly value="${dealGrandTotal.toFixed(2)}"></td>
  </tr>
`);
updateTotals();


     $(document).off('input.dealQty').on('input.dealQty', '.deal-quantity-input', function () {
  const $this = $(this);
  const newDealQty = parseInt($this.val()) || 1;
  const $row = $this.closest('tr');
  const currentDealName = $(this).closest('tr').find('.item-name-input').val() || '';
const dealIdScoped = currentDealName.replace(/\s+/g, '-');


  let hasInsufficientStock = false;
  let insufficientProductName = '';

  $('#dealitemTable tbody tr').each(function () {
    const $dealItemRow = $(this);
    const dealName = $dealItemRow.find('input[name="deal_name[]"]').val();

    if (dealName === currentDealName) {
      const $qtyInput = $dealItemRow.find('input[name="deal_product_quantity[]"]');
      const baseQty = parseInt($qtyInput.attr('data-base-quantity')) || 0;
      const updatedQty = baseQty * newDealQty;

      const productStock = parseInt($dealItemRow.attr('data-stock')) || 0;

      if (updatedQty > productStock) {
        hasInsufficientStock = true;
        insufficientProductName = $dealItemRow.find('input[name="deal_product_name[]"]').val();
        return false; 
      }

      $qtyInput.val(updatedQty);
    }
  });

  /*if (hasInsufficientStock) {
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

      
      }
    },
    error: function (xhr) {
      let errMsg = xhr.responseJSON?.message || "An error occurred";
      Swal.fire({ icon: "error", title: "Error", text: errMsg, confirmButtonText: "OK" });
    }
  });
}

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
        <td><button class="btn btn-icon btn-round btn-danger btn-sm delete-row"><i class="bi bi-x-lg"></i></button></td>
      </tr>
    `);

    updateTotals();
  }
}


$(document).on('click', '.delete-row', function () {
 
    const productRow = $(this).closest('tr');
    const dealName = productRow.find('input[name="product_name[]"]').val();
     

    productRow.remove();

    $('#dealitemTable tbody tr').each(function () {
        const row = $(this);
        const dealInput = row.find('input[name="deal_name[]"]');

        if (dealInput.length && dealInput.val() === dealName) {
            row.remove();
        }
    });
});




    $(document).on("keydown", function (e) {
      if (e.ctrlKey && e.key === "s") {
        e.preventDefault();
        handleSaleFormSubmit();
      }
    });

    $(document).ready(function () {
  $("#saleForm").on("submit", function (e) {
    e.preventDefault();
    handleSaleFormSubmit();
  });
});

    function handleSaleFormSubmit() {
  const items = [];
  $("#productTable tbody tr").each(function () {
    const row = $(this);
    const name = row.find(".item-name-input").val();
    const isDeal = row.find(".deal-quantity-input").length > 0;

    if (name && name.trim() !== "") {
      items.push({
        product_name: name,
        product_quantity: !isDeal ? parseInt(row.find(".quantity-input").val()) || 1 : undefined,
        deal_quantity: isDeal ? parseInt(row.find(".deal-quantity-input").val()) || 1 : undefined,
        purchase_rate: parseFloat(row.find(".purchase-rate-input").val()) || 0,
        product_rate: parseFloat(row.find(".rate-input").val()) || 0,
        product_subtotal: parseFloat(row.find(".subtotal-input").val()) || 0,
      });
    }
  });



     const customerSelect = document.getElementById("smallSelect");
  const customerId = customerSelect?.value || "";
  const customerName = customerSelect?.options[customerSelect.selectedIndex]?.text || "";

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
    dealProductPurchaseRates.push(parseFloat($(this).val()) || 0);
  });
  $("input[name='deal_product_retail_rate[]']").each(function () {
    dealProductRetailRates.push(parseFloat($(this).val()) || 0);
  });
  $("input[name='deal_name[]']").each(function () {
    const name = $(this).val();
    if (name.trim() !== '') {
      dealnames.push(name);
    }
  });

  const getFieldValue = (selector) => document.querySelector(`[name="${selector}"]`)?.value || "";

  const formData = {
    employee: getFieldValue("employee"),
    customer_id: customerId,
    customer_name: customerName,
    created_at: getFieldValue("created_at"),
    ref: getFieldValue("ref"),
    sale_type: getFieldValue("sale_type"),
    payment_type: getFieldValue("payment_type"),
    discount: getFieldValue("discount"),
    total_items: getFieldValue("total_items"),
    total: getFieldValue("total"),
    amount_after_discount: getFieldValue("amount_after_discount"),
    fixed_discount: getFieldValue("fixed_discount"),
    amount_after_fix_discount: getFieldValue("amount_after_fix_discount"),
    subtotal: getFieldValue("subtotal"),
    items: items,
    deal_product_name: dealProductNames,
    deal_product_quantity: dealProductQuantities,
    deal_product_purchase_rate: dealProductPurchaseRates,
    deal_product_retail_rate: dealProductRetailRates,
    deal_name: dealnames,
  };

 Swal.fire({
  title: 'Submitting Sale...',
  text: 'Please wait while we process your request.',
  allowOutsideClick: false,
  didOpen: () => {
    Swal.showLoading();
  }
});

fetch("/sales", {
  method: "POST",
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
      text: "Sale submitted successfully!",
      icon: "success",
      confirmButtonText: "OK",
    }).then((result) => {
      if (result.isConfirmed) {
        loadsalelistPage();
        document.getElementById("saleForm").reset();
        $("#productTable tbody").empty();
        $("#dealitemTable tbody").empty();
        updateTotals();
      }
    });
  })
  .catch((error) => {
    console.error("Error:", error);
    Swal.fire({
      title: "Error!",
      text: "There was an error submitting the sale.",
      icon: "error",
      confirmButtonText: "OK",
    });
  });
    }
    $(document).on("click", ".delete-row", function () {
      $(this).closest("tr").remove();
      updateTotals();
    });

    $(document).on("input", ".quantity-input, .rate-input", function () {
      const $row = $(this).closest("tr");
      let quantity = parseInt($row.find(".quantity-input").val()) || 1;
      let rate = parseFloat($row.find(".rate-input").val()) || 0;
      let stock = parseInt($row.find(".quantity-input").attr("data-stock")) || 0;

      /*if (quantity > stock) {
        Swal.fire({
          icon: "warning",
          title: "Stock Limit Reached",
          text: `Available stock is ${stock}. You cannot exceed this.`,
        });
        quantity = stock;
        $row.find(".quantity-input").val(stock);
      }*/

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
