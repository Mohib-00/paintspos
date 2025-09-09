<?php
use App\Http\Controllers\AboutServiceController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerReportController;
use App\Http\Controllers\DayCloseController;
use App\Http\Controllers\DealListController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\Emplyeescontroller;
use App\Http\Controllers\FineController;
use App\Http\Controllers\grnController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\ManufactureCategoryController;
use App\Http\Controllers\ManufactureCompanyController;
use App\Http\Controllers\ManufactureProductController;
use App\Http\Controllers\MaterialPurchaseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReportController;
use App\Http\Controllers\PurchaseRetutrnController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\saleController;
use App\Http\Controllers\SaleGraphController;
use App\Http\Controllers\SaleReportController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockReportContoller;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\UserAuthcontroller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\vehicleRecordController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorReportController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

//User Page    
//Route::get('/', [UserAuthController::class, 'home']);
//to open register page
//Route::get("register", [RegisterController::class, "register"]);
//to open login page
Route::get("/", [RegisterController::class, "login"]);
//register
Route::post("registerrr",[UserAuthcontroller::class,"register"]);
//Login
Route::post("login",[UserAuthcontroller::class,"login"])->name('login');

Route::group([
    "middleware" => ["auth:sanctum"]
],function(){

//Logout
Route::post("logout",[UserAuthcontroller::class,"logout"]);
//to logout normal user
Route::post('logoutuser', [UserAuthcontroller::class, 'logoutuser']);
//to change password
Route::post("changePassword",[UserAuthcontroller::class,"changePassword"]);
});

