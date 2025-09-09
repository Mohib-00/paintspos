<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrialBalanceController extends Controller
{
public function trialbalance()
{
    $user = Auth::user();

    $accounts = AddAccount::all();

    $today = Carbon::today();
    $todayDate = $today->toDateString();

    foreach ($accounts as $account) {
        $accountId = $account->id;

        $vendorNetAmountSum = DB::table('grn_accounts')
            ->where('vendor_account_id', $accountId)
            ->whereDate('created_at', $todayDate)
            ->sum('vendor_net_amount');

        $debitSum = DB::table('grn_accounts')
            ->where('vendor_account_id', $accountId)
            ->whereDate('created_at', $todayDate)
            ->sum('debit');

        $openingBalance = 0;

        if (
            $account->head_name !== 'Revenue Accounts' &&
            $account->head_name !== 'Expense Accounts'
        ) {
            $openingBalance = AddAccount::where('id', $accountId)->value('opening');

            $totalDebitBeforeToday = DB::table('grn_accounts')
                ->where('vendor_account_id', $accountId)
                ->whereDate('created_at', '<', $todayDate)
                ->sum('debit');

            $totalVendorNetAmountBeforeToday = DB::table('grn_accounts')
                ->where('vendor_account_id', $accountId)
                ->whereDate('created_at', '<', $todayDate)
                ->sum('vendor_net_amount');

            $openingBalance += ($totalDebitBeforeToday - $totalVendorNetAmountBeforeToday);
        }

        $account->today_vendor_net_amount_sum = $vendorNetAmountSum;
        $account->today_debit_sum = $debitSum;
        $account->opening_balance = $openingBalance ?? 0;

    }

    return view('adminpages.trialbalance', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'accounts' => $accounts,
    ]);
}




public function searchtrialbalance(Request $request)
{
    $user = Auth::user();

    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');

    $accounts = AddAccount::all();

    $today = Carbon::today();
    $todayDate = $today->toDateString();

    foreach ($accounts as $account) {
        $accountId = $account->id;

        $dateStart = $fromDate ?? $todayDate;
        $dateEnd = $toDate ?? $todayDate;

        $vendorNetAmountSum = DB::table('grn_accounts')
            ->where('vendor_account_id', $accountId)
            ->whereBetween('created_at', [$dateStart . ' 00:00:00', $dateEnd . ' 23:59:59'])
            ->sum('vendor_net_amount');

        $debitSum = DB::table('grn_accounts')
            ->where('vendor_account_id', $accountId)
            ->whereBetween('created_at', [$dateStart . ' 00:00:00', $dateEnd . ' 23:59:59'])
            ->sum('debit');

        $openingBalance = 0;

        if (
            $account->head_name !== 'Revenue Accounts' &&
            $account->head_name !== 'Expense Accounts'
        ) {
            $openingBalance = AddAccount::where('id', $accountId)->value('opening');

            $totalDebitBeforeFromDate = DB::table('grn_accounts')
                ->where('vendor_account_id', $accountId)
                ->whereDate('created_at', '<', $dateStart)
                ->sum('debit');

            $totalVendorNetAmountBeforeFromDate = DB::table('grn_accounts')
                ->where('vendor_account_id', $accountId)
                ->whereDate('created_at', '<', $dateStart)
                ->sum('vendor_net_amount');

            $openingBalance += ($totalDebitBeforeFromDate - $totalVendorNetAmountBeforeFromDate);
        }

        $account->today_vendor_net_amount_sum = $vendorNetAmountSum;
        $account->today_debit_sum = $debitSum;
        $account->opening_balance = $openingBalance ?? 0;

        

    }

    return view('adminpages.trialbalance', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'accounts' => $accounts,
        'from_date' => $fromDate,
        'to_date' => $toDate,
    ]);
}


}
