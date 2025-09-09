<?php
/*
namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\customer;
use App\Models\Deal;
use App\Models\GrnAccount;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePurchaseLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleReturnController extends Controller
{
   public function salereturn($id)
{
    $sale = Sale::with(['saleItems.dealSaleItems'])->findOrFail($id); 
    $users = User::all();
    $customers = Customer::all();
    $products = Product::all()->keyBy('item_name'); 

    foreach ($sale->saleItems as $item) {
        $matchedProduct = $products[$item->product_name] ?? null;
        $item->single_purchase_rate = $matchedProduct ? $matchedProduct->single_purchase_rate : null;
    }

    $user = Auth::user();
    $deals = Deal::all();

    return view(
        'adminpages.salereturn',
        [
            'userName' => $user->name,
            'userEmail' => $user->email,
            'users' => $users,
            'customers' => $customers,
            'products' => $products,
            'sale' => $sale,
            'deals' => $deals
        ]
    );
}



public function processSaleReturn(Request $request, $sale_id)
{
    $customCreatedAt = $request->input('created_at'); 
    $amountPayed = $request->input('sale_return');
    if ($amountPayed !== null) {
    Sale::where('id', $sale_id)->update([
        'sale_return' => $amountPayed,
        'created_at' => $customCreatedAt,
        'updated_at' => $customCreatedAt,
    ]);
}

   $amountPayedReturn = $request->input('amount_payed_return');
   if ($amountPayedReturn !== null) {
    Sale::where('id', $sale_id)->update([
        'amount_payed_return' => $amountPayedReturn,
        'created_at' => $customCreatedAt,
        'updated_at' => $customCreatedAt,
    ]);
}

    

$productNames = $request->input('product_name', []);
$returnQuantities = $request->input('return_quantity', []);
$singlePurchaseRates = $request->input('single_purchase_rate', []);
$productRates = $request->input('product_rate', []);
$returnAmounts = $request->input('return_amount', []);
$itemTypes = $request->input('item_type', []);

foreach ($productNames as $index => $productName) {
    $newReturnQty = (float) ($returnQuantities[$index] ?? 0);
    $singlePurchaseRate = (float) ($singlePurchaseRates[$index] ?? 0);
    $productRate = (float) ($productRates[$index] ?? 0);
    $returnAmount = (float) ($returnAmounts[$index] ?? 0);
     $itemType = $itemTypes[$index] ?? 'normal';
    $isDealItem = $itemType === 'deal';

    $saleItem = SaleItem::where('sale_id', $sale_id)
        ->where('product_name', $productName)
        ->first();

    if (!$saleItem) continue;

       if ($isDealItem) {
        $saleItem->return_qty = $newReturnQty;
        $saleItem->return_amount = $returnAmount;
        $saleItem->timestamps = false;
        $saleItem->save();
        continue; 
    }

    $oldReturnQty = (float) $saleItem->return_qty;
    $difference = $newReturnQty - $oldReturnQty;

    $product = Product::where('item_name', $productName)->first();
    if (!$product) continue;

    $productId = $product->id;
    $links = SalePurchaseLink::where('sale_id', $sale_id)
                ->where('sale_item_id', $saleItem->id)
                ->get();

    if ($difference > 0) {
        $product->quantity += $difference;
        $product->purchase_rate += $singlePurchaseRate * $difference;
        $product->retail_rate += $productRate * $difference;
        $product->save();

        foreach ($links as $link) {
            $purchase = Purchase::find($link->purchase_id);
            if (!$purchase) continue;

            $productsArray = json_decode($purchase->products, true);
            $quantityArray = json_decode($purchase->quantity, true);
            $purchaseRateArray = json_decode($purchase->purchase_rate, true);
            $retailRateArray = json_decode($purchase->retail_rate, true);

            $productIndex = array_search((string) $productId, array_map('strval', $productsArray));
            if ($productIndex !== false) {
                $restoreQty = min($difference, abs($link->deducted_quantity));
                $rate = $link->deducted_purchase_rate;

                $quantityArray[$productIndex] += $restoreQty;
                $purchaseRateArray[$productIndex] += $rate * $restoreQty;
                $retailRateArray[$productIndex] += $rate * $restoreQty;

                $purchase->timestamps = false;
                $purchase->quantity = json_encode($quantityArray);
                $purchase->purchase_rate = json_encode($purchaseRateArray);
                $purchase->retail_rate = json_encode($retailRateArray);
                $purchase->save();

                $link->deducted_quantity -= $restoreQty;
                $link->save();

                $difference -= $restoreQty;
                if ($difference <= 0) break;
            }
        }
    } elseif ($difference < 0) {
        $absDiff = abs($difference);
        $product->quantity -= $absDiff;
        $product->purchase_rate -= $singlePurchaseRate * $absDiff;
        $product->retail_rate -= $productRate * $absDiff;
        $product->save();

        $quantityToDeduct = $absDiff;
        $purchases = Purchase::orderBy('created_at')->get();

        foreach ($purchases as $purchase) {
            $productsArray = json_decode($purchase->products, true);
            $quantityArray = json_decode($purchase->quantity, true);
            $purchaseRateArray = json_decode($purchase->purchase_rate, true);

            $productIndex = array_search((string) $productId, array_map('strval', $productsArray));
            if ($productIndex !== false && $quantityToDeduct > 0) {
                $availableQty = $quantityArray[$productIndex] ?? 0;
                if ($availableQty <= 0) continue;

                $deductQty = min($availableQty, $quantityToDeduct);
                $quantityArray[$productIndex] -= $deductQty;
                $purchaseRateArray[$productIndex] -= ($deductQty * $singlePurchaseRate);

                $purchase->timestamps = false;
                $purchase->quantity = json_encode($quantityArray);
                $purchase->purchase_rate = json_encode($purchaseRateArray);
                $purchase->save();

                SalePurchaseLink::create([
                    'sale_id' => $sale_id,
                    'sale_item_id' => $saleItem->id,
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'deducted_quantity' => $deductQty,
                    'deducted_purchase_rate' => $singlePurchaseRate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $quantityToDeduct -= $deductQty;
                if ($quantityToDeduct <= 0) break;
            }
        }
    }

    $saleItem->timestamps = false;
    $saleItem->return_qty = $newReturnQty;
    $saleItem->return_amount = $returnAmount;
    $saleItem->created_at = $customCreatedAt;
    $saleItem->updated_at = $customCreatedAt;
    $saleItem->save();
}



$dealSaleItemIds = $request->input('deal_sale_item_id', []);
$dealReturnQtys = $request->input('return_qty', []);
$dealProductNames = $request->input('deal_product_name', []);
$dealProductRetailRates = $request->input('deal_product_retail_rate', []);
$dealProductpurchaseRates = $request->input('deal_product_purchase_rate', []);

foreach ($dealSaleItemIds as $index => $dealSaleItemId) {
    $newReturnQty = (float) ($dealReturnQtys[$index] ?? 0);
    $retailRate = (float) ($dealProductRetailRates[$index] ?? 0);
    $purchaseRate = (float) ($dealProductpurchaseRates[$index] ?? 0);
    $productName = $dealProductNames[$index] ?? null;

    if (!$productName) continue;

    $dealSaleItem = \App\Models\DealSaleItem::find($dealSaleItemId);
    if (!$dealSaleItem) continue;

    $oldReturnQty = (float) $dealSaleItem->return_qty ?? 0;
    $difference = $newReturnQty - $oldReturnQty;

    if ($difference != 0) {
        $product = Product::where('item_name', $productName)->first();
        if (!$product) continue;

        $productId = $product->id;
        $saleId = $dealSaleItem->sale_id;
        $saleItemId = $dealSaleItem->sale_item_id;

        if ($difference > 0) {
            $product->quantity += $difference;
            $product->purchase_rate += $purchaseRate * $difference;
            $product->retail_rate += $retailRate * $difference;
            $product->save();

            $links = SalePurchaseLink::where('sale_id', $saleId)
                ->where('sale_item_id', $saleItemId)
                ->where('product_id', $productId)
                ->where('deducted_quantity', '>', 0)
                ->orderBy('created_at')
                ->get();

            foreach ($links as $link) {
                $purchase = Purchase::find($link->purchase_id);
                if (!$purchase) continue;

                $productsArray = json_decode($purchase->products, true);
                $quantityArray = json_decode($purchase->quantity, true);
                $purchaseRateArray = json_decode($purchase->purchase_rate, true);
                $retailRateArray = json_decode($purchase->retail_rate, true);

                $productIndex = array_search((string) $productId, array_map('strval', $productsArray));
                if ($productIndex === false) continue;

                $restoreQty = min($difference, $link->deducted_quantity);
                $rate = $link->deducted_purchase_rate;

                $quantityArray[$productIndex] += $restoreQty;
                $purchaseRateArray[$productIndex] += $rate * $restoreQty;
                $retailRateArray[$productIndex] += $retailRate * $restoreQty;

                $purchase->timestamps = false;
                $purchase->quantity = json_encode($quantityArray);
                $purchase->purchase_rate = json_encode($purchaseRateArray);
                $purchase->retail_rate = json_encode($retailRateArray);
                $purchase->save();

                $link->deducted_quantity -= $restoreQty;
                $link->save();

                $difference -= $restoreQty;
                if ($difference <= 0) break;
            }

            if ($difference > 0) {
                $purchases = Purchase::orderBy('created_at')->get();

                foreach ($purchases as $purchase) {
                    if ($difference <= 0) break;

                    $productsArray = json_decode($purchase->products, true);
                    $quantityArray = json_decode($purchase->quantity, true);
                    $purchaseRateArray = json_decode($purchase->purchase_rate, true);
                    $retailRateArray = json_decode($purchase->retail_rate, true);

                    $productIndex = array_search((string) $productId, array_map('strval', $productsArray));
                    if ($productIndex === false) continue;

                    $quantityArray[$productIndex] += $difference;
                    $purchaseRateArray[$productIndex] += $purchaseRate * $difference;
                    $retailRateArray[$productIndex] += $retailRate * $difference;

                    $purchase->timestamps = false;
                    $purchase->quantity = json_encode($quantityArray);
                    $purchase->purchase_rate = json_encode($purchaseRateArray);
                    $purchase->retail_rate = json_encode($retailRateArray);
                    $purchase->save();

                    $difference = 0;
                    break;
                }
            }

        } else {
            $absDiff = abs($difference);

            $product->quantity -= $absDiff;
            $product->purchase_rate -= $purchaseRate * $absDiff;
            $product->retail_rate -= $retailRate * $absDiff;
            $product->save();

            $quantityToDeduct = $absDiff;
            $purchases = Purchase::orderBy('created_at')->get();

            foreach ($purchases as $purchase) {
                $productsArray = json_decode($purchase->products, true);
                $quantityArray = json_decode($purchase->quantity, true);
                $purchaseRateArray = json_decode($purchase->purchase_rate, true);

                $productIndex = array_search((string) $productId, array_map('strval', $productsArray));
                if ($productIndex === false || $quantityToDeduct <= 0) continue;

                $availableQty = $quantityArray[$productIndex] ?? 0;
                if ($availableQty <= 0) continue;

                $deductQty = min($availableQty, $quantityToDeduct);
                $quantityArray[$productIndex] -= $deductQty;
                $purchaseRateArray[$productIndex] -= $purchaseRate * $deductQty;

                $purchase->timestamps = false;
                $purchase->quantity = json_encode($quantityArray);
                $purchase->purchase_rate = json_encode($purchaseRateArray);
                $purchase->save();

                SalePurchaseLink::create([
                    'sale_id' => $saleId,
                    'sale_item_id' => $saleItemId,
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'deducted_quantity' => $deductQty,
                    'deducted_purchase_rate' => $purchaseRate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $quantityToDeduct -= $deductQty;
                if ($quantityToDeduct <= 0) break;
            }
        }

        $dealSaleItem->timestamps = false;
        $dealSaleItem->return_qty = $newReturnQty;
        $dealSaleItem->return_amount = round($retailRate * $newReturnQty, 2);
        $dealSaleItem->created_at = $customCreatedAt;
        $dealSaleItem->updated_at = $customCreatedAt;
        $dealSaleItem->save();
    }
}



    $sale = Sale::find($sale_id);
    $saleType = $request->input('sale_type');

    $purchaseRates = $request->input('single_purchase_rate', []);
$returnQuantities = $request->input('return_quantity', []);
$itemTypes = $request->input('item_type', []);

$totalPurchaseRate = 0;

foreach ($purchaseRates as $index => $rate) {
    $returnQty = isset($returnQuantities[$index]) ? (float)$returnQuantities[$index] : 0;
    $itemType = $itemTypes[$index] ?? 'normal';

    if ($returnQty > 0) {
        if ($itemType === 'deal') {
            $totalPurchaseRate += (float)$rate; 
        } else {
            $totalPurchaseRate += (float)$rate * $returnQty;
        }
    }
}


    if ($saleType == 'cash') {
        $cashSalesAccount = AddAccount::where('sub_head_name', 'Cash Sales')->first();
        $cashinhandAccount = AddAccount::where('sub_head_name', 'Cash In Hand')->first();
        $InventoryAccount = AddAccount::where('sub_head_name', 'Inventory')->first();
        $CostGoodsSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        $DiscountAccount = AddAccount::where('sub_head_name', 'Discount Given')->first();
        $SaleReturn = AddAccount::where('sub_head_name', 'Sales Return')->first();

        if ($cashSalesAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $cashSalesAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $sale_id,
                'debit' => $amountPayed ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($SaleReturn) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $SaleReturn->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $SaleReturn->id,
                'sale_id' => $sale_id,
                'debit' => $amountPayedReturn ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($cashinhandAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $cashinhandAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $cashinhandAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $amountPayedReturn ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($InventoryAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $InventoryAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $InventoryAccount->id,
                'sale_id' => $sale_id,
                'debit' => $totalPurchaseRate,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($CostGoodsSoldAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $CostGoodsSoldAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $CostGoodsSoldAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $totalPurchaseRate,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        $totalDiscount = 0;
        if (!empty($sale->discount)) {
            $totalDiscount += $sale->discount;
        }
        if (!empty($sale->fixed_discount)) {
            $totalDiscount += $sale->fixed_discount;
        }

        $productQuantities = $request->input('product_quantity', []);
        $returnQuantities = $request->input('return_quantity', []);

        $totalOriginalQty = array_sum($productQuantities);

        $proportionalDiscount = 0;

        if ($totalOriginalQty > 0) {
            $discountPerUnit = $totalDiscount / $totalOriginalQty;

            foreach ($returnQuantities as $index => $returnQty) {
                $returnQty = (float)$returnQty;
                if ($returnQty > 0) {
                    $proportionalDiscount += round($returnQty * $discountPerUnit, 2);
                }
            }
        }

        if ($DiscountAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $DiscountAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $DiscountAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $proportionalDiscount,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }
    } elseif ($saleType == 'credit') {
        $creditSalesAccount = AddAccount::where('sub_head_name', 'Credit Sales')->first();

        $customerName = $request->input('customer_name');
        $customerAccount = AddAccount::where('sub_head_name', $customerName)->first();

        $InventoryAccount = AddAccount::where('sub_head_name', 'Inventory')->first();
        $CostGoodsSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        $DiscountAccount = AddAccount::where('sub_head_name', 'Discount Given')->first();
        $SaleReturn = AddAccount::where('sub_head_name', 'Sales Return')->first();

        if ($creditSalesAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $creditSalesAccount->id,
                'sale_id' => $sale_id,
                'debit' => $amountPayed ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($SaleReturn) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $SaleReturn->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $SaleReturn->id,
                'sale_id' => $sale_id,
                'debit' => $amountPayedReturn ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($customerAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $customerAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $customerAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $amountPayedReturn ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($InventoryAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $InventoryAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $InventoryAccount->id,
                'sale_id' => $sale_id,
                'debit' => $totalPurchaseRate,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($CostGoodsSoldAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $CostGoodsSoldAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $CostGoodsSoldAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $totalPurchaseRate,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        $totalDiscount = 0;
        if (!empty($sale->discount)) {
            $totalDiscount += $sale->discount;
        }
        if (!empty($sale->fixed_discount)) {
            $totalDiscount += $sale->fixed_discount;
        }

        $productQuantities = $request->input('product_quantity', []);
        $returnQuantities = $request->input('return_quantity', []);

        $totalOriginalQty = array_sum($productQuantities);

        $proportionalDiscount = 0;

        if ($totalOriginalQty > 0) {
            $discountPerUnit = $totalDiscount / $totalOriginalQty;

            foreach ($returnQuantities as $index => $returnQty) {
                $returnQty = (float)$returnQty;
                if ($returnQty > 0) {
                    $proportionalDiscount += round($returnQty * $discountPerUnit, 2);
                }
            }
        }

        if ($DiscountAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $DiscountAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $DiscountAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $proportionalDiscount,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }
    }

    return response()->json(['status' => 'success', 'message' => 'Sale return processed successfully.']);
}


}*/



namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\customer;
use App\Models\Deal;
use App\Models\GrnAccount;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleReturnController extends Controller
{
   public function salereturn($id)
{
    $sale = Sale::with(['saleItems.dealSaleItems'])->findOrFail($id); 
    $users = User::all();
    $customers = Customer::all();
    $products = Product::all()->keyBy('item_name'); 

    foreach ($sale->saleItems as $item) {
        $matchedProduct = $products[$item->product_name] ?? null;
        $item->single_purchase_rate = $matchedProduct ? $matchedProduct->single_purchase_rate : null;
    }

    $user = Auth::user();
    $deals = Deal::all();

    return view(
        'adminpages.salereturn',
        [
            'userName' => $user->name,
            'userEmail' => $user->email,
            'users' => $users,
            'customers' => $customers,
            'products' => $products,
            'sale' => $sale,
            'deals' => $deals
        ]
    );
}



public function processSaleReturn(Request $request, $sale_id)
{
    $customCreatedAt = $request->input('created_at'); 
    $amountPayed = $request->input('sale_return');
    if ($amountPayed !== null) {
    Sale::where('id', $sale_id)->update([
        'sale_return' => $amountPayed,
        'created_at' => $customCreatedAt,
        'updated_at' => $customCreatedAt,
    ]);
}

   $amountPayedReturn = $request->input('amount_payed_return');
   if ($amountPayedReturn !== null) {
    Sale::where('id', $sale_id)->update([
        'amount_payed_return' => $amountPayedReturn,
        'created_at' => $customCreatedAt,
        'updated_at' => $customCreatedAt,
    ]);
}

    

   $productNames = $request->input('product_name', []);
$returnQuantities = $request->input('return_quantity', []);
$singlePurchaseRates = $request->input('single_purchase_rate', []);
$productRates = $request->input('product_rate', []);
$returnAmounts = $request->input('return_amount', []);

foreach ($productNames as $index => $productName) {
    $newReturnQty = (float) ($returnQuantities[$index] ?? 0);
    $singlePurchaseRate = (float) ($singlePurchaseRates[$index] ?? 0);
    $productRate = (float) ($productRates[$index] ?? 0);
    $returnAmount = (float) ($returnAmounts[$index] ?? 0);

    $saleItem = SaleItem::where('sale_id', $sale_id)
        ->where('product_name', $productName)
        ->first();

    if (!$saleItem) {
        continue;
    }

    $oldReturnQty = (float) $saleItem->return_qty;
    $difference = $newReturnQty - $oldReturnQty;

    if ($difference != 0) {
        $product = Product::where('item_name', $productName)->first();

        if ($product) {
            if ($difference > 0) {
                $product->quantity += $difference;
                $product->purchase_rate += $singlePurchaseRate * $difference;
                $product->retail_rate += $productRate * $difference;
            } else {
                $absDiff = abs($difference);
                $product->quantity -= $absDiff;
                $product->purchase_rate -= $singlePurchaseRate * $absDiff;
                $product->retail_rate -= $productRate * $absDiff;
            }

            $product->save();

            $productId = $product->id;
            $purchases = Purchase::whereJsonContains('products', (string) $productId)->get();

            foreach ($purchases as $purchase) {
                $productsArray = json_decode($purchase->products, true);
                $quantityArray = json_decode($purchase->quantity, true);
                $purchaseRateArray = json_decode($purchase->purchase_rate, true);
                $retailRateArray = json_decode($purchase->retail_rate, true);

                if (
                    is_array($productsArray) &&
                    is_array($quantityArray) &&
                    is_array($purchaseRateArray) &&
                    is_array($retailRateArray)
                ) {
                    $productIndex = array_search((string) $productId, array_map('strval', $productsArray));

                    if ($productIndex !== false) {
                        if ($difference > 0) {
                            $quantityArray[$productIndex] += $difference;
                            $purchaseRateArray[$productIndex] += $singlePurchaseRate * $difference;
                            $retailRateArray[$productIndex] += $productRate * $difference;
                        } else {
                            $absDiff = abs($difference);
                            $quantityArray[$productIndex] -= $absDiff;
                            $purchaseRateArray[$productIndex] -= $singlePurchaseRate * $absDiff;
                            $retailRateArray[$productIndex] -= $productRate * $absDiff;
                        }

                        $purchase->timestamps = false;
                        $purchase->quantity = json_encode($quantityArray);
                        $purchase->purchase_rate = json_encode($purchaseRateArray);
                        $purchase->retail_rate = json_encode($retailRateArray);
                       
                        $purchase->save();
                    }

                    break;
                }
            }
        }

        $saleItem->timestamps = false;
        $saleItem->return_qty = $newReturnQty;
        $saleItem->return_amount = $returnAmount;
        $saleItem->created_at = $customCreatedAt;
        $saleItem->updated_at = $customCreatedAt;
        $saleItem->save();
    }
}



    $dealSaleItemIds = $request->input('deal_sale_item_id', []);   
    $dealReturnQtys = $request->input('return_qty', []);
    $dealProductNames = $request->input('deal_product_name', []);
    $dealProductRetailRates = $request->input('deal_product_retail_rate', []);
    $dealProductpurchaseRates = $request->input('deal_product_purchase_rate', []);

    $aggregatedDealReturns = [];

    foreach ($dealSaleItemIds as $index => $dealSaleItemId) {
        $newReturnQty = (float) ($dealReturnQtys[$index] ?? 0);
        $retailRate = (float) ($dealProductRetailRates[$index] ?? 0);
        $purchaseRate = (float) ($dealProductpurchaseRates[$index] ?? 0);
        $productName = $dealProductNames[$index] ?? null;

        if (!$productName) {
            continue; 
        }

        $dealSaleItem = \App\Models\DealSaleItem::find($dealSaleItemId);

        if (!$dealSaleItem) {
            continue; 
        }

        $oldReturnQty = (float) $dealSaleItem->return_qty ?? 0;
        $difference = $newReturnQty - $oldReturnQty;

        if ($difference != 0) {
            $product = Product::where('item_name', $productName)->first();

            if ($product) {
                if ($difference > 0) {
                $product->quantity += $difference;
                $product->purchase_rate += $purchaseRate * $difference;
                $product->retail_rate += $retailRate * $difference;
            } else {
                $absDiff = abs($difference);
                $product->quantity -= $absDiff;
                $product->purchase_rate -= $purchaseRate * $absDiff;
                $product->retail_rate -= $retailRate * $absDiff;
            }

                $product->save();

                $productId = $product->id;
                $purchases = Purchase::whereJsonContains('products', (string) $productId)->get();

                foreach ($purchases as $purchase) {
                    $productsArray = json_decode($purchase->products, true);
                    $quantityArray = json_decode($purchase->quantity, true);
                    $purchaseRateArray = json_decode($purchase->purchase_rate, true);
                    $retailRateArray = json_decode($purchase->retail_rate, true);

                    if (
                        is_array($productsArray) &&
                        is_array($quantityArray) &&
                        is_array($purchaseRateArray) &&
                        is_array($retailRateArray)
                    ) {
                        $productIndex = array_search((string) $productId, array_map('strval', $productsArray));

                        if ($productIndex !== false) {
                        if ($difference > 0) {
                        $quantityArray[$productIndex] += $difference;
                        $purchaseRateArray[$productIndex] += $purchaseRate * $difference;
                        $retailRateArray[$productIndex] += $retailRate * $difference;
                        } else {
                        $absDiff = abs($difference);
                        $quantityArray[$productIndex] -= $absDiff;
                        $purchaseRateArray[$productIndex] -= $purchaseRate * $absDiff;
                        $retailRateArray[$productIndex] -= $retailRate * $absDiff;
                        }

                            $purchase->timestamps = false; 
                            $purchase->quantity = json_encode($quantityArray);
                            $purchase->purchase_rate = json_encode($purchaseRateArray);
                            $purchase->retail_rate = json_encode($retailRateArray);
                            
                            $purchase->save();
                        }
                        break; 
                    }
                }

                $dealSaleItem->timestamps = false;
                $dealSaleItem->return_qty = $newReturnQty;
                $dealSaleItem->return_amount = round($retailRate * $newReturnQty, 2);
                $dealSaleItem->created_at = $customCreatedAt;
                $dealSaleItem->updated_at = $customCreatedAt;
                $dealSaleItem->save();

                   



            }
        }
    }

    $sale = Sale::find($sale_id);
    $saleType = $request->input('sale_type');

    $purchaseRates = $request->input('single_purchase_rate', []);
$returnQuantities = $request->input('return_quantity', []);
$itemTypes = $request->input('item_type', []);

$totalPurchaseRate = 0;

foreach ($purchaseRates as $index => $rate) {
    $returnQty = isset($returnQuantities[$index]) ? (float)$returnQuantities[$index] : 0;
    $itemType = $itemTypes[$index] ?? 'normal';

    if ($returnQty > 0) {
        if ($itemType === 'deal') {
            $totalPurchaseRate += (float)$rate; 
        } else {
            $totalPurchaseRate += (float)$rate * $returnQty;
        }
    }
}


    if ($saleType == 'cash') {
        $cashSalesAccount = AddAccount::where('sub_head_name', 'Cash Sales')->first();
        $cashinhandAccount = AddAccount::where('sub_head_name', 'Cash In Hand')->first();
        $InventoryAccount = AddAccount::where('sub_head_name', 'Inventory')->first();
        $CostGoodsSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        $DiscountAccount = AddAccount::where('sub_head_name', 'Discount Given')->first();
        $SaleReturn = AddAccount::where('sub_head_name', 'Sales Return')->first();

        if ($cashSalesAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $cashSalesAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $sale_id,
                'debit' => $amountPayed ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($SaleReturn) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $SaleReturn->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $SaleReturn->id,
                'sale_id' => $sale_id,
                'debit' => $amountPayedReturn ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($cashinhandAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $cashinhandAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $cashinhandAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $amountPayedReturn ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($InventoryAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $InventoryAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $InventoryAccount->id,
                'sale_id' => $sale_id,
                'debit' => $totalPurchaseRate,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($CostGoodsSoldAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $CostGoodsSoldAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $CostGoodsSoldAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $totalPurchaseRate,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        $totalDiscount = 0;
        /*if (!empty($sale->discount)) {
            $totalDiscount += $sale->discount;
        }*/
        if (!empty($sale->fixed_discount)) {
            $totalDiscount += $sale->fixed_discount;
        }

        $productQuantities = $request->input('product_quantity', []);
        $returnQuantities = $request->input('return_quantity', []);

        $totalOriginalQty = array_sum($productQuantities);

        $proportionalDiscount = 0;

        if ($totalOriginalQty > 0) {
            $discountPerUnit = $totalDiscount / $totalOriginalQty;

            foreach ($returnQuantities as $index => $returnQty) {
                $returnQty = (float)$returnQty;
                if ($returnQty > 0) {
                    $proportionalDiscount += round($returnQty * $discountPerUnit, 2);
                }
            }
        }

        if ($DiscountAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $DiscountAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $DiscountAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $proportionalDiscount,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }
    } elseif ($saleType == 'credit') {
        $creditSalesAccount = AddAccount::where('sub_head_name', 'Credit Sales')->first();

        $customerName = $request->input('customer_name');
        $customerAccount = AddAccount::where('sub_head_name', $customerName)->first();

        $InventoryAccount = AddAccount::where('sub_head_name', 'Inventory')->first();
        $CostGoodsSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        $DiscountAccount = AddAccount::where('sub_head_name', 'Discount Given')->first();
        $SaleReturn = AddAccount::where('sub_head_name', 'Sales Return')->first();

        if ($creditSalesAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $creditSalesAccount->id,
                'sale_id' => $sale_id,
                'debit' => $amountPayed ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($SaleReturn) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $SaleReturn->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $SaleReturn->id,
                'sale_id' => $sale_id,
                'debit' => $amountPayedReturn ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($customerAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $customerAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $customerAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $amountPayedReturn ?? 0,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($InventoryAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $InventoryAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $InventoryAccount->id,
                'sale_id' => $sale_id,
                'debit' => $totalPurchaseRate,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        if ($CostGoodsSoldAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $CostGoodsSoldAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $CostGoodsSoldAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $totalPurchaseRate,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }

        $totalDiscount = 0;
        /*if (!empty($sale->discount)) {
            $totalDiscount += $sale->discount;
        }*/
        if (!empty($sale->fixed_discount)) {
            $totalDiscount += $sale->fixed_discount;
        }

        $productQuantities = $request->input('product_quantity', []);
        $returnQuantities = $request->input('return_quantity', []);

        $totalOriginalQty = array_sum($productQuantities);

        $proportionalDiscount = 0;

        if ($totalOriginalQty > 0) {
            $discountPerUnit = $totalDiscount / $totalOriginalQty;

            foreach ($returnQuantities as $index => $returnQty) {
                $returnQty = (float)$returnQty;
                if ($returnQty > 0) {
                    $proportionalDiscount += round($returnQty * $discountPerUnit, 2);
                }
            }
        }

        if ($DiscountAccount) {
            GrnAccount::where('sale_id', $sale_id)
                ->where('salereturn', 'salereturn')
                ->where('vendor_account_id', $DiscountAccount->id)
                ->delete();

            GrnAccount::create([
                'vendor_account_id' => $DiscountAccount->id,
                'sale_id' => $sale_id,
                'vendor_net_amount' => $proportionalDiscount,
                'salereturn' => 'salereturn',
                'created_at' => $customCreatedAt,
                'updated_at' => $customCreatedAt 
            ]);
        }
    }

    return response()->json(['status' => 'success', 'message' => 'Sale return processed successfully.']);
}


}

