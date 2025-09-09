<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\DealSaleItem;
use App\Models\GrnAccount;
use App\Models\Product;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{


public function profitlossreport()
{
    $user = Auth::user();
    $today = Carbon::today();

    $currentDate = Carbon::today();
    $saleItems = SaleItem::whereDate('created_at', $currentDate)->get();
    
    $revenueAccounts = AddAccount::where('head_name', 'Revenue Accounts')->get();

    foreach ($revenueAccounts as $revenueAccount) {
        $revenueAccount->totalDebit = GrnAccount::where('vendor_account_id', $revenueAccount->id)
            ->whereDate('created_at', $currentDate)
            ->sum('debit');

        $revenueAccount->totalVendorNet = GrnAccount::where('vendor_account_id', $revenueAccount->id)
            ->whereDate('created_at', $currentDate)
            ->sum('vendor_net_amount');

        $revenueAccount->netRevenue = abs($revenueAccount->totalVendorNet - $revenueAccount->totalDebit);
    }

    $overallNetRevenue = $revenueAccounts->sum('netRevenue');

    $subHeadNames = AddAccount::where('head_name', 'Expense Accounts')->get();

    foreach ($subHeadNames as $account) {
        $account->debitTotal = GrnAccount::where('vendor_account_id', $account->id)
            ->whereDate('created_at', $currentDate)
            ->sum('debit');

        $account->vendorNetAmountTotal = GrnAccount::where('vendor_account_id', $account->id)
            ->whereDate('created_at', $currentDate)
            ->sum('vendor_net_amount');

        $account->finalTotal = $account->debitTotal - $account->vendorNetAmountTotal;
    }

    $totalFinal = $subHeadNames->sum('finalTotal');

    return view('adminpages.profitlossreport', compact(
        'subHeadNames',
        'revenueAccounts',
        'saleItems',
        'totalFinal',
        'overallNetRevenue'
    ), [
        'userName' => $user->name,
        'userEmail' => $user->email,
    ]);
}


public function searchprofitlossreport(Request $request)
{
    $user = Auth::user();
    $products = Product::all();

    $fromDate = $request->input('from_date') ?? now()->toDateString();
    $toDate = $request->input('to_date') ?? now()->toDateString();

    $fromDateTime = $fromDate . ' 00:00:00';
    $toDateTime = $toDate . ' 23:59:59';

   

$saleItems = SaleItem::whereBetween('created_at', [$fromDateTime, $toDateTime])->get();


    $revenueAccounts = AddAccount::where('head_name', 'Revenue Accounts')->get();

foreach ($revenueAccounts as $revenueAccount) {
    $revenueAccount->totalDebit = GrnAccount::where('vendor_account_id', $revenueAccount->id)
        ->whereBetween('created_at', [$fromDateTime, $toDateTime])
        ->sum('debit');

    $revenueAccount->totalVendorNet = GrnAccount::where('vendor_account_id', $revenueAccount->id)
        ->whereBetween('created_at', [$fromDateTime, $toDateTime])
        ->sum('vendor_net_amount');

    $revenueAccount->netRevenue = abs($revenueAccount->totalDebit - $revenueAccount->totalVendorNet);
}

$overallNetRevenue = $revenueAccounts->sum('netRevenue');


     $subHeadNames = AddAccount::where('head_name', 'Expense Accounts')->get();

    foreach ($subHeadNames as $account) {
        $account->debitTotal = GrnAccount::where('vendor_account_id', $account->id)
            ->whereBetween('created_at', [$fromDateTime, $toDateTime])
            ->sum('debit');

        $account->vendorNetAmountTotal = GrnAccount::where('vendor_account_id', $account->id)
            ->whereBetween('created_at', [$fromDateTime, $toDateTime])
            ->sum('vendor_net_amount');

        $account->finalTotal = $account->debitTotal - $account->vendorNetAmountTotal;
    }

    $totalFinal = $subHeadNames->sum('finalTotal');

     return view('adminpages.profitlossreport', compact(
        'subHeadNames',
        'revenueAccounts',
        'saleItems',
        'totalFinal',
        'overallNetRevenue'
    ), [
        'userName' => $user->name,
        'userEmail' => $user->email,
    ]);
}


public function getBySaleItemId($saleItemId)
{
    $dealItems = DealSaleItem::where('sale_item_id', $saleItemId)->get();
    return response()->json($dealItems);
}


}
