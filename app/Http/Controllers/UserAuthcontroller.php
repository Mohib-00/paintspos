<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\GrnAccount;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserAuthcontroller extends Controller
{
   public function register(Request $request) {
    try {
        $validateuser = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|same:password',
        ]);

        if ($validateuser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateuser->errors()
            ], 401);
        }

        $existingUser = User::whereRaw('LOWER(name) = ?', [strtolower($request->name)])->first();

        if ($existingUser) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => [
                    'name' => ['The name has already been taken.']
                ]
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage(),
        ], 500);
    }
}

 
     public function login(Request $request)
{
    try {
      
        $validateuser = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

       
        if ($validateuser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateuser->errors(),
            ], 422); 
        }

        
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'The credentials do not match our record.',
                'errors' => [
                    'password' => ['The password you entered is incorrect.']
                ],
            ], 401); 
        }
 
        $user = Auth::user();

        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'userType' => $user->userType,
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th->getMessage(),
        ], 500);
    }
}


public function logout() {
       
    auth()->user()->tokens()->delete();

    Session::flush(); 

    return response()->json([
        'status' => true,
        'message' => 'User logged out',
        'data' => [],
    ], 200);
}

     public function logoutuser() {
       
        auth()->user()->tokens()->delete();
 
        Session::flush(); 
    
        return response()->json([
            'status' => true,
            'message' => 'User logged out',
            'data' => [],
        ], 200);
    }
    public function getUserData(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);
        $currentUser = Auth::user();  
    
        if ($user) {
            return response()->json([
                'success' => true,
                'user' => $user,
                'currentUser' => $currentUser
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'User not found']);
    }
    
    public function editUser(Request $request, $id)
    {
        
    
        $user = User::findOrFail($id);
    
        $user->name = $request->name;
        $user->email = $request->email;
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->userType = $request->userType;
    
        $user->save();
    
        return response()->json(['message' => 'User updated successfully.']);
    }
    
    public function deleteUser(Request $request)
    {
        $user = User::find($request->user_id);
    
        if ($user) {
            $user->delete();
    
            return response()->json(['success' => true, 'message' => 'User deleted successfully']);
        }
    
        return response()->json(['success' => false, 'message' => 'User not found']);
    }
 
     
     public function home(){ 
         //$user = Auth::user();         
         return view('userpages.home');
     }
 
     public function admin(){ 
       $user = Auth::user();        
       $today = Carbon::today(); 
       $cashSubtotalToday = Sale::where('sale_type', 'cash')
    ->whereDate('created_at', $today)
    ->sum('subtotal');

    $totalSaleReturnToday = Sale::whereDate('created_at', $today)->sum('sale_return');
    $totalSale = Sale::whereDate('created_at', $today)->sum('subtotal');


    $expenseAccountIds = AddAccount::where('head_name', 'Expense Accounts')->pluck('id');

    $vendorNetAmount = GrnAccount::whereIn('vendor_account_id', $expenseAccountIds)
    ->whereDate('created_at', $today)
    ->sum('vendor_net_amount');

$debitAmount = GrnAccount::whereIn('vendor_account_id', $expenseAccountIds)
    ->whereDate('created_at', $today)
    ->sum('debit');

$totalExpense = max(0, $debitAmount - $vendorNetAmount);




  $AccountreceiveIds = AddAccount::where('head_name', 'Accounts Receiveable')->pluck('id');
  
  

    $vendorNetAmount = GrnAccount::whereIn('vendor_account_id', $AccountreceiveIds)
    ->sum('vendor_net_amount');
   

$debitAmount = GrnAccount::whereIn('vendor_account_id', $AccountreceiveIds)
    ->sum('debit');
     

$totalreciveables = ( $debitAmount - $vendorNetAmount);


$totalPurchase = Purchase::whereDate('created_at', $today)->get()->sum(function ($purchase) {
    $rates = json_decode($purchase->purchase_rate, true);
    return is_array($rates) ? array_sum($rates) : 0;
});

$startOfMonth = Carbon::now()->startOfMonth();
$endOfMonth = Carbon::now()->endOfMonth();

$monthlySale = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                   ->sum('subtotal');


$monthlySaleReturn = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                   ->sum('sale_return');                   
          
$monthlyCashSubtotal = Sale::where('sale_type', 'cash')
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('subtotal');                   

$monthlyCreditSubtotal = Sale::where('sale_type', 'credit')
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('subtotal'); 


    $expenseAccountIds = AddAccount::where('head_name', 'Expense Accounts')->pluck('id');

$monthlyVendorNetAmount = GrnAccount::whereIn('vendor_account_id', $expenseAccountIds)
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('vendor_net_amount');

$monthlyDebitAmount = GrnAccount::whereIn('vendor_account_id', $expenseAccountIds)
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('debit');

$monthlyTotalExpense = max(0, $monthlyDebitAmount - $monthlyVendorNetAmount);



   $payableAccountIds = AddAccount::where('head_name', 'Accounts Payable')->pluck('id');

$monthlyVendorNetAmountpayables = GrnAccount::whereIn('vendor_account_id', $payableAccountIds)
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('vendor_net_amount');

$monthlyDebitAmounpayables = GrnAccount::whereIn('vendor_account_id', $payableAccountIds)
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('debit');

$monthlyTotalpayables = ( $monthlyVendorNetAmountpayables - $monthlyDebitAmounpayables);


$purchaseRates = Purchase::whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->pluck('amount_payed');

$monthlyPurchaseTotal = $purchaseRates->sum();




$cashinhandAccountIds = AddAccount::where('sub_head_name', 'Cash In Hand')->pluck('id');

$cashinhandcredit = GrnAccount::whereIn('vendor_account_id', $cashinhandAccountIds)
    ->sum('vendor_net_amount');

$cashinhanddebit = GrnAccount::whereIn('vendor_account_id', $cashinhandAccountIds)
    ->sum('debit');

$totalcashinhand = ( $cashinhanddebit - $cashinhandcredit);


$cashatBankAccountIds = AddAccount::where('sub_head_name', 'Cash At Bank')->pluck('id');

$cashAtbankcredit = GrnAccount::whereIn('vendor_account_id', $cashatBankAccountIds)
    ->sum('vendor_net_amount');

$cashatbankdebit = GrnAccount::whereIn('vendor_account_id', $cashatBankAccountIds)
    ->sum('debit');

$totalcashatBank = ( $cashatbankdebit - $cashAtbankcredit);


$totaldiscount = Sale::whereDate('created_at', $today)->sum('discount');
$totalfixdiscount = Sale::whereDate('created_at', $today)->sum('fixed_discount');


$monthlyDiscount = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('discount');

$monthlyfixDiscount = Sale::whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('fixed_discount');    




 $otherIncomeAccountIds = AddAccount::where('sub_head_name', 'Other Income')->pluck('id');

$monthlyotherIncomeAmount = GrnAccount::whereIn('vendor_account_id', $otherIncomeAccountIds)
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('vendor_net_amount');

$monthlyDebitotherincomeAmount = GrnAccount::whereIn('vendor_account_id', $otherIncomeAccountIds)
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('debit');

$monthlyTotalotherincome = ($monthlyotherIncomeAmount - $monthlyDebitotherincomeAmount); 





$paymentvendor = AddAccount::where('head_name', 'Accounts Payable')->pluck('id');
$monthlypaymentvendor = GrnAccount::whereIn('vendor_account_id', $paymentvendor)
    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('debit');

 $currentYear = date('Y');
 $currentMonth = date('m');
    $daysOfMonth = range(1, date('d'));

    $monthDailySales = Sale::select(
        DB::raw('DAY(created_at) as day'),
        DB::raw('SUM(subtotal) as subtotal_sales')
    )
    ->whereYear('created_at', $currentYear)
    ->whereMonth('created_at', $currentMonth)
    ->whereDay('created_at', '<=', date('d'))
    ->groupBy(DB::raw('DAY(created_at)'))
    ->orderBy('day')
    ->get();

    $monthDailyLabels = [];
    $monthDailyTotals = [];

    foreach($monthDailySales as $sale) {
        $monthDailyLabels[] = $sale->day;
        $monthDailyTotals[] = $sale->subtotal_sales;
    }


$monthlyDifference = $monthlypaymentvendor - $monthlyPurchaseTotal;    

       return view('adminpages.admin', ['userName' => $user->name,'userEmail' => $user->email,'monthDailyLabels' => $monthDailyLabels,
        'monthDailyTotals' => $monthDailyTotals],
       compact(
        'cashSubtotalToday',
        'totalSaleReturnToday',
        'totalSale',
        'totalExpense',
        'totalreciveables',
        'totalPurchase',
        'monthlySale',
        'monthlySaleReturn',
        'monthlyCashSubtotal',
        'monthlyCreditSubtotal',
        'monthlyTotalExpense',
        'monthlyTotalpayables',
        'monthlyPurchaseTotal',
        'totalcashinhand',
        'totaldiscount',
        'totalfixdiscount',
        'monthlyDiscount',
        'monthlyfixDiscount',
        'totalcashatBank',
        'monthlyTotalotherincome',
        'monthlyDifference',
        )
    );
   }

    public function adduser(){ 
    $user = Auth::user();         
    return view('adminpages.adduser', ['userName' => $user->name,'userEmail' => $user->email]);
    }

   public function  users(){ 
    $user = Auth::user();
    $users = User::all();
    return view('adminpages.users', ['userName' => $user->name,'userEmail' => $user->email],compact('users'));
  }

  public function  format(){ 
    $user = Auth::user();
    return view('adminpages.format', ['userName' => $user->name,'userEmail' => $user->email]);
  }

  public function changePassword(Request $request){
    $request->validate([
        'password' => 'required|confirmed',
    ]);
    $loggeduser = auth()->user();
    $loggeduser->password = Hash::make($request->password);
    $loggeduser->save();
    return response()->json([
        'message' => 'Password change succesfully',
        'status' => 'Success'   
    ], 200);
}


public function edituserpage($id)
{
    $user = Auth::user();
    $users = User::find($id);
    return view('adminpages.edituser', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'users' => $users,
    ]);
}

}
