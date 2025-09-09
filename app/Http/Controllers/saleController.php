<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\Category;
use App\Models\Company;
use App\Models\customer;
use App\Models\Deal;
use App\Models\DealItem;
use App\Models\DealSaleItem;
use App\Models\emplyees;
use App\Models\GrnAccount;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePurchaseLink;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class saleController extends Controller
{
    public function  pos(){ 
        $user = Auth::user();
        $employees= emplyees::all();
        $customers = customer::all();
        $products = Product::all();
        $deals = Deal::all();
        $categories = Category::all();
        $brands = Company::all(); 
        $categorys = Category::all();
        $subs = SubCategory::all();
        return view('adminpages.pos', ['userName' => $user->name,'userEmail' => $user->email],compact('employees','customers','brands','products','deals','categories','categorys','subs'));
      }

      public function  pos2(){ 
        $user = Auth::user();
        $users= User::all();
        $customers = customer::all();
        $products = Product::all();
        $deals = Deal::all();
        $categories = Category::all();
        return view('adminpages.pos2', ['userName' => $user->name,'userEmail' => $user->email],compact('users','customers','products','deals','categories'));
      }

      public function  salelist(){ 
        $user = Auth::user();
        $sales = Sale::with(['saleItems', 'user'])
            ->whereDate('created_at', Carbon::today())
            ->get();

        $saless = Sale::with(['saleItems', 'user'])->get();
        return view('adminpages.salelist', ['userName' => $user->name,'userEmail' => $user->email],compact('sales','saless'));
      }

public function getProductDetails($id)
{
    $product = Product::find($id);

    if ($product) {
        return response()->json([
            'type' => 'product',
            'item_name' => $product->item_name,
            'single_retail_rate' => $product->single_retail_rate,
            'single_purchase_rate' => $product->single_purchase_rate,
            'quantity' => $product->quantity,
            'shade' => $product->shade,
        ]);
    }

    $deal = Deal::findOrFail($id);
    $dealItems = DealItem::where('deal_id', $deal->id)->get();

    $possibleQuantities = [];

    foreach ($dealItems as $item) {
        $product = Product::where('item_name', $item->products)->first();

        if (!$product) {
            return response()->json([
                'error' => true,
                'message' => "Product '{$item->products}' not found in inventory."
            ], 400);
        }

        if ($product->quantity < $item->quantity) {
            return response()->json([
                'error' => true,
                'message' => "The item '{$item->products}' is currently out of stock or insufficient quantity for this deal."
            ], 400);
        }

        $possibleQuantity = floor($product->quantity / $item->quantity);
        $possibleQuantities[] = $possibleQuantity;
    }

    $maxDealQuantity = min($possibleQuantities);

    $purchaseRateSum = $dealItems->reduce(function ($carry, $item) {
        return $carry + ($item->single_purchase_rate * $item->quantity);
    }, 0);
    $retailRateSum = $dealItems->reduce(function ($carry, $item) {
        return $carry + ($item->single_retail_rate * $item->quantity);
    }, 0);

    return response()->json([
        'type' => 'deal',
        'deal_name' => $deal->deal_name,
        'deal_price' => $deal->deal_price,
        'single_purchase_rate' => $purchaseRateSum,
        'single_retail_rate' => $deal->deal_price,
        'retail_rate' =>  $retailRateSum,
        'quantity' => $maxDealQuantity,
        'products' => $dealItems->map(function ($item) {
            return [
                'products' => $item->products,
                'single_purchase_rate' => $item->single_purchase_rate,
                'single_retail_rate' => $item->single_retail_rate,
                'quantity' => $item->quantity,
            ];
        }),
    ]);
}



      

public function getCustomersByUsername($username)
{
    if ($username === '1') {
        $customers = Customer::all();
    } else {
        $customers = Customer::where('assigned_user_id', $username)->get();
    }

    return response()->json([
        'customers' => $customers,
        'fixed_discount' => $customers->first()?->client_fixed_discount ?? null,
    ]);
}

public function getCustomerDiscount($customerId)
{
    $customer = Customer::find($customerId);
    return response()->json([
        'fixed_discount' => $customer?->client_fixed_discount ?? null
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'employee' => 'nullable|string',
        'customer_name' => 'nullable|string',
        'created_at' => 'nullable|date',
        'ref' => 'nullable|string',
        'total_items' => 'nullable|integer',
        'total' => 'nullable|numeric',
        'sale_type' => 'required|string',
        'payment_type' => 'required_if:sale_type,1|string|nullable',
        'discount' => 'nullable|numeric',
        'amount_after_discount' => 'nullable|numeric',
        'fixed_discount' => 'nullable|numeric',
        'amount_after_fix_discount' => 'nullable|numeric',
        'subtotal' => 'nullable|numeric',
        'items' => 'required|array',
    ]);

    $createdAt = $validated['created_at'] ?? Carbon::now();

    $sale = Sale::create([
        'employee' => $validated['employee'],
        'customer_name' => $validated['customer_name'],
        'created_at' => $createdAt,
        'updated_at' => $createdAt,
        'ref' => $validated['ref'],
        'total_items' => $validated['total_items'],
        'total' => $validated['total'],
        'sale_type' => $validated['sale_type'],
        'payment_type' => $validated['sale_type'] == '1' ? $validated['payment_type'] : null,
        'discount' => $validated['discount'],
        'amount_after_discount' => $validated['amount_after_discount'],
        'fixed_discount' => $validated['fixed_discount'],
        'amount_after_fix_discount' => $validated['amount_after_fix_discount'],
        'subtotal' => $validated['subtotal'],
        'user_id' => auth()->id(),
        'status' => $validated['sale_type'] == '1' ? 'complete' : 'pending', 
    ]);

    $saleId = $sale->id;

    $totalProductRate = 0;
    $dealItemMap = [];

    foreach ($request->items as $item) {
        $product_quantity = $item['product_quantity'] ?? $item['deal_quantity'] ?? 0;

        $saleItem = SaleItem::create([
            'sale_id' => $saleId,
            'product_name' => $item['product_name'],
            'product_quantity' => $product_quantity,
            'purchase_rate' => $item['purchase_rate'],
            'product_rate' => $item['product_rate'],
            'product_subtotal' => $item['product_subtotal'],
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        if (!isset($item['product_quantity']) && isset($item['deal_quantity'])) {
            $dealItemMap[$item['product_name']] = $saleItem->id;
        }

        $totalProductRate += $item['purchase_rate'];

        $product = Product::where('item_name', $item['product_name'])->first();
        if ($product) {
            $product->quantity -= $product_quantity;
            $product->purchase_rate -= $item['purchase_rate'];
            $product->retail_rate -= $item['product_rate'];
            $product->save();
        }
    }

    $dealProductNames = $request->input('deal_product_name', []);
    $dealProductQuantities = $request->input('deal_product_quantity', []);
    $dealProductPurchaseRates = $request->input('deal_product_purchase_rate', []);
    $dealProductRetailRates = $request->input('deal_product_retail_rate', []);
    $dealnames = $request->input('deal_name', []);

    foreach ($dealProductNames as $index => $dealItemName) {
        $currentDealName = $dealnames[$index] ?? null;
        $saleItemId = $dealItemMap[$currentDealName] ?? null;

        if (!$saleItemId) continue;

        DealSaleItem::create([
            'sale_id' => $saleId,
            'sale_item_id' => $saleItemId,
            'deal_product_name' => $dealItemName,
            'deal_name' => $currentDealName,
            'deal_product_quantity' => $dealProductQuantities[$index] ?? 0,
            'deal_product_purchase_rate' => $dealProductPurchaseRates[$index] ?? 0,
            'deal_product_retail_rate' => $dealProductRetailRates[$index] ?? 0,
        ]);

        $quantity = (int) ($dealProductQuantities[$index] ?? 0);
        $purchaseRate = (float) ($dealProductPurchaseRates[$index] ?? 0) * $quantity;
        $retailRate = (float) ($dealProductRetailRates[$index] ?? 0) * $quantity;

        $product = Product::where('item_name', $dealItemName)->first();
        if ($product) {
            $product->quantity -= $quantity;
            $product->purchase_rate -= $purchaseRate;
            $product->retail_rate -= $retailRate;
            $product->save();
        }
    }

 foreach ($dealProductNames as $index => $name) {
        $quantityToDeduct = (int) ($dealProductQuantities[$index] ?? 0);
        $ratePerUnit = (float) ($dealProductPurchaseRates[$index] ?? 0);

        $product = Product::where('item_name', $name)->first();
        if ($product && $quantityToDeduct > 0) {
            $saleItem = SaleItem::where('sale_id', $saleId)
                ->where('product_name', $name) 
                ->first();

            $purchases = \DB::table('purchases')->orderBy('created_at')->get();

            foreach ($purchases as $purchase) {
                $productIds = json_decode($purchase->products, true);
                $quantities = json_decode($purchase->quantity, true);
                $purchaseRates = json_decode($purchase->purchase_rate, true);

                if (!is_array($productIds)) continue;

                foreach ($productIds as $i => $id) {
                    if ($id == $product->id && ($quantities[$i] ?? 0) > 0) {
                    $availableQty = (float) $quantities[$i]; 
                    $deductQty = $quantityToDeduct; 
                    $quantityToDeduct = 0; 

                    $quantities[$i] = $availableQty - $deductQty;
                    $purchaseRates[$i] -= ($ratePerUnit * $deductQty);

                        \DB::table('purchases')->where('id', $purchase->id)->update([
                            'quantity' => json_encode($quantities),
                            'purchase_rate' => json_encode($purchaseRates),
                        ]);
                        SalePurchaseLink::create([
    'sale_id' => $sale->id,
    'sale_item_id' => $saleItemId ?? null,
    'purchase_id' => $purchase->id,
    'product_id' => $product->id,
    'deducted_quantity' => $deductQty,
    'deducted_purchase_rate' => $ratePerUnit,
]);

                    

                        if ($quantityToDeduct <= 0) break 2;
                    }
                }
            }
        }
    }

    foreach ($request->items as $item) {
        $quantityToDeduct = (float) ($item['product_quantity'] ?? 0);
        $rate = (float) ($item['purchase_rate'] ?? 0);
        $ratePerUnit = $quantityToDeduct > 0 ? $rate / $quantityToDeduct : 0;

        $product = Product::where('item_name', $item['product_name'])->first();
        if ($product && $quantityToDeduct > 0) {
            $saleItem = SaleItem::where('sale_id', $saleId)
                ->where('product_name', $item['product_name'])
                ->first();

            $purchases = \DB::table('purchases')->orderBy('created_at')->get();

            foreach ($purchases as $purchase) {
                $productIds = json_decode($purchase->products, true);
                $quantities = json_decode($purchase->quantity, true);
                $purchaseRates = json_decode($purchase->purchase_rate, true);

                if (!is_array($productIds)) continue;

                foreach ($productIds as $i => $id) {
                    if ($id == $product->id && ($quantities[$i] ?? 0) > 0) {
                       $availableQty = (float) $quantities[$i]; 
                       $deductQty = $quantityToDeduct; 
                       $quantityToDeduct = 0; 
                       $quantities[$i] = $availableQty - $deductQty;
                       $purchaseRates[$i] -= ($ratePerUnit * $deductQty);

                        \DB::table('purchases')->where('id', $purchase->id)->update([
                            'quantity' => json_encode($quantities),
                            'purchase_rate' => json_encode($purchaseRates),
                        ]);

                        SalePurchaseLink::create([
    'sale_id' => $sale->id,
    'sale_item_id' => $saleItem->id,
    'purchase_id' => $purchase->id,
    'product_id' => $product->id,
    'deducted_quantity' => $deductQty,
    'deducted_purchase_rate' => $ratePerUnit,
]);

                       

                        if ($quantityToDeduct <= 0) break 2;
                    }
                }
            }
        }
    }

    $totalProductRate = collect($request->items)->sum('purchase_rate');

    if ($sale->sale_type == '1') { 
        $paymentType = strtolower(trim($sale->payment_type)); 
        $cashAccountName = $paymentType === '2' ? 'Cash At Bank' : 'Cash In Hand';

        $cashAccount = AddAccount::whereRaw('LOWER(sub_head_name) = ?', [strtolower($cashAccountName)])->first();
        if ($cashAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashAccount->id,
                'sale_id' => $saleId,
                'debit' => $sale->subtotal,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        } else {
            \Log::warning("Account not found for: " . $cashAccountName);
        }

        $cashSalesAccount = AddAccount::where('sub_head_name', 'Cash Sales')->first();
        if ($cashSalesAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $saleId,
                'vendor_net_amount' => $sale->total,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $CostOfGoodSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        if ($CostOfGoodSoldAccount) {
            GrnAccount::create([
                'vendor_account_id' => $CostOfGoodSoldAccount->id,
                'sale_id' => $saleId,
                'debit' => $totalProductRate, 
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $inventory = AddAccount::where('sub_head_name', 'Inventory')->first();
        if ($inventory) {
            GrnAccount::create([
                'vendor_account_id' => $inventory->id,
                'sale_id' => $saleId,
                'vendor_net_amount' => $totalProductRate, 
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }




          $discountwithoutfix = AddAccount::where('sub_head_name', 'Discount')->first();
        if ($discountwithoutfix) {
            $debitAmount = 0;
            if ($sale->discount) {
                $debitAmount += $sale->discount;
            }

            GrnAccount::create([
                'vendor_account_id' => $discountwithoutfix->id,
                'sale_id' => $saleId,
                'debit' => $debitAmount,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }




        $disGiven = AddAccount::where('sub_head_name', 'Discount Given')->first();
        if ($disGiven) {
            $debitAmount = 0;
       
            if ($sale->fixed_discount) {
                $debitAmount += $sale->fixed_discount;
            }

            GrnAccount::create([
                'vendor_account_id' => $disGiven->id,
                'sale_id' => $saleId,
                'debit' => $debitAmount,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $taxPayable = AddAccount::where('sub_head_name', 'Tax Payable')->first();
        if ($taxPayable) {
            GrnAccount::create([
                'vendor_account_id' => $taxPayable->id,
                'sale_id' => $saleId,
                'vendor_net_amount' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

    } elseif ($sale->sale_type == '2') {
        $customerAccount = AddAccount::where('sub_head_name', $request->customer_name)->first();
        if ($customerAccount) {
            GrnAccount::create([
                'vendor_account_id' => $customerAccount->id,
                'sale_id' => $saleId,
                'debit' => $sale->subtotal,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $cashSalesAccount = AddAccount::where('sub_head_name', 'Credit Sales')->first();
        if ($cashSalesAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $saleId,
                'vendor_net_amount' => $sale->total,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $CostOfGoodSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        if ($CostOfGoodSoldAccount) {
            GrnAccount::create([
                'vendor_account_id' => $CostOfGoodSoldAccount->id,
                'sale_id' => $saleId,
                'debit' => $totalProductRate, 
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $inventory = AddAccount::where('sub_head_name', 'Inventory')->first();
        if ($inventory) {
            GrnAccount::create([
                'vendor_account_id' => $inventory->id,
                'sale_id' => $saleId,
                'vendor_net_amount' => $totalProductRate, 
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }



        $discountwithoutfix = AddAccount::where('sub_head_name', 'Discount')->first();
        if ($discountwithoutfix) {
            $debitAmount = 0;
            if ($sale->discount) {
                $debitAmount += $sale->discount;
            }

            GrnAccount::create([
                'vendor_account_id' => $discountwithoutfix->id,
                'sale_id' => $saleId,
                'debit' => $debitAmount,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $disGiven = AddAccount::where('sub_head_name', 'Discount Given')->first();
        if ($disGiven) {
            $debitAmount = 0;
          
            if ($sale->fixed_discount) {
                $debitAmount += $sale->fixed_discount;
            }

            GrnAccount::create([
                'vendor_account_id' => $disGiven->id,
                'sale_id' => $saleId,
                'debit' => $debitAmount,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $taxPayable = AddAccount::where('sub_head_name', 'Tax Payable')->first();
        if ($taxPayable) {
            GrnAccount::create([
                'vendor_account_id' => $taxPayable->id,
                'sale_id' => $saleId,
                'vendor_net_amount' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }

    return response()->json(['message' => 'Sale recorded successfully']);
}


public function edit($id)
{
    $sale = Sale::with(['saleItems.dealSaleItems'])->findOrFail($id); 

    $employees = emplyees::all();
    $customers = Customer::all();
    $products = Product::all();
    $user = Auth::user();
    $deals = Deal::all();

    return view('adminpages.salesedit', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'employees' => $employees,
        'customers' => $customers,
        'products' => $products,
        'sale' => $sale,
        'deals' => $deals
    ]);
}


public function getProductQuantity(Request $request)
{
    $product = Product::where('item_name', $request->product_name)->first();

    if ($product) {
        return response()->json(['quantity' => $product->quantity]);
    }

    return response()->json(['quantity' => 0], 404);
}


public function updateSale(Request $request, $saleId)
{
  $validated = $request->validate([
    'employee' => 'nullable|string',
    'customer_name' => 'nullable|string',
    'created_at' => 'nullable|date',
    'ref' => 'nullable|string',
    'total_items' => 'nullable|integer',
    'total' => 'nullable|numeric',
    'sale_type' => 'required|string',
    'payment_type' => 'string|nullable',
    'discount' => 'nullable|numeric',
    'amount_after_discount' => 'nullable|numeric',
    'fixed_discount' => 'nullable|numeric',
    'amount_after_fix_discount' => 'nullable|numeric',
    'subtotal' => 'nullable|numeric',
    'items' => 'required|array',
    'deal_product_name' => 'nullable|array',
    'deal_product_quantity' => 'nullable|array',
    'deal_product_purchase_rate' => 'nullable|array',
    'deal_product_retail_rate' => 'nullable|array',
    'deal_name' => 'nullable|array',
]);

$sale = Sale::findOrFail($saleId);

$oldSaleItems = SaleItem::where('sale_id', $sale->id)->get()->keyBy('product_name');
$newProductNames = collect($validated['items'])->pluck('product_name')->toArray();
$oldProductNames = $oldSaleItems->keys()->toArray();
$deletedItems = array_diff($oldProductNames, $newProductNames);

foreach ($deletedItems as $deletedName) {
    $product = Product::where('item_name', $deletedName)->first();
    $oldItem = $oldSaleItems[$deletedName] ?? null;

    if ($product && $oldItem) {
        $restoreQty = $oldItem->product_quantity;
        $restorePurchaseRate = $restoreQty * $product->single_purchase_rate;
        $restoreRetailRate = $restoreQty * $product->single_retail_rate;

        $product->quantity += $restoreQty;
        $product->purchase_rate += $restorePurchaseRate;
        $product->retail_rate += $restoreRetailRate;
        $product->save();

          $purchases = \DB::table('purchases')->orderBy('created_at')->get();
        foreach ($purchases as $purchase) {
            $productIds = json_decode($purchase->products, true);
            $quantities = json_decode($purchase->quantity, true);
            $purchaseRates = json_decode($purchase->purchase_rate, true);

            if (is_array($productIds)) {
                foreach ($productIds as $i => $productId) {
                    if ($productId == $product->id) {
                        $quantities[$i] = ($quantities[$i] ?? 0) + $restoreQty;
                        $purchaseRates[$i] = ($purchaseRates[$i] ?? 0) + $restorePurchaseRate;

                        \DB::table('purchases')->where('id', $purchase->id)->update([
                            'quantity' => json_encode($quantities),
                            'purchase_rate' => json_encode($purchaseRates),
                        ]);
                        break 2;
                    }
                }
            }
        }
    }

    $dealItems = DealSaleItem::where('sale_id', $sale->id)
        ->where('deal_name', $deletedName)
        ->get();

    foreach ($dealItems as $dealItem) {
        $product = Product::where('item_name', $dealItem->deal_product_name)->first();

        if ($product) {
            $restoreQty = $dealItem->deal_product_quantity;
            $restorePurchaseRate = $dealItem->deal_product_purchase_rate * $restoreQty;
            $restoreRetailRate = $dealItem->deal_product_retail_rate * $restoreQty;

            $product->quantity += $restoreQty;
            $product->purchase_rate += $restorePurchaseRate;
            $product->retail_rate += $restoreRetailRate;
            $product->save();

            $purchases = \DB::table('purchases')->orderBy('created_at')->get(); 
                foreach ($purchases as $purchase) {
                $productIds = json_decode($purchase->products, true);
                $quantities = json_decode($purchase->quantity, true);
                $purchaseRates = json_decode($purchase->purchase_rate, true);

                if (is_array($productIds)) {
                    foreach ($productIds as $i => $productId) {
                        if ($productId == $product->id) {
                            $quantities[$i] += $restoreQty;
                            $purchaseRates[$i] += $restorePurchaseRate;
                            \DB::table('purchases')->where('id', $purchase->id)->update([
                                'quantity' => json_encode($quantities),
                                'purchase_rate' => json_encode($purchaseRates),
                            ]);
                            break 2;
                        }
                    }
                }
            }
        }
    }
}

SaleItem::where('sale_id', $sale->id)
    ->whereIn('product_name', $deletedItems)
    ->delete();

DealSaleItem::where('sale_id', $sale->id)
    ->whereIn('deal_name', $deletedItems)
    ->delete();

GrnAccount::where('sale_id', $sale->id)->delete();

if ($validated['sale_type'] == 'credit') {
    $validated['payment_type'] = null;
} elseif ($validated['sale_type'] == 'cash' && empty($validated['payment_type'])) {
    $validated['payment_type'] = 'Cash';
}

$sale->update([
    'employee' => $validated['employee'] ?? $sale->employee,
    'customer_name' => $validated['customer_name'] ?? $sale->customer_name,
    'created_at' => $validated['created_at'] ?? $sale->created_at,
    'updated_at' => $validated['created_at'] ?? $sale->created_at,
    'ref' => $validated['ref'] ?? $sale->ref,
    'total_items' => $validated['total_items'] ?? $sale->total_items,
    'total' => $validated['total'] ?? $sale->total,
    'sale_type' => $validated['sale_type'],
    'payment_type' => ($validated['sale_type'] == 'credit') ? null : ($validated['payment_type'] ?? $sale->payment_type),
    'discount' => $validated['discount'] ?? $sale->discount,
    'amount_after_discount' => $validated['amount_after_discount'] ?? $sale->amount_after_discount,
    'fixed_discount' => $validated['fixed_discount'] ?? $sale->fixed_discount,
    'amount_after_fix_discount' => $validated['amount_after_fix_discount'] ?? $sale->amount_after_fix_discount,
    'subtotal' => $validated['subtotal'] ?? $sale->subtotal,
    'status' => $validated['sale_type'] == 'cash' ? 'complete' : 'pending',
]);

$dealItemMap = [];
foreach ($validated['items'] as $item) {
    $productName = $item['product_name'];
    $newQty = $item['product_quantity'] ?? $item['deal_quantity'] ?? 0;
    $newPurchaseRate = $item['purchase_rate'] ?? 0;

    $saleItem = SaleItem::updateOrCreate(
        ['sale_id' => $sale->id, 'product_name' => $productName],
        [
            'product_quantity' => $newQty,
            'product_rate' => $item['product_rate'],
            'product_subtotal' => $item['product_subtotal'],
            'purchase_rate' => $newPurchaseRate,
            'created_at' => $validated['created_at'] ?? $sale->created_at,
            'updated_at' => $validated['created_at'] ?? $sale->created_at,
        ]
    );

    if (!isset($item['product_quantity']) && isset($item['deal_quantity'])) {
        $dealItemMap[$productName] = $saleItem->id;
    }

    $product = Product::where('item_name', $productName)->first();
    if (!$product) continue;

    $oldQty = $oldSaleItems[$productName]->product_quantity ?? 0;
    $oldPurchaseRate = $oldSaleItems[$productName]->purchase_rate ?? 0;

    $qtyDifference = $newQty - $oldQty;
    $purchaseRateDifference = $newPurchaseRate - $oldPurchaseRate;

    $product->quantity -= $qtyDifference;

    if ($qtyDifference > 0) {
        $product->purchase_rate -= ($qtyDifference * $product->single_purchase_rate);
        $product->retail_rate -= ($qtyDifference * $product->single_retail_rate);
    } else {
        $product->purchase_rate += abs($qtyDifference) * $product->single_purchase_rate;
        $product->retail_rate += abs($qtyDifference) * $product->single_retail_rate;
    }

    $product->save();

   if ($qtyDifference != 0) {
    if ($qtyDifference > 0) {
        $quantityToDeduct = $qtyDifference;

        $purchases = \DB::table('purchases')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($purchases as $purchase) {
            $productIds = json_decode($purchase->products, true);
            $quantities = json_decode($purchase->quantity, true);
            $purchaseRates = json_decode($purchase->purchase_rate, true);

            if (!is_array($productIds)) continue;

            foreach ($productIds as $i => $productId) {
                if ($productId == $product->id && $quantityToDeduct > 0) {
                    $availableQty = $quantities[$i] ?? 0;

                    // ✅ allow negatives also
                    $deductQty = min($availableQty + $quantityToDeduct, $quantityToDeduct);

                    $quantities[$i] = $availableQty - $deductQty;
                    $purchaseRates[$i] = ($purchaseRates[$i] ?? 0) - ($deductQty * $product->single_purchase_rate);

                    \DB::table('purchases')->where('id', $purchase->id)->update([
                        'quantity' => json_encode($quantities),
                        'purchase_rate' => json_encode($purchaseRates),
                    ]);

                    $quantityToDeduct -= $deductQty;

                    if ($quantityToDeduct <= 0) {
                        break 2;
                    }
                }
            }
        }
    } else {
        $quantityToAdd = abs($qtyDifference);

        $purchases = \DB::table('purchases')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        foreach ($purchases as $purchase) {
            $productIds = json_decode($purchase->products, true);
            $quantities = json_decode($purchase->quantity, true);
            $purchaseRates = json_decode($purchase->purchase_rate, true);

            if (!is_array($productIds)) continue;

            foreach ($productIds as $i => $productId) {
                if ($productId == $product->id && $quantityToAdd > 0) {
                    $quantities[$i] = ($quantities[$i] ?? 0) + $quantityToAdd;
                    $purchaseRates[$i] = ($purchaseRates[$i] ?? 0) + ($quantityToAdd * $product->single_purchase_rate);

                    \DB::table('purchases')->where('id', $purchase->id)->update([
                        'quantity' => json_encode($quantities),
                        'purchase_rate' => json_encode($purchaseRates),
                    ]);

                    $quantityToAdd = 0;
                    break 2;
                }
            }
        }
    }
}


}


$dealProductNames = $request->input('deal_product_name', []);
$dealProductQuantities = $request->input('deal_product_quantity', []);
$dealProductPurchaseRates = $request->input('deal_product_purchase_rate', []);
$dealProductRetailRates = $request->input('deal_product_retail_rate', []);
$dealNames = $request->input('deal_name', []);

$existingDealItems = DealSaleItem::where('sale_id', $sale->id)->get()->keyBy(function($dealItem) {
    return $dealItem->deal_name . '||' . $dealItem->deal_product_name;
});

$requestDealItemKeys = [];

foreach ($dealProductNames as $i => $dealProductName) {
    $dealName = $dealNames[$i];
    $requestKey = $dealName . '||' . $dealProductName;
    $requestDealItemKeys[] = $requestKey;

    $saleItemId = $dealItemMap[$dealName]
        ?? SaleItem::where('sale_id', $sale->id)
                   ->where('product_name', $dealName)
                   ->value('id');

    if (!$saleItemId) continue;

    $quantity = $dealProductQuantities[$i];
    $purchaseRate = $dealProductPurchaseRates[$i] * $quantity;
    $retailRate = $dealProductRetailRates[$i] * $quantity;

    $product = Product::where('item_name', $dealProductName)->first();

    if (isset($existingDealItems[$requestKey])) {
        $existingDeal = $existingDealItems[$requestKey];

        $oldQty = $existingDeal->deal_product_quantity;
        $oldPurchaseRate = $existingDeal->deal_product_purchase_rate * $oldQty;
        $oldRetailRate = $existingDeal->deal_product_retail_rate * $oldQty;

        $qtyDiff = $quantity - $oldQty;
        $purchaseRateDiff = $purchaseRate - $oldPurchaseRate;
        $retailRateDiff = $retailRate - $oldRetailRate;

        $existingDeal->update([
            'deal_product_quantity' => $quantity,
            'deal_product_purchase_rate' => $dealProductPurchaseRates[$i],
            'deal_product_retail_rate' => $dealProductRetailRates[$i],
        ]);

        if ($product && $qtyDiff != 0) {
            $product->quantity -= $qtyDiff;
            $product->purchase_rate -= $purchaseRateDiff;
            $product->retail_rate -= $retailRateDiff;
            $product->save();

            $purchases = \DB::table('purchases')
    ->orderBy('created_at', 'asc')
    ->orderBy('id', 'asc')
    ->get();


            if ($qtyDiff > 0) {
                $quantityToDeduct = $qtyDiff;
                foreach ($purchases as $purchase) {
                    $productIds = json_decode($purchase->products, true);
                    $quantities = json_decode($purchase->quantity, true);
                    $purchaseRates = json_decode($purchase->purchase_rate, true);

                    if (!is_array($productIds)) continue;

                    foreach ($productIds as $index => $productId) {
                        if ($productId == $product->id && $quantityToDeduct > 0) {
                            $availableQty = $quantities[$index] ?? 0;
                            if ($availableQty <= 0) continue;

                            $deductQty = min($availableQty, $quantityToDeduct);

                            $quantities[$index] -= $deductQty;
                            $purchaseRates[$index] -= ($deductQty * $product->single_purchase_rate);

                            \DB::table('purchases')->where('id', $purchase->id)->update([
                                'quantity' => json_encode($quantities),
                                'purchase_rate' => json_encode($purchaseRates),
                            ]);

                            $quantityToDeduct -= $deductQty;
                            if ($quantityToDeduct <= 0) break 2;
                        }
                    }
                }
            } else {
                $restoreQty = abs($qtyDiff);
                $restoreRate = abs($purchaseRateDiff);

                foreach ($purchases as $purchase) {
                    $productIds = json_decode($purchase->products, true);
                    $quantities = json_decode($purchase->quantity, true);
                    $purchaseRates = json_decode($purchase->purchase_rate, true);

                    if (!is_array($productIds)) continue;

                    foreach ($productIds as $index => $productId) {
                        if ($productId == $product->id) {
                            $quantities[$index] = ($quantities[$index] ?? 0) + $restoreQty;
                            $purchaseRates[$index] = ($purchaseRates[$index] ?? 0) + $restoreRate;

                            \DB::table('purchases')->where('id', $purchase->id)->update([
                                'quantity' => json_encode($quantities),
                                'purchase_rate' => json_encode($purchaseRates),
                            ]);
                            break 2;
                        }
                    }
                }
            }
        }
    } else {
        DealSaleItem::create([
            'sale_id' => $sale->id,
            'sale_item_id' => $saleItemId,
            'deal_product_name' => $dealProductName,
            'deal_name' => $dealName,
            'deal_product_quantity' => $quantity,
            'deal_product_purchase_rate' => $dealProductPurchaseRates[$i],
            'deal_product_retail_rate' => $dealProductRetailRates[$i],
        ]);

        if ($product) {
            $product->quantity -= $quantity;
            $product->purchase_rate -= $purchaseRate;
            $product->retail_rate -= $retailRate;
            $product->save();

            $quantityToDeduct = $quantity;
            $purchases = \DB::table('purchases')
    ->orderBy('created_at', 'asc')
    ->orderBy('id', 'asc')
    ->get();


            foreach ($purchases as $purchase) {
                $productIds = json_decode($purchase->products, true);
                $quantities = json_decode($purchase->quantity, true);
                $purchaseRates = json_decode($purchase->purchase_rate, true);

                if (!is_array($productIds)) continue;

                foreach ($productIds as $index => $productId) {
                    if ($productId == $product->id && $quantityToDeduct > 0) {
                        $availableQty = $quantities[$index] ?? 0;
                        if ($availableQty <= 0) continue;

                        $deductQty = min($availableQty, $quantityToDeduct);

                        $quantities[$index] -= $deductQty;
                        $purchaseRates[$index] -= ($deductQty * $product->single_purchase_rate);

                        \DB::table('purchases')->where('id', $purchase->id)->update([
                            'quantity' => json_encode($quantities),
                            'purchase_rate' => json_encode($purchaseRates),
                        ]);

                        $quantityToDeduct -= $deductQty;
                        if ($quantityToDeduct <= 0) break 2;
                    }
                }
            }
        }
    }
}



foreach ($existingDealItems as $key => $dealItem) {
    if (!in_array($key, $requestDealItemKeys)) {
        $product = Product::where('item_name', $dealItem->deal_product_name)->first();
        if ($product) {
            $restoreQty = $dealItem->deal_product_quantity;
            $restorePurchaseRate = $dealItem->deal_product_purchase_rate * $restoreQty;
            $restoreRetailRate = $dealItem->deal_product_retail_rate * $restoreQty;

            $product->quantity += $restoreQty;
            $product->purchase_rate += $restorePurchaseRate;
            $product->retail_rate += $restoreRetailRate;
            $product->save();

            $purchases = \DB::table('purchases')->get();
            foreach ($purchases as $purchase) {
                $productIds = json_decode($purchase->products, true);
                $quantities = json_decode($purchase->quantity, true);
                $purchaseRates = json_decode($purchase->purchase_rate, true);

                if (is_array($productIds)) {
                    foreach ($productIds as $index => $productId) {
                        if ($productId == $product->id) {
                            $quantities[$index] = ($quantities[$index] ?? 0) + $restoreQty;
                            $purchaseRates[$index] = ($purchaseRates[$index] ?? 0) + $restorePurchaseRate;

                            \DB::table('purchases')->where('id', $purchase->id)->update([
                                'quantity' => json_encode($quantities),
                                'purchase_rate' => json_encode($purchaseRates),
                            ]);
                            break 2;
                        }
                    }
                }
            }
        }
        $dealItem->delete();
    }
}




    $totalProductRate = collect($validated['items'])->sum('purchase_rate');

    if ($sale->sale_type == 'cash') {
        $paymentType = strtolower(trim($sale->payment_type));
        $cashAccountName = $paymentType === 'bank' ? 'Cash At Bank' : 'Cash In Hand';

        $cashAccount = AddAccount::whereRaw('LOWER(sub_head_name) = ?', [strtolower($cashAccountName)])->first();
        if ($cashAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashAccount->id,
                'sale_id' => $sale->id,
                'debit' => $sale->subtotal,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        } else {
            \Log::warning("Account not found for: " . $cashAccountName);
        }

        $cashSalesAccount = AddAccount::where('sub_head_name', 'Cash Sales')->first();
        if ($cashSalesAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $sale->total,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $CostOfGoodSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        if ($CostOfGoodSoldAccount) {
            GrnAccount::create([
                'vendor_account_id' => $CostOfGoodSoldAccount->id,
                'sale_id' => $sale->id,
                'debit' => $totalProductRate,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $inventory = AddAccount::where('sub_head_name', 'Inventory')->first();
        if ($inventory) {
            GrnAccount::create([
                'vendor_account_id' => $inventory->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $totalProductRate,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $discountwithoutfix = AddAccount::where('sub_head_name', 'Discount')->first();
        if ($discountwithoutfix) {
            $debitAmount = 0;
            if ($sale->discount) {
                $debitAmount += $sale->discount;
            }

            GrnAccount::create([
                'vendor_account_id' => $discountwithoutfix->id,
                'sale_id' => $sale->id,
                'debit' => $debitAmount,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $disGiven = AddAccount::where('sub_head_name', 'Discount Given')->first();
        if ($disGiven) {
            $debitAmount = 0;
           
            if ($sale->fixed_discount) {
                $debitAmount += $sale->fixed_discount;
            }

            GrnAccount::create([
                'vendor_account_id' => $disGiven->id,
                'sale_id' => $sale->id,
                'debit' => $debitAmount,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $taxPayable = AddAccount::where('sub_head_name', 'Tax Payable')->first();
        if ($taxPayable) {
            GrnAccount::create([
                'vendor_account_id' => $taxPayable->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => 0,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }
    } elseif ($sale->sale_type == 'credit') {
        $customerAccount = AddAccount::where('sub_head_name', $request->customer_name)->first();
        if ($customerAccount) {
            GrnAccount::create([
                'vendor_account_id' => $customerAccount->id,
                'sale_id' => $sale->id,
                'debit' => $sale->subtotal,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $cashSalesAccount = AddAccount::where('sub_head_name', 'Credit Sales')->first();
        if ($cashSalesAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $sale->total,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $CostOfGoodSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        if ($CostOfGoodSoldAccount) {
            GrnAccount::create([
                'vendor_account_id' => $CostOfGoodSoldAccount->id,
                'sale_id' => $sale->id,
                'debit' => $totalProductRate,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $inventory = AddAccount::where('sub_head_name', 'Inventory')->first();
        if ($inventory) {
            GrnAccount::create([
                'vendor_account_id' => $inventory->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $totalProductRate,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

          $discountwithoutfix = AddAccount::where('sub_head_name', 'Discount')->first();
        if ($discountwithoutfix) {
            $debitAmount = 0;
            if ($sale->discount) {
                $debitAmount += $sale->discount;
            }

            GrnAccount::create([
                'vendor_account_id' => $discountwithoutfix->id,
                'sale_id' => $sale->id,
                'debit' => $debitAmount,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $disGiven = AddAccount::where('sub_head_name', 'Discount Given')->first();
        if ($disGiven) {
            $debitAmount = 0;
            
            if ($sale->fixed_discount) {
                $debitAmount += $sale->fixed_discount;
            }

            GrnAccount::create([
                'vendor_account_id' => $disGiven->id,
                'sale_id' => $sale->id,
                'debit' => $debitAmount,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }

        $taxPayable = AddAccount::where('sub_head_name', 'Tax Payable')->first();
        if ($taxPayable) {
            GrnAccount::create([
                'vendor_account_id' => $taxPayable->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => 0,
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Sale updated successfully',
        'sale' => $sale,
    ]);
}




public function deleteSale($saleId)
{
    $sale = Sale::findOrFail($saleId);
    SaleItem::where('sale_id', $saleId)->delete();
    GrnAccount::where('sale_id', $saleId)->delete();
    $sale->delete();
    return response()->json(['message' => 'Sale and related records deleted successfully!']);
}


public function saleinvoice($id)
{
    $sale = Sale::with(['saleItems', 'user'])->findOrFail($id);

    return view('adminpages.invoice', [
        'sale' => $sale,
        'userName' => $sale->user->name,
        'userEmail' => $sale->user->email,
        'saleItems' => $sale->saleitems,
    ]);
}

public function saleprintinvoice($id)
{
    $sale = Sale::with(['saleItems', 'user'])->findOrFail($id);

    return view('adminpages.saleprintinvoice', [
        'sale' => $sale,
        'userName' => $sale->user->name,
        'userEmail' => $sale->user->email,
        'saleItems' => $sale->saleitems,
    ]);
}




public function completeSale(Request $request)
{
    $request->validate([
        'sale_id' => 'required|exists:sales,id',
        'payment_type' => 'required|in:1,2',
        'customer_name' => 'required|string',
        'subtotal' => 'required|numeric',
    ]);

    $sale = Sale::findOrFail($request->sale_id);

    if ($sale->sale_type !== 'credit' || $sale->status !== 'pending') {
        return response()->json(['message' => 'Invalid sale operation.'], 400);
    }

    $sale->payment_type = $request->payment_type;
    $sale->status = 'complete';
    $sale->sale_type = 'cash';
    $sale->save();

    $subtotal = $request->subtotal;

    $customerAccount = AddAccount::firstOrCreate(
        ['sub_head_name' => $request->customer_name],
        ['head_name' => 'Customer', 'account_type' => 'vendor']
    );

    $cashAccountName = $request->payment_type == '2' ? 'Cash At Bank' : 'Cash In Hand';
    $cashAccount = AddAccount::whereRaw('LOWER(sub_head_name) = ?', [strtolower($cashAccountName)])->first();

    if (!$cashAccount) {
        return response()->json(['message' => 'Cash account not found.'], 400);
    }

    GrnAccount::create([
        'vendor_account_id' => $customerAccount->id,
        'sale_id' => $sale->id,
        'vendor_name' => $request->customer_name,
        'vendor_net_amount' => $subtotal,
        'complete' => 'complete',
        'created_at' =>  $sale->created_at,
        'updated_at' =>  $sale->created_at,
    ]);

    GrnAccount::create([
        'vendor_account_id' => $cashAccount->id,
        'sale_id' => $sale->id,
        'vendor_name' => $cashAccountName,
        'debit' => $subtotal,
        'credit' => $subtotal,
        'complete' => 'complete',
        'created_at' =>  $sale->created_at,
        'updated_at' =>  $sale->created_at,
    ]);

    return response()->json(['message' => 'Sale marked complete. GRN entries created.']);
}


public function salelistsearch(Request $request)
{
    $user = Auth::user();
    $query = Sale::with(['user']);

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
    }

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('customer_name')) {
        $query->where('customer_name', $request->customer_name);
    }

    $sales = $query->get();
    $saless = Sale::with(['saleItems', 'user'])->get();

    return view('adminpages.salelist', compact('sales','saless'))
        ->with([
            'userName' => $user->name,
            'userEmail' => $user->email,
        ]);
}

}
