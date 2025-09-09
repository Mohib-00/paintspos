<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleReportController extends Controller
{
    public function salereport(){ 
        $user = Auth::user();      
        $currentDate = Carbon::today();
        $sales = Sale::with(['user', 'saleItems'])
                 ->whereDate('created_at', $currentDate)
                 ->get();
        $saless = Sale::with(['saleItems', 'user'])->get();         
        return view('adminpages.salereport', ['userName' => $user->name,'userEmail' => $user->email],compact('sales','saless'));
    }



   public function searchSales(Request $request)
{
    $user = Auth::user();

    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $customerName = $request->input('customer_name');
    $userId = $request->input('employee');

    $sales = Sale::with(['user', 'saleItems'])
        ->when($fromDate, function ($query, $fromDate) {
            return $query->whereDate('created_at', '>=', $fromDate);
        })
        ->when($toDate, function ($query, $toDate) {
            return $query->whereDate('created_at', '<=', $toDate);
        })
        ->when($customerName, function ($query, $customerName) {
            return $query->where('customer_name', $customerName);
        })
        ->when($userId, function ($query, $userId) {
            return $query->where('employee', $userId);
        })
        ->get();

    $saless = Sale::with(['saleItems', 'user'])->get();    

    return view('adminpages.salereport', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'sales' => $sales,
        'saless' =>$saless
    ]);
}

public function saleitemsdetail($id)
{
    $user = Auth::user();
    $sale = Sale::with('saleItems')->findOrFail($id);
    return view('adminpages.saleitemsdetails',['userName' => $user->name,'userEmail' => $user->email], compact('sale'));
}

}