Route::group(['middleware' => ['admin.auth'], 'prefix' => 'admin'], function() {
    Route::get("", [UserAuthcontroller::class, "admin"]);
    Route::get("users", [UserAuthcontroller::class, "users"]);
    Route::get("format", [UserAuthcontroller::class, "format"]);
    Route::get("admin_profile", [SettingsController::class, "adminprofile"]);
    Route::get("add_user", [UserAuthcontroller::class, "adduser"]);
    Route::get("add_vendor", [VendorController::class, "addvendor"]);
    Route::get("area", [AreaController::class, "areas"]);
    Route::get("customer_list", [CustomerController::class, "customer"]);
    Route::get("add_customer", [CustomerController::class, "addcustomer"]);
    Route::get("blocked_client_list", [CustomerController::class, "blockedclientlist"]);
    Route::get("employees_list", [Emplyeescontroller::class, "employeeslist"]);
    Route::get("add_employee", [Emplyeescontroller::class, "addemployee"]);
    Route::get("employees_leave", [LeaveController::class, "employeesleave"]);
    Route::get("designation", [DesignationController::class, "adddesignation"]);
    Route::get("employee_attendance", [AttendanceController::class, "employeeattendance"]);
    Route::get("employee_attendance_report", [AttendanceController::class, "attendancereport"]);
    Route::get("company_list", [CompanyController::class, "addcompany"]);
    Route::get("category_list", [CategoryController::class, "addcategory"]);
    Route::get("subcategory_list", [SubCategoryController::class, "addsubcategory"]);
    Route::get("products_list", [ProductsController::class, "addproduct"]);
    Route::get("product_price_list", [ProductsController::class, "productpricelist"]);
    Route::get("product_import", [ProductsController::class, "productimport"]);
    Route::get("purchase_list", [PurchaseController::class, "addpurchase"]);
    Route::get("purchase", [PurchaseController::class, "purchases"]);
    Route::get('edit_purchase_list/{id}', [PurchaseController::class, 'editpurchases'])->name('edit.purchase');
    Route::get('purchase_invoice/{id}', [PurchaseController::class, 'purchaseinvoice'])->name('purchase.invoice');
    Route::get("GRN", [grnController::class, "openGRN"]);
    Route::get("chart_of_account", [AccountController::class, "chartofaccount"]);
    Route::get("add_account", [AccountController::class, "addaccount"]);
    Route::get('assets_child/{head_name}', [AccountController::class, 'showByHeadName'])->name('assets.child');
    Route::get('liability_child/{head_name}', [AccountController::class, 'liabilitychild'])->name('liability.child');
    Route::get('revenue_child/{head_name}', [AccountController::class, 'revenuechild'])->name('revenue.child');
    Route::get('equity_child/{head_name}', [AccountController::class, 'equitychild'])->name('equity.child');
    Route::get('expense_child/{head_name}', [AccountController::class, 'expensechild'])->name('expense.child');
    Route::get('customers_account/{head_name}', [AccountController::class, 'customersaccount'])->name('customers.child');
    Route::get('/get_account/{id}', [AccountController::class, 'getAccount'])->name('get.account');
    Route::get('vendor_account/{head_name}', [AccountController::class, 'vendoraccountssss'])->name('vendor.child');
    Route::get("payment", [PaymentController::class, "pay"]);
    Route::get("POS", [saleController::class, "pos"]);
    Route::get("POS_2", [saleController::class, "pos2"]);
    Route::get("sale_list", [saleController::class, "salelist"]);
    Route::get('/edit_sale_list/{id}', [SaleController::class, 'edit'])->name('admin.edit_sale_list');
    Route::get('sale_invoice/{id}', [SaleController::class, 'saleinvoice'])->name('sale.invoice');
    Route::get('sale_print_invoice/{id}', [SaleController::class, 'saleprintinvoice'])->name('saleprint.invoice');
    Route::get('add_voucher', [VoucherController::class, 'addvoucher'])->name('voucher');
    Route::get('voucher', [VoucherController::class, 'voucher'])->name('showvoucher');
    Route::get('voucher_items/{id}', [VoucherController::class, 'voucheritems'])->name('voucher.items');
    Route::get('edit_voucher/{id}', [VoucherController::class, 'editvoucher'])->name('edit.vouchar');
    Route::get("salary", [SalaryController::class, "salarys"]);
    Route::get("sale_report", [SaleReportController::class, "salereport"]);
    Route::get("sale_items_detail/{id}", [SaleReportController::class, "saleitemsdetail"]);
    Route::get("stock_report", [StockReportContoller::class, "stockreport"]);
    Route::get("jv_voucher", [VoucherController::class, "jvvoucher"]);
    Route::get('edit_jv_voucher/{id}', [VoucherController::class, 'editjvvoucher'])->name('editjv.vouchar');
    Route::get('complete_jv_voucher/{id}', [VoucherController::class, 'completejvvoucher'])->name('complatejv.vouchar');
    Route::get('pay_salary/{id}', [SalaryController::class, 'paysalary'])->name('salary.store');
    Route::get("day_close_report", [DayCloseController::class, "dayclosereport"]);
    Route::get("general_ledger", [LedgerController::class, "generalledger"]);
    Route::get('/sale_return/{id}', [SaleReturnController::class, 'salereturn'])->name('admin.salereturn');
    Route::get("payed_salary", [SalaryController::class, "payedsalary"]);
    Route::get("purchase_return", [PurchaseRetutrnController::class, "purchasereturn"]);
    Route::get('edit_user/{id}', [UserAuthcontroller::class, 'edituserpage']);
    Route::get("profit_loss_report", [ProfitLossController::class, "profitlossreport"]);
    Route::get("trial_balance", [TrialBalanceController::class, "trialbalance"]);
    Route::get("backup_reset", [BackupController::class, "backupreset"]);
    Route::get("balance_sheet", [BalanceSheetController::class, "balancesheet"]);
    Route::get("customer_report", [CustomerReportController::class, "customerreport"]);
    Route::get("vendor_report", [VendorReportController::class, "vendorreport"]);
    Route::get("deal_list", [DealListController::class, "deallist"]);
    Route::get("add_deal", [DealListController::class, "adddeal"]);
    Route::get('edit_deal_list/{id}', [DealListController::class, 'editdeallist']);
    Route::get("manufacture_company_list", [ManufactureCompanyController::class, "addmanufacturecompany"]);
    Route::get("manufacture_category_list", [ManufactureCategoryController::class, "addmanufacturecategory"]);
    Route::get("raw_material_list", [RawMaterialController::class, "addrawmaterial"]);
    Route::get("material_purchase", [MaterialPurchaseController::class, "materialpurchase"]);
    Route::get("purchase_report", [PurchaseReportController::class, "purchasereport"]);
    Route::get("vehicle_list", [vehicleRecordController::class, "vehiclelist"]);
    Route::get("vehicle_record_add", [vehicleRecordController::class, "vehiclerecordadd"]);
    Route::get("vehicle_alert", [vehicleRecordController::class, "vehiclealert"]);
    Route::get("fine", [FineController::class, "showfine"]);
    Route::get("alerts", [AlertController::class, "alert"]);
    Route::get("sale_graph", [SaleGraphController::class, "salegraph"]);
    Route::get("alerts_list", [AlertController::class, "alertlist"]);
});
//to search vendor report
Route::get('/vendor_report', [VendorReportController::class, 'vendorreportsearch'])->name('vendorreportsearch');
//to search customer report
Route::get('/customer_report', [CustomerReportController::class, 'customerreportsearch'])->name('customerreportsearch');
//to search balance sheet
Route::get('/balance_sheet', [BalanceSheetController::class, 'balancesheetsearch'])->name('searchbalancesheet');
//to open forgot password page
Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
//to send reset link
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
//to open reset password page
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
//to reset password
Route::post('reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
//to update profile
Route::post('/update-profile', [SettingsController::class, 'updateProfile'])->name('update.profile');
//to get user data
Route::post('/get-user-data', [UserAuthcontroller::class, 'getUserData'])->name('user.getData');
//to edit user
Route::post('/users/{id}/edit', [UserAuthController::class, 'editUser']);
//to delet user
Route::post('/delete-user', [UserAuthcontroller::class, 'deleteUser'])->name('delete.user');
//to add vendor data
Route::post('/vendor/store', [VendorController::class, 'store'])->name('vendor.store');
//to get sectionssssssssblog data
Route::get('/vendor/{id}', [VendorController::class, 'show'])->name('vendor.show');
// Update vendor data
Route::post('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
//to delet blog
Route::post('/delete-vendor', [VendorController::class, 'deletevendor'])->name('delete.vendor');
//to add area data
Route::post('/area/store', [AreaController::class, 'store'])->name('area.store');
//to get area data
Route::get('/area/{id}', [AreaController::class, 'show'])->name('area.show');
// Update area data
Route::post('/area/{id}', [AreaController::class, 'update'])->name('area.update');
//to delet blog
Route::post('/delete-area', [AreaController::class, 'deletearea'])->name('delete.area');
//to add customer data
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
//to get customer data
Route::get('/customer/{id}', [CustomerController::class, 'show'])->name('customer.show');
// Update customer data
Route::post('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
//to delet customer
Route::post('/delete-customer', [CustomerController::class, 'deletecustomer'])->name('delete.customer');
//to block customer
Route::post('/customer/block/{id}', [CustomerController::class, 'blockCustomer'])->name('customer.block');
//to ublok customer
Route::post('/customer/unblock/{id}', [CustomerController::class, 'unblock'])->name('customer.unblock');
//to add employee data
Route::post('/employee/store', [Emplyeescontroller::class, 'store'])->name('employee.store');
//to get employee data
Route::get('/employee/{id}', [Emplyeescontroller::class, 'show'])->name('employee.show');
// Update employee data
Route::post('/employee/{id}', [Emplyeescontroller::class, 'update'])->name('employee.update');
//to delet employee
Route::post('/delete-employee', [Emplyeescontroller::class, 'deleteemployee'])->name('delete.employee');
//to add leave data
Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
//to get leave data
Route::get('/leave/{id}', [LeaveController::class, 'show'])->name('leave.show');
// Update leave data
Route::post('/leave/{id}', [LeaveController::class, 'update'])->name('leave.update');
//to delet leave
Route::post('/delete-leave', [LeaveController::class, 'deleteleave'])->name('delete.leave');
//to add designation data
Route::post('/designation/store', [DesignationController::class, 'store'])->name('designation.store');
//to get designation data
Route::get('/designation/{id}', [DesignationController::class, 'show'])->name('designation.show');
// Update designation data
Route::post('/designation/{id}', [DesignationController::class, 'update'])->name('designation.update');
//to delet designation
Route::post('/delete-designation', [DesignationController::class, 'deletedesignation'])->name('designation.leave');
//to mark attendance
Route::post('/mark-attendancec', [AttendanceController::class, 'markAttendance']);
//search attendance report
Route::get('/attendance-report', [AttendanceController::class, 'report'])->name('attendance.report');
//to add company data
Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');
//to get company data
Route::get('/company/{id}', [CompanyController::class, 'show'])->name('company.show');
// Update company data
Route::post('/company/{id}', [CompanyController::class, 'update'])->name('company.update');
//to delet company
Route::post('/delete-company', [CompanyController::class, 'deletecompany'])->name('company.leave');
//to add category data
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
//to get category data
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
// Update category data
Route::post('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
//to delet category
Route::post('/delete-category', [CategoryController::class, 'deletecategory'])->name('category.leave');
//to add sub data
Route::post('/sub/store', [SubCategoryController::class, 'store'])->name('sub.store');
//to get sub data
Route::get('/sub/{id}', [SubCategoryController::class, 'show'])->name('sub.show');
// Update sub data
Route::post('/sub/{id}', [SubCategoryController::class, 'update'])->name('sub.update');
//to delet sub
Route::post('/delete-sub', [SubCategoryController::class, 'deletedeletesub'])->name('sub.leave');
//to add product data
Route::post('/product/store', [ProductsController::class, 'store'])->name('product.store');
//to get product data
Route::get('/product/{id}', [ProductsController::class, 'show'])->name('product.show');
// Update product data
Route::post('/product/{id}', [ProductsController::class, 'update'])->name('product.update');
//to delet product
Route::post('/delete-product', [ProductsController::class, 'deleteproduct'])->name('product.leave');
//to add opening qty
Route::post('/products/add-opening-quantity', [ProductsController::class, 'addOpeningQuantity'])->name('products.addOpeningQuantity');
//to edit opening qty
Route::post('/products/update-opening-quantity', [ProductsController::class, 'updateOpeningQuantity'])->name('products.updateOpeningQuantity');
//to import product
Route::post('/import-csv', [ProductsController::class, 'importCSV']);
//to add purchase list
Route::post('/purchase/save', [PurchaseController::class, 'store'])->name('purchase.store');
//to serch purchase list
Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.indexx');
//to del purchase
Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
//to get product price
Route::get('/products/{productId}/price', [ProductsController::class, 'getUpdatedPrice']);
//to get product data
Route::get('/products/{id}/data', [ProductsController::class, 'getProductData'])->name('products.getData');
//to edit purchase
Route::post('/api/edit-purchase/{id}', [PurchaseController::class, 'update']);
//to get PO for grn 
Route::get('/get-purchase-details/{id}', [grnController::class, 'getPurchaseDetails']);
//to get account data
Route::get('/account/{id}', [AccountController::class, 'show'])->name('account.show');
// Update account data
Route::post('/account/{id}', [AccountController::class, 'update'])->name('account.update');
//to add account child
Route::post('/add-account', [AccountController::class, 'store'])->name('add.account');
//to get account with child
Route::get('/get-sub-heads-by-head-name/{accountName}', [AccountController::class, 'getSubHeadsByHeadName']);
//to save child child
Route::post('/save-sub-head-name', [AccountController::class, 'storeSubHead']);
//to del account
Route::delete('/delete-account/{id}', [AccountController::class, 'deleteAccount']);
//to save edit account
Route::put('/save-account/{id}', [AccountController::class, 'saveAccount'])->name('save.account');
//same
Route::put('/edit-account/{id}', [AccountController::class, 'saveacccount'])->name('save.account');
//to add opening for accounts
Route::post('/update-opening', [AccountController::class, 'updateOpening']);
//to mark mutliple attendance
Route::post('/mark-attendance', [AttendanceController::class, 'mark']);
//to edit product in price list
Route::post('/update-product-inline', [ProductsController::class, 'updateInline'])->name('product.updateInline');
//to get produts for purchase
Route::get('/get-product/{id}', [ProductsController::class, 'getProduct'])->name('products.getProduct');
//to grn purchase
Route::post('/update-purchase-stock', [grnController::class, 'updatePurchaseStock']);
//to submit payment
Route::post('/submit-payment', [PaymentController::class, 'storePayment'])->name('submit.payment');
//to get products for sale
Route::get('/get-product-details/{id}', [SaleController::class, 'getProductDetails']);
//to get customer related to user
Route::get('/get-customers-by-username/{username}', [SaleController::class, 'getCustomersByUsername']);
//to get customer discount
Route::get('/get-customer-discount/{customerId}', [SaleController::class, 'getCustomerDiscount']);
//to save sale
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
//to edit sale
Route::put('/submit-sale-form/{saleId}', [SaleController::class, 'updateSale'])->name('sale.update');
//to del sale
Route::delete('/saledelete/{saleId}', [SaleController::class, 'deleteSale'])->name('sale.delete');
//to complete sale
Route::post('/complete-sale', [SaleController::class, 'completeSale'])->name('complete.sale');
//to get stock value 
Route::get('/get-product-quantity', [SaleController::class, 'getProductQuantity']);
//to serch sale list
Route::get('/sales-list', [SaleController::class, 'salelistsearch'])->name('sales.list');
//to get csh in ahnd
Route::post('/get-cash-in-hand', [VoucherController::class, 'getCashInHand']);
//to get account balance
Route::get('/get-account-balance', [VoucherController::class, 'getAccountBalance']);
//to add voucher
Route::post('/save-voucher', [VoucherController::class, 'store'])->name('voucher.store');
//to del voucher
Route::delete('/voucher/{id}', [VoucherController::class, 'destroy'])->name('voucher.destroy');
//to serch voucher
Route::get('/search-vouchers', [VoucherController::class, 'search'])->name('search.vouchers');
//to edit voucher
Route::post('/edit_voucher/{id}', [VoucherController::class, 'editvouchers'])->name('voucher.update');
//to search sale
Route::get('/sales', [SaleReportController::class, 'searchSales'])->name('sales.search');
//to srch stock
Route::get('/stock/search', [StockReportContoller::class, 'search'])->name('stock.search');
//to add jv voucher
Route::post('/save-jv-voucher', [VoucherController::class, 'storejv'])->name('jvvoucher.store');
//to edit jv voucher
Route::post('/editjv_voucher/{id}', [VoucherController::class, 'editjvvouchers'])->name('voucherjv.update');
//to complate jv voucher
Route::post('/complatejv_voucher/{id}', [VoucherController::class, 'complatejvvoucher'])->name('completevoucherjv.update');
//to pay salary
Route::post('/pay_salary/{employee_id}', [SalaryController::class, 'storeSalary'])->name('salary.store');
//to pay advance
Route::post('/salary/advance', [SalaryController::class, 'store'])->name('salary.advance.store');
//to get salry data
Route::get('/employee/{id}/salary-info', [SalaryController::class, 'getSalaryInfo'])->name('employee.salary-info');
//to search day close report
Route::get('/day-close-report', [DayCloseController::class, 'searchdayclosereport'])->name('dayclose.route');
Route::get('/api/subheads/by-head/{head}', [LedgerController::class, 'getSubHeadsByHead']);

// Get Sub Childs based on selected sub head
Route::get('/sub-heads/{child}', [LedgerController::class, 'getSubHeadsByHeads'])
    ->where('child', '.*');
//to serch general ledger
Route::get('/search-generalledger', [LedgerController::class, 'searchgeneralledger'])->name('searchgeneralledger');
//to get product with barcode
Route::get('/get-product-by-barcode/{barcode}', [ProductsController::class, 'getByBarcode']);

Route::post('/sale_return/{sale_id}', [SaleReturnController::class, 'processSaleReturn'])->name('process.sale.return');
//to del salary
Route::delete('/salrys/{id}', [SalaryController::class, 'destroy'])->name('salry.destroy');
//to srch salary
Route::get('/srchpayedsalary', [SalaryController::class, 'srchpayedsalary'])->name('searchpayedsalary');
//to get
Route::get('/admin/salry_invoice/{id}', [SalaryController::class, 'show'])->name('salry.show');
//to update
Route::put('/admin/salry_invoice/{id}', [SalaryController::class, 'update'])->name('salry.update');
//to get product data
Route::get('/api/productssssssssssssssssssssss/{id}', [ProductsController::class, 'getProductsssssssssssss']);
//to get PO for grn 
Route::get('/get-purchasereturn-details/{id}', [PurchaseRetutrnController::class, 'getPurchasereturnDetails']);
//to save return values 
Route::post('/save-return-quantities/{id}', [PurchaseRetutrnController::class, 'saveReturnQuantities']);
//to edit user 
Route::post('/user/update/{id}', [UserController::class, 'updateUser'])->name('user.update');
//to chnge user password
Route::post('/admin/change-password/{id}', [UserController::class, 'changePassword'])->name('admin.change.password');
//to add permissions
Route::post('/permissions/save/{user}', [UserController::class, 'savePermissions'])->name('permissions.save');
//to search profit loss report
Route::get('/profit_loss_report', [ProfitLossController::class, 'searchprofitlossreport'])->name('profitlossreport');
//to search trial balance
Route::get('/trialBalance', [TrialBalanceController::class, 'searchtrialbalance'])->name('trialbalancesearch');
//for backup
Route::get('/backup', [BackupController::class, 'backupDatabase'])->name('backup.database');
//for reset
Route::post('/reset', [BackupController::class, 'resetDatabase'])->name('reset.database');
//to save deal
Route::post('/save-deal', [DealListController::class, 'store'])->name('deal.store');
//to del deal
Route::delete('/deals/{id}', [DealListController::class, 'destroy'])->name('deal.destroy');
//to edit deal 
Route::put('/edit_deals/{id}', [DealListController::class, 'update'])->name('deal.update');
Route::get('/check-product-stock', function (\Illuminate\Http\Request $request) {
    $product = \App\Models\Product::where('item_name', $request->item_name)->first();
    return response()->json([
        'available_quantity' => $product ? (int)$product->quantity : 0
    ]);
});
//to add manufacturecompany data
Route::post('/manufacturecompany/store', [ManufactureCompanyController::class, 'storemanufacturecompany'])->name('manufacturecompany.store');
//to get manufacturecompany data
Route::get('/manufacturecompany/{id}', [ManufactureCompanyController::class, 'manufacturecompanyshow'])->name('manufacturecompany.show');
// Update manufacturecompany data
Route::post('/manufacturecompany/{id}', [ManufactureCompanyController::class, 'manufacturecompanyupdate'])->name('manufacturecompany.update');
//to delet manufacturecompany
Route::post('/delete-manufacturecompany', [ManufactureCompanyController::class, 'deletemanufacturecompany'])->name('manufacturecompany.delete');
//to add manufacturecategory data
Route::post('/manufacturecategory/store', [ManufactureCategoryController::class, 'storemanufacturecategory'])->name('manufacturecategory.store');
//to get manufacturecategory data
Route::get('/manufacturecategory/{id}', [ManufactureCategoryController::class, 'showmanufacturecategory'])->name('manufacturecategory.show');
// Update manufacturecategory data
Route::post('/manufacturecategory/{id}', [ManufactureCategoryController::class, 'updatemanufacturecategory'])->name('manufacturecategory.update');
//to delet manufacturecategory
Route::post('/delete-manufacturecategory', [ManufactureCategoryController::class, 'deletemanufacturecategory'])->name('manufacturecategory.leave');
//to add rawmaterial data
Route::post('/rawmaterial/store', [RawMaterialController::class, 'store'])->name('rawmaterial.store');
//to get rawmaterial data
Route::get('/rawmaterial/{id}', [RawMaterialController::class, 'show'])->name('rawmaterial.show');
// Update rawmaterial data
Route::post('/rawmaterial/{id}', [RawMaterialController::class, 'update'])->name('rawmaterial.update');
//to delet rawmaterial
Route::post('/delete-rawmaterial', [RawMaterialController::class, 'deleterawmaterial'])->name('rawmaterial.leave');
//to get rawmaterialproduct data
Route::get('/api/rawmaterialproducts/{id}', [RawMaterialController::class, 'rawmaterialsss']);
//to add stock for raw material 
Route::post('/rawmaterialpurchase/save', [MaterialPurchaseController::class, 'storerawmarerial'])->name('rawmaterialpurchase.store');
//to search purchase report
Route::get('/purchase/search', [PurchaseReportController::class, 'searchpurchasereportsssssss'])->name('purchasesssssss.search');
//to add vehicle data
Route::post('/vehicle/store', [vehicleRecordController::class, 'store'])->name('vehicle.store');
//to get vehicle data
Route::get('/vehicle/{id}', [vehicleRecordController::class, 'show'])->name('vehicle.show');
// Update vehicle data
Route::post('/vehicle/{id}', [vehicleRecordController::class, 'update'])->name('vehicle.update');
//to delet vehicle
Route::post('/delete-vehicle', [vehicleRecordController::class, 'deletevehicle'])->name('delete.vehicle');
//to add alert
Route::post('/save-alert', [vehicleRecordController::class, 'storealert'])->name('alert.store');
//to get data for deal to get its items in profit loss
Route::get('/deal-items/{saleItemId}', [ProfitLossController::class, 'getBySaleItemId'])->name('deal.items.bySaleItemId');
//to add fine data
Route::post('/fines/store', [FineController::class, 'store'])->name('fine.store');
//to get fine data
Route::get('/fine/{id}', [FineController::class, 'show'])->name('fine.show');
// Update fine data
Route::put('/fine/update/{id}', [FineController::class, 'update'])->name('fine.update');
//to delet fine
Route::delete('/fine/{id}', [FineController::class, 'destroy'])->name('fine.destroy');
//to add alerts
Route::post('/alerts/store', [AlertController::class, 'store'])->name('alerts.store');
//mark as read
Route::post('/alerts/mark-read/{id}', [AlertController::class, 'markRead'])->name('alerts.markRead');