<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\GrnAccount;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BalanceSheetController extends Controller
{


public function balancesheet()
{
    $user = Auth::user();

    $calculateBalanceBySubHead = function ($subHeadName) {
        $account = AddAccount::where('sub_head_name', $subHeadName)->first();

        if (!$account) {
            return 0;
        }

        $sums = GrnAccount::where('vendor_account_id', $account->id)
            ->whereDate('created_at', Carbon::today())
            ->selectRaw('SUM(vendor_net_amount) as total_vendor_net_amount, SUM(debit) as total_debit')
            ->first();

        $balanceFromGrn = ($sums->total_debit ?? 0) - ($sums->total_vendor_net_amount ?? 0);

        return ($account->opening ?? 0) + $balanceFromGrn;
    };

    $calculateBalanceByHeadOrSubHead = function ($headName, $subHeadName) {
        $accounts = AddAccount::where(function ($query) use ($headName, $subHeadName) {
            $query->where('head_name', $headName)
                  ->orWhere('sub_head_name', $subHeadName);
        })->get();

        $totalBalance = 0;

        foreach ($accounts as $account) {
            $sums = GrnAccount::where('vendor_account_id', $account->id)
                ->whereDate('created_at', Carbon::today())
                ->selectRaw('SUM(vendor_net_amount) as total_vendor_net_amount, SUM(debit) as total_debit')
                ->first();

            $balanceFromGrn = ($sums->total_debit ?? 0) - ($sums->total_vendor_net_amount ?? 0);

            $totalBalance += ($account->opening ?? 0) + $balanceFromGrn;
        }

        return $totalBalance;
    };


    $calculateBalanceforpayableByHeadOrSubHead = function ($headName, $subHeadName) {
    $accountsPayable = AddAccount::where(function ($query) use ($headName, $subHeadName) {
        $query->where('head_name', $headName)
              ->orWhere('sub_head_name', $subHeadName);
    })->get();

    $totalBalancepayable = 0;

    foreach ($accountsPayable as $account) {
        $sums = GrnAccount::where('vendor_account_id', $account->id)
            ->whereDate('created_at', Carbon::today())
            ->selectRaw('SUM(vendor_net_amount) as total_vendor_net_amount, SUM(debit) as total_debit')
            ->first();

        $balanceFromGrnpayable = ($sums->total_debit ?? 0) - ($sums->total_vendor_net_amount ?? 0);

        $totalBalancepayable += ($account->opening ?? 0) + $balanceFromGrnpayable;
    }

    return $totalBalancepayable;
    };

    $cashInHandBalance = $calculateBalanceBySubHead('Cash In Hand');
    $cashAtBankBalance = $calculateBalanceBySubHead('Cash At Bank');
    $inventory = $calculateBalanceBySubHead('Inventory');
    $FurnitureAndFixtures = $calculateBalanceBySubHead('Furniture & Fixtures');
    $software = $calculateBalanceBySubHead('Software');
    $officeEquipments = $calculateBalanceBySubHead('Office Equipments');
    $survellianceEquipments = $calculateBalanceBySubHead('Survelliance Equipments');
    $accountsReceivableBalance = $calculateBalanceByHeadOrSubHead('Accounts Receiveable', 'Accounts Receiveable');
    $accountsPayableBalance = $calculateBalanceforpayableByHeadOrSubHead('Accounts Payable', 'Accounts Payable');
    $taxPayable = $calculateBalanceBySubHead('Tax Payable');
    $loanPayable = $calculateBalanceBySubHead('Loan Payable');
    $ownerEquity = $calculateBalanceBySubHead('Owners Equity');
    $drawing = $calculateBalanceBySubHead('Drawings');

    $cashSales = $calculateBalanceBySubHead('Cash Sales');
    $creditSales = $calculateBalanceBySubHead('Credit Sales');
    $totalSale =    $cashSales + $creditSales;

    $saleReturnSums = $calculateBalanceBySubHead('Sales Return');


    $netSale = $totalSale + $saleReturnSums;
    $costOfGoodSolds = $calculateBalanceBySubHead('Cost Of Goods Sold');
    $grossProfit = $costOfGoodSolds +  $netSale;


    $otherIncome = $calculateBalanceBySubHead('Other Income');
    $disAvailed = $calculateBalanceBySubHead('Discount Availed');
    $totals = $otherIncome +  $disAvailed;
    
    $totalIncome = $grossProfit + $totals;

    $rentExpense = $calculateBalanceBySubHead('Rent Expense');
    $officeExpense = $calculateBalanceBySubHead('Office Expenses');
    $disGiven = $calculateBalanceBySubHead('Discount Given');
    $salryExpense = $calculateBalanceBySubHead('Salary Expense');
    $fuelExpense = $calculateBalanceBySubHead('Fuel Expense');
    $maintenanceExpense = $calculateBalanceBySubHead('Maintainance Expenses');
    $billExpense = $calculateBalanceBySubHead('Electricity Bill Expense');
    $internetExpense = $calculateBalanceBySubHead('Internet & TV Expense');
    $otherExpense = $calculateBalanceBySubHead('Other Expenses');
    $totalExpenses = $rentExpense + $officeExpense + $disGiven + $salryExpense + $fuelExpense + $maintenanceExpense + $billExpense + $internetExpense + $otherExpense;
    

    $pbit = $totalIncome - $totalExpenses;

    $taxPayable = $calculateBalanceBySubHead('Tax Payable');

    $net_profit = $pbit - $taxPayable;
    
    $today = Carbon::today();
    $totalOpening = AddAccount::sum('opening');
    $totalDebit = GrnAccount::whereDate('created_at', $today)->sum('debit');
    
    $totalCredit = GrnAccount::whereDate('created_at', $today)->sum('vendor_net_amount');
    $totalRetain = $totalOpening + $totalDebit - $totalCredit ;

    $ownerEquity = $calculateBalanceBySubHead('Owners Equity');
    $drawings = $calculateBalanceBySubHead('Drawings');

    $saleRate = SaleItem::whereDate('created_at', $today)->sum('product_subtotal');
    $totalPurchaseValue = SaleItem::whereDate('created_at', $today)->sum('purchase_rate');
    $net_profit = $saleRate - $totalPurchaseValue;

    $total_equity = $ownerEquity + $drawings + $net_profit - $totalRetain;
 
    return view('adminpages.balancesheet', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'cashInHandBalance' => $cashInHandBalance,
        'cashAtBankBalance' => $cashAtBankBalance,
        'accountsReceivableBalance' => $accountsReceivableBalance,
        'inventory' => $inventory,
        'FurnitureAndFixtures' => $FurnitureAndFixtures,
        'software' => $software,
        'officeEquipments' => $officeEquipments,
        'survellianceEquipments' => $survellianceEquipments,
        'accountsPayableBalance' => $accountsPayableBalance,
        'taxPayable' => $taxPayable,
        'loanPayable' => $loanPayable,
        'ownerEquity' => $ownerEquity,
        'drawing' => $drawing,
        'net_profit' => $net_profit,
        'totalRetain' => $totalRetain,
        'total_equity' => $total_equity
    ]);
}



