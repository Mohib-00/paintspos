<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseReportController extends Controller
{
public function purchasereport()
{ 
    $user = Auth::user();      
    $currentDate = Carbon::today();

    $purchases = Purchase::whereDate('created_at', $currentDate)->get();
    $purchasesvendors = Purchase::all()->unique('vendors');


    $allProductIds = Purchase::all()->flatMap(function ($purchase) {
        return json_decode($purchase->products, true) ?? [];
    })->unique()->values();

    $products = Product::whereIn('id', $allProductIds)->get();

    return view('adminpages.purchasereport', [
        'userName'  => $user->name,
        'userEmail' => $user->email,
        'purchases' => $purchases,
        'products'  => $products,
        'purchasesvendors' => $purchasesvendors
    ]);
}

public function searchpurchasereportsssssss(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'from_date'  => 'nullable|date',
        'to_date'    => 'nullable|date|after_or_equal:from_date',
        'product_id' => 'nullable|integer',
        'vendors'    => 'nullable|integer'
    ]);

    $query = Purchase::query();

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $from = Carbon::parse($request->from_date)->startOfDay();
        $to   = Carbon::parse($request->to_date)->endOfDay();
        $query->whereBetween('created_at', [$from, $to]);
    }

    if ($request->filled('product_id')) {
        $query->whereJsonContains('products', (string)$request->product_id);
    }

    if ($request->filled('vendors')) {
        $vendorName = Purchase::find($request->vendors)->vendors ?? null;
        if ($vendorName) {
            $query->where('vendors', $vendorName);
        }
    }

    $purchases = $query->get();

    if ($request->filled('product_id')) {
        $products = Product::where('id', $request->product_id)->get();
    } else {
        $productIds = $purchases->flatMap(function ($purchase) {
            return json_decode($purchase->products, true) ?? [];
        })->unique()->values();

        $products = Product::whereIn('id', $productIds)->get();
    }

    $purchasesvendors = Purchase::all()->unique('vendors');

    return view('adminpages.purchasereport', [
        'userName'         => $user->name,
        'userEmail'        => $user->email,
        'purchases'        => $purchases,
        'products'         => $products,
        'purchasesvendors' => $purchasesvendors
    ]);
}


}
