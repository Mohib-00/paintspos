<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\GrnAccount;
use App\Models\Purchase;
use App\Models\RawMaterial;
use App\Models\RawmaterialPurchase;
use App\Models\Vendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialPurchaseController extends Controller
{
     public function materialpurchase(){ 
        $user = Auth::user();      
        $products = RawMaterial::all(); 
        $vendors = Vendors::all();  
        return view('adminpages.materialpurchase', ['userName' => $user->name,'userEmail' => $user->email],compact('products','vendors'));
    }


   

public function storerawmarerial(Request $request)
{
    try {
        DB::beginTransaction();

        $purchase = new RawmaterialPurchase();
        $purchase->user_id = Auth::id();
        $purchase->receiving_location = $request->receiving_location;
        $purchase->vendors = $request->vendors;
        $purchase->invoice_no = $request->invoice_no;
        $purchase->created_at = $request->created_at ?? now();
        $purchase->updated_at = $request->created_at ?? now();
        $purchase->remarks = $request->remarks;
        $purchase->single_purchase_rate = json_encode(is_array($request->single_purchase_rate) ? $request->single_purchase_rate : [$request->single_purchase_rate]);
        $purchase->products = json_encode($request->products);
        $purchase->quantity = json_encode($request->quantity);
        $purchase->purchase_rate = json_encode($request->purchase_rate);
        $purchase->totalquantity = $request->totalquantity;
        $purchase->gross_amount = $request->gross_amount;
        $purchase->discount = $request->discount ?? 0;
        $purchase->net_amount = $request->net_amount;
        $purchase->stock_status = 'complete';
        $purchase->save();

        $products = $request->products;
        $quantities = $request->quantity;
        $singleRates = is_array($request->single_purchase_rate)
            ? $request->single_purchase_rate
            : [$request->single_purchase_rate];

        foreach ($products as $index => $productId) {
            $quantity = isset($quantities[$index]) ? (float)$quantities[$index] : 0;
            $rate = isset($singleRates[$index]) ? (float)$singleRates[$index] : 0;

            $material = RawMaterial::find($productId);
            if ($material) {
                $material->quantity += $quantity;
                $material->purchase_rate = $rate;
                $material->save();
            }
        }

        $netAmount = $purchase->net_amount;
        $discount = $purchase->discount ?? 0;
        $grossAmount = $purchase->gross_amount;

        $vendorAccount = AddAccount::where('sub_head_name', $purchase->vendors)->first();


        if (!$vendorAccount) {
            DB::rollBack();
            return response()->json(['message' => 'Vendor not found in add_accounts.'], 404);
        }

        GrnAccount::create([
            'vendor_account_id' => $vendorAccount->id,
            'vendor_net_amount' => $netAmount,
            'raw_material_purchase_id' => $purchase->id,
            'grn' => 'grn',
            'created_at' => $purchase->created_at,
            'updated_at' => $purchase->created_at,
        ]);

        if ($discount > 0) {
            $discountAccount = AddAccount::where('sub_head_name', 'Discount Availed')->first();
            if (!$discountAccount) {
                DB::rollBack();
                return response()->json(['message' => 'Discount Availed account not found.'], 404);
            }

            GrnAccount::create([
                'vendor_account_id' => $discountAccount->id,
                'discount' => $discount,
                'vendor_net_amount' => $discount,
                'raw_material_purchase_id' => $purchase->id,
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
                'raw_material_purchase_id' => $purchase->id,
                'grn' => 'grn',
                'created_at' => $purchase->created_at,
                'updated_at' => $purchase->created_at,
            ]);
        }

        DB::commit(); 

        return response()->json(['success' => 'Purchase saved, inventory updated, and GRN entries recorded.']);

    } catch (\Exception $e) {
        DB::rollBack(); 
        return response()->json(['error' => 'Error saving purchase: ' . $e->getMessage()]);
    }
}


}
