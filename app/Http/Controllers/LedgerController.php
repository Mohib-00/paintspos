<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AddAccount;
use App\Models\GrnAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LedgerController extends Controller
{
     public function generalledger(){ 
        $user = Auth::user();     
        $gnrlaccounts = Account::all();

        $totalAssetOpening = AddAccount::where('head_name', 'Asset Accounts')->sum('opening');
        $totalExpenseOpening = AddAccount::where('head_name', 'Expense Accounts')->sum('opening');
        $totalliabilityOpening = AddAccount::where('head_name', 'Liability Accounts')->sum('opening');
        $totalrevenueOpening = AddAccount::where('head_name', 'Revenue Accounts')->sum('opening');
        $totalequityOpening = AddAccount::where('head_name', 'Equity Accounts')->sum('opening');

        $today = Carbon::today();

        $assetAccountIds = AddAccount::whereIn('head_name', ['Asset Accounts', 'Accounts Receiveable'])->pluck('id');
        $matchingGrnAccounts = GrnAccount::with(['sale', 'purchase', 'voucherItems'])
        ->whereIn('vendor_account_id', $assetAccountIds)
        ->whereDate('created_at', $today)
        ->get();


        $expenseAccountIds = AddAccount::where('head_name', 'Expense Accounts')->pluck('id');
        $matchingExpenseAccounts = GrnAccount::with(['sale', 'purchase', 'voucherItems'])
        ->whereIn('vendor_account_id', $expenseAccountIds)
        ->whereDate('created_at', $today)
        ->get();


        $liabilityAccountIds = AddAccount::whereIn('head_name', ['Liability Accounts', 'Accounts Payable'])->pluck('id');

$matchingLiabilityAccounts = GrnAccount::with(['sale', 'purchase', 'voucherItems'])
    ->whereIn('vendor_account_id', $liabilityAccountIds)
    ->whereDate('created_at', $today)
    ->get();


        $revenueAccountIds = AddAccount::where('head_name', 'Revenue Accounts')->pluck('id');
        $matchingRevenueAccounts = GrnAccount::with(['sale', 'purchase', 'voucherItems'])
        ->whereIn('vendor_account_id', $revenueAccountIds)
        ->whereDate('created_at', $today)
        ->get();

        $equityAccountIds = AddAccount::where('head_name', 'Equity Accounts')->pluck('id');
        $matchingEquityAccounts = GrnAccount::with(['sale', 'purchase', 'voucherItems'])
        ->whereIn('vendor_account_id', $equityAccountIds)
        ->whereDate('created_at', $today)
        ->get();

        return view('adminpages.ledger', ['userName' => $user->name,'userEmail' => $user->email],compact(
        'gnrlaccounts',
        'totalAssetOpening',
        'matchingGrnAccounts',
        'matchingExpenseAccounts',
        'totalExpenseOpening',
        'totalliabilityOpening',
        'matchingLiabilityAccounts',
        'totalrevenueOpening',
        'matchingRevenueAccounts',
        'totalequityOpening',
        'matchingEquityAccounts'
        ));
    }

public function getSubHeadsByHead($head)
{
    $subHeads = AddAccount::where('head_name', $head)
        ->where('sub_head_name', 'not like', '%(%') 
        ->distinct()
        ->pluck('sub_head_name');

    return response()->json($subHeads);
}

public function getSubHeadsByHeads($child)
{
    $child = trim(urldecode(urldecode($child)));

    if (in_array($child, ['Accounts Receiveable', 'Accounts Payable'])) {
        $subHeads = AddAccount::where('head_name', $child)
            ->where('sub_head_name', 'not like', '%(%')
            ->distinct()
            ->pluck('sub_head_name');

        return response()->json($subHeads);
    }

    $subHeads = AddAccount::where('sub_head_name', 'like', "$child (%")
        ->distinct()
        ->pluck('sub_head_name'); 

    return response()->json(array_values($subHeads->toArray()));
}


public function searchgeneralledger()
{
    $user = Auth::user();
    $gnrlaccounts = Account::all();

    $fromDate = request('from_date');
    $toDate = request('to_date');

    $accountName = request('employee_id');      
    $subHeadName = request('sub_head_name');    
    $subChild = request('sub_child');           

    if (empty($fromDate) && empty($toDate)) {
        return view('adminpages.ledger', [
            'userName' => $user->name,
            'userEmail' => $user->email,
            'gnrlaccounts' => $gnrlaccounts,

            'totalAssetOpening' => 0,
            'matchingGrnAccounts' => collect(),

            'totalExpenseOpening' => 0,
            'matchingExpenseAccounts' => collect(),

            'totalliabilityOpening' => 0,
            'matchingLiabilityAccounts' => collect(),

            'totalrevenueOpening' => 0,
            'matchingRevenueAccounts' => collect(),

            'totalequityOpening' => 0,
            'matchingEquityAccounts' => collect(),
        ]);
    }

    $grnQuery = GrnAccount::with(['sale', 'purchase', 'voucherItems']);

     if ($fromDate && $toDate) {
        $from = Carbon::parse($fromDate)->startOfDay();
        $to = Carbon::parse($toDate)->endOfDay();
        $grnQuery->whereBetween('created_at', [$from, $to]);
    } elseif ($fromDate) {
        $from = Carbon::parse($fromDate)->startOfDay();
        $grnQuery->where('created_at', '>=', $from);
    } elseif ($toDate) {
        $to = Carbon::parse($toDate)->endOfDay();
        $grnQuery->where('created_at', '<=', $to);
    }

   if ($subChild) {
    $cleanedSubChild = preg_replace('/\s*\(.*?\)/', '', $subChild);

    $accountIds = AddAccount::where(function ($q) use ($subChild, $cleanedSubChild) {
        $q->where('head_name', $cleanedSubChild)
          ->orWhere('sub_head_name', $cleanedSubChild)
          ->orWhere('sub_head_name', $subChild)
          ->orWhere('sub_head_name', $subChild); 
    })->pluck('id')->toArray();

    $grnQuery->whereIn('vendor_account_id', $accountIds);
}
elseif ($subHeadName) {
    preg_match('/^(.*?)\s*(\(|$)/', $subHeadName, $matches);
    $baseSubHead = trim($matches[1] ?? $subHeadName);

    $accountIds = AddAccount::where(function ($query) use ($subHeadName, $baseSubHead) {
        $query->where('sub_head_name', $subHeadName) 
              ->orWhere('sub_head_name', 'like', $baseSubHead . ' (%') 
              ->orWhere('head_name', $subHeadName)
              ->orWhere('head_name', $baseSubHead);
    })->pluck('id')->toArray();

    $grnQuery->whereIn('vendor_account_id', $accountIds);


} elseif ($accountName) {
    if (in_array($accountName, ['Accounts Payable', 'Accounts Receiveable'])) {
        $accountIds = AddAccount::where(function ($q) use ($accountName) {
            $q->where('head_name', $accountName)
              ->orWhere('sub_head_name', $accountName);
        })->pluck('id')->toArray();
    } else {
        $accountIds = AddAccount::where('head_name', $accountName)->pluck('id')->toArray();
    }
    $grnQuery->whereIn('vendor_account_id', $accountIds);

} else {
    $grnQuery->whereRaw('1 = 0'); 
}

    $totalAssetOpening = AddAccount::whereIn('head_name', ['Asset Accounts', 'Accounts Receiveable'])->sum('opening');
    $totalExpenseOpening = AddAccount::where('head_name', 'Expense Accounts')->sum('opening');
    $totalliabilityOpening = AddAccount::whereIn('head_name', ['Liability Accounts', 'Accounts Payable'])->sum('opening');
    $totalrevenueOpening = AddAccount::where('head_name', 'Revenue Accounts')->sum('opening');
    $totalequityOpening = AddAccount::where('head_name', 'Equity Accounts')->sum('opening');

    $assetAccountIds = AddAccount::whereIn('head_name', ['Asset Accounts', 'Accounts Receiveable'])->pluck('id')->toArray();
    $expenseAccountIds = AddAccount::where('head_name', 'Expense Accounts')->pluck('id')->toArray();
    $liabilityAccountIds = AddAccount::whereIn('head_name', ['Liability Accounts', 'Accounts Payable'])->pluck('id')->toArray();
    $revenueAccountIds = AddAccount::where('head_name', 'Revenue Accounts')->pluck('id')->toArray();
    $equityAccountIds = AddAccount::where('head_name', 'Equity Accounts')->pluck('id')->toArray();

    $matchingGrnAccounts = (clone $grnQuery)->whereIn('vendor_account_id', $assetAccountIds)->get();
    $matchingExpenseAccounts = (clone $grnQuery)->whereIn('vendor_account_id', $expenseAccountIds)->get();
    $matchingLiabilityAccounts = (clone $grnQuery)->whereIn('vendor_account_id', $liabilityAccountIds)->get();
    $matchingRevenueAccounts = (clone $grnQuery)->whereIn('vendor_account_id', $revenueAccountIds)->get();
    $matchingEquityAccounts = (clone $grnQuery)->whereIn('vendor_account_id', $equityAccountIds)->get();

    return view('adminpages.ledger', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'gnrlaccounts' => $gnrlaccounts,

        'totalAssetOpening' => $totalAssetOpening,
        'matchingGrnAccounts' => $matchingGrnAccounts,

        'totalExpenseOpening' => $totalExpenseOpening,
        'matchingExpenseAccounts' => $matchingExpenseAccounts,

        'totalliabilityOpening' => $totalliabilityOpening,
        'matchingLiabilityAccounts' => $matchingLiabilityAccounts,

        'totalrevenueOpening' => $totalrevenueOpening,
        'matchingRevenueAccounts' => $matchingRevenueAccounts,

        'totalequityOpening' => $totalequityOpening,
        'matchingEquityAccounts' => $matchingEquityAccounts,
    ]);
}



}
