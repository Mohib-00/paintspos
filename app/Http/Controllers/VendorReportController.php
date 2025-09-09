<?php

namespace App\Http\Controllers;

use App\Models\Vendors;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorReportController extends Controller
{
     public function vendorreport()
{
    $user = Auth::user();
    $vendors = Vendors::all();

    $vendorData = [];

    foreach ($vendors as $vendor) {
        $accountIds = DB::table('add_accounts')
            ->where('sub_head_name', $vendor->name)
            ->pluck('id');

        $sums = DB::table('grn_accounts')
            ->whereIn('vendor_account_id', $accountIds)
            //->whereDate('created_at', Carbon::today())
            ->selectRaw('SUM(debit) as total_debit, SUM(vendor_net_amount) as total_net_amount')
            ->first();

        $vendorData[] = [
            'vendor' => $vendor,
            'total_debit' => $sums->total_debit ?? 0,
            'total_net_amount' => $sums->total_net_amount ?? 0,
        ];
    }

    return view('adminpages.vendorreport',compact('vendors'), [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'vendorData' => $vendorData,
    ]);
}


public function vendorreportsearch(Request $request)
{
    $user = Auth::user();

    $vendors = Vendors::all();

    $from_date = $request->input('from_date');
    $to_date = $request->input('to_date');
    $selected_vendor = $request->input('name');

    $filtered_vendors = $vendors;

    if ($selected_vendor) {
        $filtered_vendors = $vendors->where('name', $selected_vendor);
    }

    $vendorData = [];

    foreach ($filtered_vendors as $vendor) {
        $accountIds = DB::table('add_accounts')
            ->where('sub_head_name', $vendor->name)
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

        $vendorData[] = [
            'vendor' => $vendor,
            'total_debit' => $sums->total_debit ?? 0,
            'total_net_amount' => $sums->total_net_amount ?? 0,
        ];
    }

    return view('adminpages.vendorreport', [
        'vendors' => $vendors,
        'userName' => $user->name,
        'userEmail' => $user->email,
        'vendorData' => $vendorData,
    ]);
}


}
