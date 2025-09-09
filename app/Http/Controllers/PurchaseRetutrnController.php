<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\GrnAccount;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SalePurchaseTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRetutrnController extends Controller
{
     public function purchasereturn(){ 
        $user = Auth::user();      
        $purchases = Purchase::where('stock_status', 'complete')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('adminpages.purchasereturn', ['userName' => $user->name,'userEmail' => $user->email],compact('purchases'));
    }

      public function getPurchasereturnDetails($id)
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
            'discount' => $purchase->discount,
            'products' => $purchase->products,
            'quantity' => $purchase->quantity,
            'retail_rate' => $purchase->retail_rate,
            'purchase_rate' => $purchase->purchase_rate,
            'single_retail_rate' => $purchase->single_retail_rate,
            'single_purchase_rate' => $purchase->single_purchase_rate,
            'product_names' => $productNamesArray,
            'stock_status' => $purchase->stock_status,
            'payment_status' => $purchase->payment_status,
            'return_quantity' => $purchase->return_quantity,
            'amount_payed_return' => $purchase->amount_payed_return,
        ]);
    }


public function saveReturnQuantities(Request $request, $id)
{

    $customCreatedAt = $request->input('created_at');
    $amountPayedReturn = $request->input('amount_payed_return');

    if ($amountPayedReturn !== null) {
    Purchase::where('id', $id)->update([
        'amount_payed_return' => $amountPayedReturn,
        'created_at' => $customCreatedAt,
        'updated_at' => $customCreatedAt
    ]);
    }



    $purchase = Purchase::find($id);

    if (!$purchase) {
        return response()->json(['error' => 'Purchase not found'], 404);
    }

    $newReturnQuantities = $request->input('return_quantity');
    $singlePurchaseRates = $request->input('single_purchase_rate'); 
    $singleRetailRates   = $request->input('single_retail_rate');  
    $productIds          = $request->input('product_ids');

    if (
        !is_array($newReturnQuantities) || 
        !is_array($singlePurchaseRates) || 
        !is_array($singleRetailRates) || 
        !is_array($productIds)
    ) {
        return response()->json(['error' => 'Invalid input data'], 400);
    }

    $originalQuantities  = json_decode($purchase->quantity, true); 
    $oldReturnQuantities = json_decode($purchase->return_quantity, true);

    $count = count($originalQuantities);

    if (!is_array($oldReturnQuantities) || count($oldReturnQuantities) !== $count) {
        $oldReturnQuantities = array_fill(0, $count, 0); 
    }

    if (
        count($newReturnQuantities) !== $count ||
        count($singlePurchaseRates) !== $count ||
        count($singleRetailRates) !== $count ||
        count($productIds) !== $count
    ) {
        return response()->json(['error' => 'Mismatch in count of items'], 422);
    }

    $updatedQuantities       = [];
    $updatedPurchaseRates    = [];
    $updatedRetailRates      = [];
    $updatedReturnQuantities = [];

    for ($i = 0; $i < $count; $i++) {
        $originalQty = (int) $originalQuantities[$i];
        $oldReturn   = (int) $oldReturnQuantities[$i];
        $newReturn   = (int) $newReturnQuantities[$i];

        if ($newReturn > $originalQty + $oldReturn) {
            return response()->json(['error' => "Return quantity for item $i exceeds total available quantity"], 400);
        }

        $unitPurchaseRate = (float) $singlePurchaseRates[$i];
        $unitRetailRate   = (float) $singleRetailRates[$i];

        $productId = $productIds[$i];
        $product   = Product::find($productId);

        $returnDifference = $newReturn - $oldReturn;

        $newQty = max(0, $originalQty - $returnDifference);
        $originalQuantities[$i] = $newQty;

        $newTotalPurchaseRate = $newQty * $unitPurchaseRate;
        $newTotalRetailRate   = $newQty * $unitRetailRate;

        $updatedQuantities[]        = $newQty;
        $updatedReturnQuantities[]  = $newReturn;
        $updatedPurchaseRates[]     = $newTotalPurchaseRate;
        $updatedRetailRates[]       = $newTotalRetailRate;

        if ($product) {
            $currentStock        = (int) $product->quantity;
            $currentPurchaseRate = (float) $product->purchase_rate;
            $currentRetailRate   = (float) $product->retail_rate;

            if ($returnDifference > 0) {
                $product->quantity      = max(0, $currentStock - $returnDifference);
                $product->purchase_rate = max(0, $currentPurchaseRate - ($returnDifference * $unitPurchaseRate));
                $product->retail_rate   = max(0, $currentRetailRate - ($returnDifference * $unitRetailRate));
            } elseif ($returnDifference < 0) {
                $adjust = abs($returnDifference);
                $product->quantity      = $currentStock + $adjust;
                $product->purchase_rate = $currentPurchaseRate + ($adjust * $unitPurchaseRate);
                $product->retail_rate   = $currentRetailRate + ($adjust * $unitRetailRate);
            }

            

            $product->save();
        }
    }

    $purchase->timestamps = false;
    $purchase->quantity        = json_encode($originalQuantities);
    $purchase->return_quantity = json_encode($updatedReturnQuantities);
    $purchase->purchase_rate   = json_encode($updatedPurchaseRates);
    $purchase->retail_rate     = json_encode($updatedRetailRates);
    $purchase->totalquantity   = array_sum($originalQuantities);
    

    $purchase->save();

 $paymentMethod = $request->input('payment_status');
 
 $netAmountReturn = $request->input('net_amount');
    if ($paymentMethod == 'pending') {
    $vendorName = $request->input('vendors');
    $vendorAccount = AddAccount::where('sub_head_name', $vendorName)->first();

    $inventoryReturn = $request->input('gross_amount');
    $inventoryAccount = AddAccount::where('sub_head_name', 'Inventory')->first();

    $DiscountAccount = AddAccount::where('sub_head_name', 'Discount Availed')->first();
 
      if ($vendorAccount) {
        GrnAccount::where('purchase_id', $purchase->id)
            ->where('purchasereturn', 'purchasereturn')
            ->where('vendor_account_id', $vendorAccount->id)
            ->delete();

        GrnAccount::create([
            'vendor_account_id' => $vendorAccount->id,
            'purchase_id' => $purchase->id,
            'debit' => $netAmountReturn ?? 0,
            'purchasereturn' => 'purchasereturn',
            'created_at' => $customCreatedAt,
            'updated_at' => $customCreatedAt
        ]);
    }

    if ($inventoryAccount) {
        GrnAccount::where('purchase_id', $purchase->id)
            ->where('purchasereturn', 'purchasereturn')
            ->where('vendor_account_id', $inventoryAccount->id)
            ->delete();

        GrnAccount::create([
            'vendor_account_id' => $inventoryAccount->id,
            'purchase_id' => $purchase->id,
            'vendor_net_amount' => $inventoryReturn ?? 0,
            'purchasereturn' => 'purchasereturn',
            'created_at' => $customCreatedAt,
            'updated_at' => $customCreatedAt
        ]);
    }

$totalDiscount = 0;
if (!empty($purchase->discount)) {
    $totalDiscount += $purchase->discount;
}

$productQuantities = $request->input('quantity', []);
$returnQuantities = $request->input('return_quantity', []);

$totalOriginalQty = array_sum($productQuantities);

$proportionalDiscount = 0;

if ($totalOriginalQty > 0 && $totalDiscount > 0) {
    $discountPerUnit = $totalDiscount / $totalOriginalQty;

    foreach ($returnQuantities as $index => $returnQty) {
        $returnQty = isset($returnQty) ? (float)$returnQty : 0;

        $originalQty = isset($productQuantities[$index]) ? (float)$productQuantities[$index] : 0;
        $returnQty = min($returnQty, $originalQty);

        if ($returnQty > 0 && $originalQty > 0) {
            $proportionalDiscount += round($returnQty * $discountPerUnit, 2);
        }
    }
}

if ($DiscountAccount) {
    GrnAccount::where('purchase_id', $purchase->id)
        ->where('purchasereturn', 'purchasereturn')
        ->where('vendor_account_id', $DiscountAccount->id)
        ->delete();

    GrnAccount::create([
        'vendor_account_id' => $DiscountAccount->id,
        'purchase_id' => $purchase->id,
        'vendor_net_amount' => $proportionalDiscount,
        'purchasereturn' => 'purchasereturn',
        'created_at' => $customCreatedAt,
        'updated_at' => $customCreatedAt
    ]);
}

 

} elseif ($paymentMethod === 'complete') {

    $inventoryReturn = $request->input('gross_amount');
    $inventoryAccount = AddAccount::where('sub_head_name', 'Inventory')->first();
    $DiscountAccount = AddAccount::where('sub_head_name', 'Discount Availed')->first();

    $paymentType = $request->input('payment_method'); 
    
    $amountpayed = $request->input('amount_payed');
    if ($paymentType === 'cash') {
        $cashAccount = AddAccount::where('sub_head_name', 'Cash In Hand')->first();

        if ($cashAccount) {
            GrnAccount::where('purchase_id', $purchase->id)
                ->where('purchasereturn', 'purchasereturn')
                ->where('vendor_account_id', $cashAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $cashAccount->id,
                'purchase_id'       => $purchase->id,
                'debit'             => $amountPayedReturn ?? 0,
                'purchasereturn'    => 'purchasereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt
            ]);
        }

    } elseif ($paymentType === 'bank') {
        $bankAccount = AddAccount::where('sub_head_name', 'Cash At Bank')->first();

        if ($bankAccount) {
            GrnAccount::where('purchase_id', $purchase->id)
                ->where('purchasereturn', 'purchasereturn')
                ->where('vendor_account_id', $bankAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $bankAccount->id,
                'purchase_id'       => $purchase->id,
                'debit'             => $amountPayedReturn ?? 0,
                'purchasereturn'    => 'purchasereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt
            ]);
        }
    }

    if ($inventoryAccount) {
        GrnAccount::where('purchase_id', $purchase->id)
            ->where('purchasereturn', 'purchasereturn')
            ->where('vendor_account_id', $inventoryAccount->id)
            ->delete();

        GrnAccount::create([
            'vendor_account_id' => $inventoryAccount->id,
            'purchase_id' => $purchase->id,
            'vendor_net_amount' => $inventoryReturn ?? 0,
            'purchasereturn' => 'purchasereturn',
            'created_at' => $customCreatedAt,
            'updated_at' => $customCreatedAt
        ]);
    }

    
