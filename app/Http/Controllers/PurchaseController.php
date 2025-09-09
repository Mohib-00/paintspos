<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\RawMaterial;
use App\Models\Vendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function addpurchase(){ 
        $user = Auth::user();      
        $purchases = Purchase::whereDate('created_at', Carbon::today())
        ->orderBy('created_at', 'desc')
        ->get();
        return view('adminpages.purchases', ['userName' => $user->name,'userEmail' => $user->email],compact('purchases'));
    }

    public function purchases(){ 
        $user = Auth::user();      
        $products = Product::all(); 
        $vendors = Vendors::all();  
        $rawmaterials = RawMaterial::all();
        return view('adminpages.purchasess', ['userName' => $user->name,'userEmail' => $user->email],compact('products','vendors','rawmaterials'));
    }

    public function editpurchases($id)
    {
        $user = Auth::user();
        $products = Product::all();
        $vendors = Vendors::all();
        
        $purchase = Purchase::findOrFail($id);
    
        $selectedProductIds = json_decode($purchase->products ?? '[]');
    
        return view('adminpages.editpurchasess', [
            'userName' => $user->name,
            'userEmail' => $user->email,
            'products' => $products,
            'vendors' => $vendors,
            'selectedProductIds' => $selectedProductIds,
            'purchase' => $purchase
        ]);
    }


    public function purchaseinvoice($id)
    {
        $user = Auth::user();
        $products = Product::select('id', 'brand_name', 'item_name', 'barcode')->get(); 
        $vendors = Vendors::all();
    
        $purchase = Purchase::findOrFail($id);
    
        return view('adminpages.purchaseinvoice', [
            'userName' => $user->name,
            'userEmail' => $user->email,
            'products' => $products,
            'vendors' => $vendors,
            'selectedProductIds' => json_decode($purchase->products ?? '[]'),
            'quantities' => json_decode($purchase->quantity ?? '[]'),
            'retailRates' => json_decode($purchase->purchase_rate ?? '[]'),
            'purchase' => $purchase
        ]);
    }
    
    

  public function store(Request $request)
{
    try {
        $purchase = new Purchase();
        $purchase->user_id = Auth::id();
        $purchase->receiving_location = $request->receiving_location;
        $purchase->vendors = $request->vendors;
        $purchase->invoice_no = $request->invoice_no;
        $purchase->created_at = $request->created_at ? $request->created_at : now();
        $purchase->updated_at = $request->created_at ? $request->created_at : now();
        $purchase->remarks = $request->remarks;

        $purchase->single_purchase_rate = json_encode(is_array($request->single_purchase_rate) ? $request->single_purchase_rate : [$request->single_purchase_rate]);
        $purchase->single_retail_rate = json_encode(is_array($request->single_retail_rate) ? $request->single_retail_rate : [$request->single_retail_rate]);

        $purchase->products = json_encode($request->products);
        $purchase->quantity = json_encode($request->quantity);
        $purchase->original_qty = json_encode($request->quantity);
        $purchase->retail_rate = json_encode($request->retail_rate);
        $purchase->purchase_rate = json_encode($request->purchase_rate);
        $purchase->totalquantity = $request->totalquantity;
        $purchase->gross_amount = $request->gross_amount;
        $purchase->discount = $request->discount ?? 0;
        $purchase->net_amount = $request->net_amount;

        $purchase->save();

$rawProductIds = $request->raw_material_products ?? [];
$rawQuantities = $request->raw_material_quantities ?? [];

foreach ($rawProductIds as $index => $rawProductId) {
    $qtyToDeduct = isset($rawQuantities[$index]) ? (float) $rawQuantities[$index] : 0;

    $rawMaterial = RawMaterial::find($rawProductId);
    if ($rawMaterial && $qtyToDeduct > 0) {
        $rawMaterial->quantity = max(0, $rawMaterial->quantity - $qtyToDeduct);
        $rawMaterial->save();
    }
}

if ($request->filled('removed_raw_materials')) {
    $removedRawMaterials = json_decode($request->removed_raw_materials, true);
    foreach ($removedRawMaterials as $removed) {
        $rawMaterial = RawMaterial::find($removed['product_id']);
        $qtyToDeduct = (float) $removed['quantity'] ?? 0;

        if ($rawMaterial && $qtyToDeduct > 0) {
            $rawMaterial->quantity = max(0, $rawMaterial->quantity - $qtyToDeduct);
            $rawMaterial->save();
        }
    }
}


       

        return response()->json(['success' => 'Purchase saved and raw materials updated!']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error saving purchase: ' . $e->getMessage()]);
    }
}

    
    
    public function update(Request $request, $id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            $purchase->user_id = Auth::id();
            $purchase->receiving_location = $request->receiving_location;
            $purchase->vendors = $request->vendors;
            $purchase->invoice_no = $request->invoice_no;
            $purchase->created_at = $request->created_at ? $request->created_at : now();
            $purchase->updated_at = $request->created_at ? $request->created_at : now();
    
            $purchase->remarks = $request->remarks;
    
            $purchase->single_purchase_rate = json_encode(is_array($request->single_purchase_rate) ? $request->single_purchase_rate : [$request->single_purchase_rate]);
            $purchase->single_retail_rate = json_encode(is_array($request->single_retail_rate) ? $request->single_retail_rate : [$request->single_retail_rate]);
    
            $purchase->products = json_encode($request->products);
            $purchase->quantity = json_encode($request->quantity);
            $purchase->original_qty = json_encode($request->quantity);
            $purchase->retail_rate = json_encode($request->retail_rate);
            $purchase->purchase_rate = json_encode($request->purchase_rate);
            $purchase->totalquantity = $request->totalquantity;
            $purchase->gross_amount = $request->gross_amount;
            $purchase->discount = $request->discount ?? 0;
            $purchase->net_amount = $request->net_amount;
    
            $purchase->save();
    
            
            return response()->json(['success' => 'Purchase Edit and products updated!']);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error saving purchase: ' . $e->getMessage()]);
        }
    }

  
    public function index(Request $request)
{
    $query = Purchase::query();

    if ($request->from_date && $request->to_date) {
        $from = Carbon::parse($request->from_date)->startOfDay();
        $to = Carbon::parse($request->to_date)->endOfDay();

        $query->whereBetween('created_at', [$from, $to]);
    }

    $purchases = $query->orderBy('created_at', 'desc')->get();
    $user = Auth::user();

    return view('adminpages.purchases',['userName' => $user->name,'userEmail' => $user->email], compact('purchases'));
}

public function destroy($id)
{
    $purchase = Purchase::findOrFail($id);
    $purchase->delete();

    return response()->json(['success' => true, 'message' => 'Purchase deleted successfully.']);
}



    
}