<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\emplyees;
use App\Models\GrnAccount;
use App\Models\Salary;
use App\Models\Sale;
use App\Models\User;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DayCloseController extends Controller
{
   

public function dayclosereport() { 
    $user = Auth::user();  
    $today = Carbon::today();

    $users = User::withSum(['sales as cash_subtotal' => function ($query) use ($today) {
                    $query->where('sale_type', 'cash')->whereDate('created_at', $today);
                }], 'subtotal')
             ->withSum(['sales as credit_subtotal' => function ($query) use ($today) {
                    $query->where('sale_type', 'credit')->whereDate('created_at', $today);
                }], 'subtotal')
             ->withSum(['vouchers as cash_receipt_total' => function ($query) use ($today) {
                    $query->where('voucher_type', 'Cash Receipt')->whereDate('created_at', $today);
                }], 'totalAmount')
             ->withSum(['sales as total_items_today' => function ($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }], 'total_items')
             ->withSum(['sales as total_today' => function ($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }], 'total')
             ->withSum(['sales as discount_today' => function ($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }], 'discount')
             ->withSum(['sales as sale_return_today' => function ($query) use ($today) {
                    $query->whereDate('created_at', $today);
                }], 'sale_return')
             ->get();

    $totalSale = Sale::whereDate('created_at', $today)->sum('total');
    $totalDiscount = Sale::whereDate('created_at', $today)->sum('discount');
    $totalSaleReturn = Sale::whereDate('created_at', $today)->sum('sale_return');
    $netTotal = $totalSale - $totalDiscount - $totalSaleReturn;
    $totalFixedDiscount = Sale::whereDate('created_at', $today)->sum('fixed_discount');
    $creditSubtotal = Sale::where('sale_type', 'credit')->whereDate('created_at', $today)->sum('subtotal');

    $totalCashPayment = Voucher::where('voucher_type', 'Cash Payment')
                        ->whereDate('created_at', $today)
                        ->sum('totalAmount');

     $expenseAccountIds = AddAccount::where('head_name', 'Expense Accounts')->pluck('id');

    $vendorNetAmount = GrnAccount::whereIn('vendor_account_id', $expenseAccountIds)
    ->whereDate('created_at', $today)
    ->sum('vendor_net_amount');

$debitAmount = GrnAccount::whereIn('vendor_account_id', $expenseAccountIds)
    ->whereDate('created_at', $today)
    ->sum('debit');

$totalExpense = max(0, $debitAmount - $vendorNetAmount);
$totalSalariesPaid = Salary::whereDate('created_at', $today)
    ->sum('paid');


    return view('adminpages.dayclosereport', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'users' => $users,
        'totalSale' => $totalSale,
        'totalDiscount' => $totalDiscount,
        'totalSaleReturn' => $totalSaleReturn,
        'netTotal' => $netTotal,
        'totalFixedDiscount' => $totalFixedDiscount,
        'creditSubtotal' => $creditSubtotal,
        'totalCashPayment' => $totalCashPayment,
        'totalExpense' => $totalExpense,
        'totalSalariesPaid' => $totalSalariesPaid
    ]);
}


public function searchdayclosereport(Request $request)
{
    $user = Auth::user();

    $fromDate = $request->input('from_date') ?? Carbon::today()->toDateString();
    $toDate = $request->input('to_date') ?? Carbon::today()->toDateString();

    $startDateTime = Carbon::parse($fromDate)->startOfDay();
    $endDateTime = Carbon::parse($toDate)->endOfDay();

    $users = User::withSum(['sales as cash_subtotal' => function ($query) use ($startDateTime, $endDateTime) {
                    $query->where('sale_type', 'cash')->whereBetween('created_at', [$startDateTime, $endDateTime]);
                }], 'subtotal')
             ->withSum(['sales as credit_subtotal' => function ($query) use ($startDateTime, $endDateTime) {
                    $query->where('sale_type', 'credit')->whereBetween('created_at', [$startDateTime, $endDateTime]);
                }], 'subtotal')
             ->withSum(['vouchers as cash_receipt_total' => function ($query) use ($startDateTime, $endDateTime) {
                    $query->where('voucher_type', 'Cash Receipt')->whereBetween('created_at', [$startDateTime, $endDateTime]);
                }], 'totalAmount')
             ->withSum(['sales as total_items_today' => function ($query) use ($startDateTime, $endDateTime) {
                    $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
                }], 'total_items')
             ->withSum(['sales as total_today' => function ($query) use ($startDateTime, $endDateTime) {
                    $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
                }], 'total')
             ->withSum(['sales as discount_today' => function ($query) use ($startDateTime, $endDateTime) {
                    $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
                }], 'discount')
             ->withSum(['sales as sale_return_today' => function ($query) use ($startDateTime, $endDateTime) {
                    $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
                }], 'sale_return')
             ->get();

    $totalSale = Sale::whereBetween('created_at', [$startDateTime, $endDateTime])->sum('total');
    $totalDiscount = Sale::whereBetween('created_at', [$startDateTime, $endDateTime])->sum('discount');
    $totalSaleReturn = Sale::whereBetween('created_at', [$startDateTime, $endDateTime])->sum('sale_return');
    $netTotal = $totalSale - $totalDiscount - $totalSaleReturn;
    $totalFixedDiscount = Sale::whereBetween('created_at', [$startDateTime, $endDateTime])->sum('fixed_discount');
    $creditSubtotal = Sale::where('sale_type', 'credit')->whereBetween('created_at', [$startDateTime, $endDateTime])->sum('subtotal');

    $totalCashPayment = Voucher::where('voucher_type', 'Cash Payment')
                        ->whereBetween('created_at', [$startDateTime, $endDateTime])
                        ->sum('totalAmount');

    $expenseAccountIds = AddAccount::where('head_name', 'Expense Accounts')->pluck('id');

    $vendorNetAmount = GrnAccount::whereIn('vendor_account_id', $expenseAccountIds)
        ->whereBetween('created_at', [$startDateTime, $endDateTime])
        ->sum('vendor_net_amount');

    $debitAmount = GrnAccount::whereIn('vendor_account_id', $expenseAccountIds)
        ->whereBetween('created_at', [$startDateTime, $endDateTime])
        ->sum('debit');

    $totalExpense = max(0, $debitAmount - $vendorNetAmount);
    $totalSalariesPaid = Salary::whereBetween('created_at', [$startDateTime, $endDateTime])->sum('paid');

    return view('adminpages.dayclosereport', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'users' => $users,
        'totalSale' => $totalSale,
        'totalDiscount' => $totalDiscount,
        'totalSaleReturn' => $totalSaleReturn,
        'netTotal' => $netTotal,
        'totalFixedDiscount' => $totalFixedDiscount,
        'creditSubtotal' => $creditSubtotal,
        'totalCashPayment' => $totalCashPayment,
        'totalExpense' => $totalExpense,
        'totalSalariesPaid' => $totalSalariesPaid
    ]);
}



}