$totalDiscount = 0;
if (!empty($purchase->discount)) {
    $totalDiscount += $purchase->discount;
}

$productQuantities = $request->input('quantity', []);
$returnQuantities = $request->input('return_quantity', []);

$totalOriginalQty = array_sum($productQuantities);

$proportionalDiscount = 0;

if ($totalOriginalQty > 0 && $totalDiscount > 0) {
    $discountPerUnit = $totalDiscount / $totalOriginalQty;

    foreach ($returnQuantities as $index => $returnQty) {
        $returnQty = isset($returnQty) ? (float)$returnQty : 0;

        $originalQty = isset($productQuantities[$index]) ? (float)$productQuantities[$index] : 0;
        $returnQty = min($returnQty, $originalQty);

        if ($returnQty > 0 && $originalQty > 0) {
            $proportionalDiscount += round($returnQty * $discountPerUnit, 2);
        }
    }
}

if ($DiscountAccount) {
    GrnAccount::where('purchase_id', $purchase->id)
        ->where('purchasereturn', 'purchasereturn')
        ->where('vendor_account_id', $DiscountAccount->id)
        ->delete();

    GrnAccount::create([
        'vendor_account_id' => $DiscountAccount->id,
        'purchase_id' => $purchase->id,
        'vendor_net_amount' => $proportionalDiscount,
        'purchasereturn' => 'purchasereturn',
        'created_at' => $customCreatedAt,
        'updated_at' => $customCreatedAt
    ]);
}

}


    return response()->json([
        'success' => true,
        'message' => 'Return quantities updated successfully',
        'updated_quantity'      => $originalQuantities,
        'updated_purchase_rate' => $updatedPurchaseRates,
        'updated_retail_rate'   => $updatedRetailRates,
        'total_quantity'        => $purchase->totalquantity,
        'total_return_quantity' => $updatedReturnQuantities,
    ]);
}



}
