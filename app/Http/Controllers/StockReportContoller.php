<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockReportContoller extends Controller
{
 
public function stockreport()
{
    $user = Auth::user();
    $componys = Company::all();
    $categorys = Category::all();
    $currentDate = Carbon::now()->toDateString();
    $products = Product::all();

    $purchases = \DB::table('purchases')
        ->where('stock_status', 'complete')
        ->get();

    $products = $products->map(function ($product) use ($purchases, $currentDate) {
        $currentQuantity = 0;
        $currentPurchaseRate = 0;
        $prevQuantity = 0;
        $prevPurchaseRate = 0;
        $totalQuantity = 0;
        $totalPurchaseRate = 0;

        foreach ($purchases as $purchase) {
            $purchaseDate = Carbon::parse($purchase->created_at)->toDateString();
            $productIds = json_decode($purchase->products, true);
            $quantities = json_decode($purchase->quantity, true);
            $purchaseRates = json_decode($purchase->purchase_rate, true);

            if (is_array($productIds)) {
                foreach ($productIds as $index => $productId) {
                    if ($productId == $product->id) {
                        $quantity = (int)($quantities[$index] ?? 0);
                        $purchaseRate = (float)($purchaseRates[$index] ?? 0);

                        if ($purchaseDate === $currentDate) {
                            $currentQuantity += $quantity;
                            $currentPurchaseRate += $purchaseRate;
                        }

                        if ($purchaseDate < $currentDate) {
                            $prevQuantity += $quantity;
                            $prevPurchaseRate += $purchaseRate;
                        }

                        $totalQuantity += $quantity;
                        $totalPurchaseRate += $purchaseRate;
                    }
                }
            }
        }

        $product->quantity = $currentQuantity;
        $product->purchase_rate = $currentPurchaseRate;
        $product->prev_quantity = $prevQuantity; 
        $product->prev_purchase_rate = $prevPurchaseRate;
        $product->total_quantity = $totalQuantity;
        $product->total_purchase_rate = $totalPurchaseRate;

        return $product;
    });

    return view('adminpages.stockreport',compact('componys', 'categorys'), [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'products' => $products,
        'currentDate' => $currentDate,
    ]);
}


public function search(Request $request)
{
    $user = Auth::user();
    $componys = Company::all();
    $categorys = Category::all();

    $fromDateInput = $request->input('from_date');
    $toDateInput = $request->input('to_date');
    $vendor = $request->input('vendors');
    $itemName = $request->input('item_name');
    $categoryName = $request->input('category_name');
    $designationName = $request->input('brand_name');

    $fromDate = $fromDateInput ? Carbon::parse($fromDateInput)->startOfDay() : null;
    $toDate = $toDateInput ? Carbon::parse($toDateInput)->endOfDay() : null;

    $products = Product::query();

    if ($vendor) {
        $products->where('brand_name', $vendor);
    }

    if ($itemName) {
        $products->where('item_name', $itemName);
    }

    if ($categoryName) {
        $products->where('category_name', $categoryName);
    }

    if ($designationName) {
        $products->where('brand_name', $designationName);
    }

    $products = $products->get();

    // Purchase + Previous Purchase Data
    $products = $products->map(function ($product) use ($fromDate, $toDate) {
        $totalQuantity = 0;
        $totalPurchaseRate = 0;
        $prevQuantity = 0;
        $prevPurchaseRate = 0;

        $purchases = \DB::table('purchases')
            ->where('stock_status', 'complete')
            ->when($fromDate && $toDate, fn($query) => $query->whereBetween('created_at', [$fromDate, $toDate]))
            ->get();

        $prevPurchases = \DB::table('purchases')
            ->where('stock_status', 'complete')
            ->when($fromDate, fn($query) => $query->where('created_at', '<', $fromDate))
            ->get();

        foreach ($purchases as $purchase) {
            $productIds = json_decode($purchase->products, true);
            $quantities = json_decode($purchase->quantity, true);
            $purchaseRates = json_decode($purchase->purchase_rate, true);

            if (is_array($productIds)) {
                foreach ($productIds as $index => $productId) {
                    if ($productId == $product->id) {
                        $quantity = (int)($quantities[$index] ?? 0);
                        $purchaseRate = (float)($purchaseRates[$index] ?? 0);

                        $totalQuantity += $quantity;
                        $totalPurchaseRate += $purchaseRate;
                    }
                }
            }
        }

        foreach ($prevPurchases as $purchase) {
            $productIds = json_decode($purchase->products, true);
            $quantities = json_decode($purchase->quantity, true);
            $purchaseRates = json_decode($purchase->purchase_rate, true);

            if (is_array($productIds)) {
                foreach ($productIds as $index => $productId) {
                    if ($productId == $product->id) {
                        $quantity = (int)($quantities[$index] ?? 0);
                        $purchaseRate = (float)($purchaseRates[$index] ?? 0);

                        $prevQuantity += $quantity;
                        $prevPurchaseRate += $purchaseRate;
                    }
                }
            }
        }

        $product->quantity = $totalQuantity;
        $product->purchase_rate = $totalPurchaseRate;
        $product->prev_quantity = $prevQuantity;
        $product->prev_purchase_rate = $prevPurchaseRate;

        return $product;
    });

    $currentDate = Carbon::now()->toDateString();

    return view('adminpages.stockreport', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'products' => $products,
        'currentDate' => $currentDate,
        'componys' => $componys,
        'categorys' => $categorys,
    ]);
}



}