public function balancesheetsearch(Request $request)
{
    $user = Auth::user();

    $from_date = $request->from_date ?? Carbon::today()->toDateString();
    $to_date = $request->to_date ?? Carbon::today()->toDateString();

    $from = Carbon::parse($from_date)->startOfDay();
    $to = Carbon::parse($to_date)->endOfDay();

    $calculateBalanceBySubHead = function ($subHeadName) use ($from, $to) {
        $account = AddAccount::where('sub_head_name', $subHeadName)->first();

        if (!$account) return 0;

        $sums = GrnAccount::where('vendor_account_id', $account->id)
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('SUM(vendor_net_amount) as total_vendor_net_amount, SUM(debit) as total_debit')
            ->first();

        $balanceFromGrn = ($sums->total_debit ?? 0) - ($sums->total_vendor_net_amount ?? 0);

        return ($account->opening ?? 0) + $balanceFromGrn;
    };

    $calculateBalanceByHeadOrSubHead = function ($headName, $subHeadName) use ($from, $to) {
        $accounts = AddAccount::where(function ($query) use ($headName, $subHeadName) {
            $query->where('head_name', $headName)
                  ->orWhere('sub_head_name', $subHeadName);
        })->get();

        $totalBalance = 0;

        foreach ($accounts as $account) {
            $sums = GrnAccount::where('vendor_account_id', $account->id)
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw('SUM(vendor_net_amount) as total_vendor_net_amount, SUM(debit) as total_debit')
                ->first();

            $balanceFromGrn = ($sums->total_debit ?? 0) - ($sums->total_vendor_net_amount ?? 0);

            $totalBalance += ($account->opening ?? 0) + $balanceFromGrn;
        }

        return $totalBalance;
    };

    $calculateBalanceforpayableByHeadOrSubHead = function ($headName, $subHeadName) use ($from, $to) {
        $accountsPayable = AddAccount::where(function ($query) use ($headName, $subHeadName) {
            $query->where('head_name', $headName)
                  ->orWhere('sub_head_name', $subHeadName);
        })->get();

        $totalBalancepayable = 0;

        foreach ($accountsPayable as $account) {
            $sums = GrnAccount::where('vendor_account_id', $account->id)
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw('SUM(vendor_net_amount) as total_vendor_net_amount, SUM(debit) as total_debit')
                ->first();

            $balanceFromGrnpayable = ($sums->total_debit ?? 0) - ($sums->total_vendor_net_amount ?? 0);

            $totalBalancepayable += ($account->opening ?? 0) + $balanceFromGrnpayable;
        }

        return $totalBalancepayable;
    };

    $cashInHandBalance = $calculateBalanceBySubHead('Cash In Hand');
    $cashAtBankBalance = $calculateBalanceBySubHead('Cash At Bank');
    $inventory = $calculateBalanceBySubHead('Inventory');
    $FurnitureAndFixtures = $calculateBalanceBySubHead('Furniture & Fixtures');
    $software = $calculateBalanceBySubHead('Software');
    $officeEquipments = $calculateBalanceBySubHead('Office Equipments');
    $survellianceEquipments = $calculateBalanceBySubHead('Survelliance Equipments');
    $accountsReceivableBalance = $calculateBalanceByHeadOrSubHead('Accounts Receiveable', 'Accounts Receiveable');
    $accountsPayableBalance = $calculateBalanceforpayableByHeadOrSubHead('Accounts Payable', 'Accounts Payable');
    $taxPayable = $calculateBalanceBySubHead('Tax Payable');
    $loanPayable = $calculateBalanceBySubHead('Loan Payable');
    $ownerEquity = $calculateBalanceBySubHead('Owners Equity');
    $drawing = $calculateBalanceBySubHead('Drawings');

    $cashSales = $calculateBalanceBySubHead('Cash Sales');
    $creditSales = $calculateBalanceBySubHead('Credit Sales');
    $totalSale = $cashSales + $creditSales;
    $saleReturnSums = $calculateBalanceBySubHead('Sales Return');
    $netSale = $totalSale + $saleReturnSums;

    $costOfGoodSolds = $calculateBalanceBySubHead('Cost Of Goods Sold');
    $grossProfit = $costOfGoodSolds + $netSale;

    $otherIncome = $calculateBalanceBySubHead('Other Income');
    $disAvailed = $calculateBalanceBySubHead('Discount Availed');
    $totals = $otherIncome + $disAvailed;

    $totalIncome = $grossProfit + $totals;

    $rentExpense = $calculateBalanceBySubHead('Rent Expense');
    $officeExpense = $calculateBalanceBySubHead('Office Expenses');
    $disGiven = $calculateBalanceBySubHead('Discount Given');
    $salryExpense = $calculateBalanceBySubHead('Salary Expense');
    $fuelExpense = $calculateBalanceBySubHead('Fuel Expense');
    $maintenanceExpense = $calculateBalanceBySubHead('Maintainance Expenses');
    $billExpense = $calculateBalanceBySubHead('Electricity Bill Expense');
    $internetExpense = $calculateBalanceBySubHead('Internet & TV Expense');
    $otherExpense = $calculateBalanceBySubHead('Other Expenses');
    $totalExpenses = $rentExpense + $officeExpense + $disGiven + $salryExpense + $fuelExpense + $maintenanceExpense + $billExpense + $internetExpense + $otherExpense;

    $pbit = $totalIncome - $totalExpenses;
    $net_profit = $pbit - $taxPayable;

    $totalOpening = AddAccount::sum('opening');
    $totalDebit = GrnAccount::whereBetween('created_at', [$from, $to])->sum('debit');
    $totalCredit = GrnAccount::whereBetween('created_at', [$from, $to])->sum('vendor_net_amount');
    $totalRetain = $totalOpening + $totalDebit - $totalCredit;

    $saleRate = SaleItem::whereBetween('created_at', [$from, $to])->sum('product_subtotal');
    $totalPurchaseValue = SaleItem::whereBetween('created_at', [$from, $to])->sum('purchase_rate');
    $net_profitt = $saleRate - $totalPurchaseValue;

    $total_equity = $ownerEquity + $drawing + $net_profitt - $totalRetain;

    return view('adminpages.balancesheet', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'cashInHandBalance' => $cashInHandBalance,
        'cashAtBankBalance' => $cashAtBankBalance,
        'accountsReceivableBalance' => $accountsReceivableBalance,
        'inventory' => $inventory,
        'FurnitureAndFixtures' => $FurnitureAndFixtures,
        'software' => $software,
        'officeEquipments' => $officeEquipments,
        'survellianceEquipments' => $survellianceEquipments,
        'accountsPayableBalance' => $accountsPayableBalance,
        'taxPayable' => $taxPayable,
        'loanPayable' => $loanPayable,
        'ownerEquity' => $ownerEquity,
        'drawing' => $drawing,
        'net_profit' => $net_profit,
        'totalRetain' => $totalRetain,
        'total_equity' => $total_equity,
        'from_date' => $from_date,
        'to_date' => $to_date,
    ]);
}


}
