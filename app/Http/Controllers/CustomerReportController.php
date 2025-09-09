<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerReportController extends Controller
{
   public function customerreport()
{
    $user = Auth::user();
    $customers = customer::all();

    $customerData = [];

    foreach ($customers as $customer) {
        $accountIds = DB::table('add_accounts')
            ->where('sub_head_name', $customer->customer_name)
            ->pluck('id');

        $sums = DB::table('grn_accounts')
            ->whereIn('vendor_account_id', $accountIds)
            //->whereDate('created_at', Carbon::today())
            ->selectRaw('SUM(debit) as total_debit, SUM(vendor_net_amount) as total_net_amount')
            ->first();

        $customerData[] = [
            'customer' => $customer,
            'total_debit' => $sums->total_debit ?? 0,
            'total_net_amount' => $sums->total_net_amount ?? 0,
        ];
    }

    return view('adminpages.customerreport',compact('customers'), [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'customerData' => $customerData,
    ]);
}


public function customerreportsearch(Request $request)
{
    $user = Auth::user();

    $customers = customer::all();

    $from_date = $request->input('from_date');
    $to_date = $request->input('to_date');
    $selected_customer = $request->input('customer_name');

    $filtered_customers = $customers;

    if ($selected_customer) {
        $filtered_customers = $customers->where('customer_name', $selected_customer);
    }

    $customerData = [];

    foreach ($filtered_customers as $customer) {
        $accountIds = DB::table('add_accounts')
            ->where('sub_head_name', $customer->customer_name)
            ->pluck('id');

        $sumsQuery = DB::table('grn_accounts')
            ->whereIn('vendor_account_id', $accountIds);

        if ($from_date && $to_date) {
            $sumsQuery->whereBetween('created_at', [
                Carbon::parse($from_date)->startOfDay(),
                Carbon::parse($to_date)->endOfDay()
            ]);
        } else {
            $sumsQuery->whereDate('created_at', Carbon::today());
        }

        $sums = $sumsQuery->selectRaw('SUM(debit) as total_debit, SUM(vendor_net_amount) as total_net_amount')
            ->first();

        $customerData[] = [
            'customer' => $customer,
            'total_debit' => $sums->total_debit ?? 0,
            'total_net_amount' => $sums->total_net_amount ?? 0,
        ];
    }

    return view('adminpages.customerreport', [
        'customers' => $customers,
        'userName' => $user->name,
        'userEmail' => $user->email,
        'customerData' => $customerData,
    ]);
}



}
