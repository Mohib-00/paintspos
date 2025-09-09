<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\GrnAccount;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class grnController extends Controller
{
    public function openGRN(){ 
        $user = Auth::user();      
        $purchases = Purchase::where('stock_status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('adminpages.grn', ['userName' => $user->name,'userEmail' => $user->email],compact('purchases'));
    }

    public function getPurchaseDetails($id)
    {
        $purchase = Purchase::find($id);
    
        if (!$purchase) {
            return response()->json(['error' => 'Purchase not found'], 404);
        }
    
        $productIds = json_decode($purchase->products, true);
    
        $productNames = Product::whereIn('id', $productIds)->pluck('item_name', 'id');
    
        $productNamesArray = $productNames->toArray();
    
        return response()->json([
            'id' => $purchase->id,
            'vendors' => $purchase->vendors,
            'invoice_no' => $purchase->invoice_no,
            'receiving_location' => $purchase->receiving_location,
            'created_at' => $purchase->created_at,
            'remarks' => $purchase->remarks,
            'totalquantity' => $purchase->totalquantity,
            'gross_amount' => $purchase->gross_amount,
            'discount' => $purchase->discount,
            'net_amount' => $purchase->net_amount,
            'products' => $purchase->products,
            'quantity' => $purchase->quantity,
            'retail_rate' => $purchase->retail_rate,
            'purchase_rate' => $purchase->purchase_rate,
            'single_retail_rate' => $purchase->single_retail_rate,
            'single_purchase_rate' => $purchase->single_purchase_rate,
            'product_names' => $productNamesArray,
        ]);
    }

   

    public function updatePurchaseStock(Request $request)
    {
        $purchaseId = $request->input('purchase_id');
        $productIds = $request->input('products');
        $quantities = $request->input('quantity');
        $purchaseRates = $request->input('purchase_rate');
        $retailRates = $request->input('retail_rate');
        $UPRs = $request->input('single_purchase_rate');
        $URRs = $request->input('single_retail_rate');
        $netAmount = $request->input('net_amount', 0);
        $discount = $request->input('discount', 0);
        $grossAmount = $request->input('gross_amount', 0);
        $createdAt = $request->input('created_at');
    
        $purchase = Purchase::find($purchaseId);
        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found.'], 404);
        }

        $purchase->timestamps = false;
    
        $purchase->stock_status = 'complete';
        $purchase->created_at = $createdAt;
        $purchase->updated_at = $createdAt;
        $purchase->save();
    
foreach ($productIds as $index => $productId) {
    $product = Product::find($productId);

    if ($product) {
        $newQuantity = (int)$quantities[$index];
        $newPurchaseRate = (float)$purchaseRates[$index];
        $newRetailRate = (float)$retailRates[$index];

        if ($product->quantity == 0) {
            $product->quantity = $newQuantity;
            $product->purchase_rate = $newPurchaseRate;
            $product->retail_rate = $newRetailRate;

            $product->single_purchase_rate = $UPRs[$index] ?? 0;
            $product->single_retail_rate = $URRs[$index] ?? 0;
        } else {
            $product->quantity += $newQuantity;
            $product->purchase_rate += $newPurchaseRate;
            $product->retail_rate += $newRetailRate;

            $existingQty = $product->quantity - $newQuantity;
            $oldSinglePurchase = (float) $product->single_purchase_rate;
            $oldSingleRetail = (float) $product->single_retail_rate;
            $newSinglePurchase = (float) ($UPRs[$index] ?? 0);
            $newSingleRetail = (float) ($URRs[$index] ?? 0);

            if ($existingQty > 0) {
                $avgSinglePurchase = (($oldSinglePurchase * $existingQty) + ($newSinglePurchase * $newQuantity)) / ($existingQty + $newQuantity);
                $avgSingleRetail = (($oldSingleRetail * $existingQty) + ($newSingleRetail * $newQuantity)) / ($existingQty + $newQuantity);

                $product->single_purchase_rate = round($avgSinglePurchase, 2);
                $product->single_retail_rate = round($avgSingleRetail, 2);
            } else {
                $product->single_purchase_rate = $newSinglePurchase;
                $product->single_retail_rate = $newSingleRetail;
            }
        }

        $product->save();

          \DB::table('deal_items')
            ->where('products', $product->item_name)
            ->update([
                'single_purchase_rate' => $product->single_purchase_rate,
                'single_retail_rate' => $product->single_retail_rate,
                'updated_at' => now(),
            ]);

    }
}

        $vendorAccount = AddAccount::where('sub_head_name', $purchase->vendors)->first();
    
        if (!$vendorAccount) {
            return response()->json(['message' => 'Vendor not found in add_accounts.'], 404);
        }
    
        GrnAccount::create([
            'vendor_account_id' => $vendorAccount->id,
            'vendor_net_amount' => $netAmount,
            'purchase_id' => $purchase->id,
            'grn' => 'grn',
            'created_at' => $purchase->created_at,
            'updated_at' => $purchase->created_at,
        ]);
    
        if ($discount > 0) {
            $discountAccount = AddAccount::where('sub_head_name', 'Discount Availed')->first();
    
            if (!$discountAccount) {
                return response()->json(['message' => 'Discount Availed account not found.'], 404);
            }
    
            GrnAccount::create([
                'vendor_account_id' => $discountAccount->id,
                'discount' => $discount,
                'vendor_net_amount' => $discount,
                'purchase_id' => $purchase->id,
                'grn' => 'grn',
                'created_at' => $purchase->created_at,
                'updated_at' => $purchase->created_at,
            ]);
        }
    
        $inventoryAccount = AddAccount::where('sub_head_name', 'Inventory')->first();
    
        if ($inventoryAccount) {
            GrnAccount::create([
                'vendor_account_id' => $inventoryAccount->id,
                'debit' => $grossAmount,
                'purchase_id' => $purchase->id,
                'grn' => 'grn',
                'created_at' => $purchase->created_at,
                'updated_at' => $purchase->created_at,
            ]);
        }
    
        return response()->json(['message' => 'Purchase, products, and GRN account updated successfully.']);
    }
    
}
