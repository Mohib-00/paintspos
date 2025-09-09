<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\DealItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DealListController extends Controller
{
    public function deallist(){ 
    $user = Auth::user();
    $products = Product::all();
    $deals = Deal::withCount('dealItems')
    ->orderBy('created_at', 'desc') 
    ->get();

    return view('adminpages.deallist',compact('products','deals'), ['userName' => $user->name,'userEmail' => $user->email]);
  }

    public function adddeal(){ 
    $user = Auth::user();
    $products = Product::all();
    return view('adminpages.adddeal',compact('products'), ['userName' => $user->name,'userEmail' => $user->email]);
  }

   public function store(Request $request)
    {
        $request->validate([
            'deal_name' => 'required|string|max:255',
            'deal_price' => 'required|numeric',
            'products' => 'required|array|min:1',
            'products.*' => 'required',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'single_purchase_rate' => 'required|array',
            'single_purchase_rate.*' => 'required|numeric|min:0',
            'single_retail_rate' => 'required|array',
            'single_retail_rate.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $deal = Deal::create([
                'deal_name' => $request->deal_name,
                'deal_price' => $request->deal_price,
                'remarks' => $request->remarks,
            ]);

           foreach ($request->products as $index => $productId) {
            $product = Product::find($productId);
            if ($product) {
             DealItem::create([
            'deal_id' => $deal->id,
            'products' => $product->item_name, 
            'quantity' => $request->quantity[$index],
            'single_purchase_rate' => $request->single_purchase_rate[$index],
            'single_retail_rate' => $request->single_retail_rate[$index],
        ]);
      }
    }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Deal saved successfully!']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }


    public function destroy($id)
{
    $deal = Deal::findOrFail($id);
    $deal->delete();

    return response()->json(['message' => 'Deal deleted successfully.']);
}


public function editdeallist($id)
{
    $user = Auth::user();
    
    $products = Product::all();

    $deals = Deal::with(['dealItems'])->findOrFail($id);

    if (!$deals) {
        return redirect()->back()->with('error', 'Voucher not found.');
    }

    return view('adminpages.editdeal',compact('products'), [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'deals' => $deals
    ]);
}

public function update(Request $request, $id)
{
    $request->validate([
        'deal_name' => 'required|string|max:255',
        'deal_price' => 'required|numeric',
        'products' => 'required|array|min:1',
        'products.*' => 'required|string',
        'quantity' => 'required|array',
        'quantity.*' => 'required|integer|min:1',
        'single_purchase_rate' => 'required|array',
        'single_purchase_rate.*' => 'required|numeric|min:0',
        'single_retail_rate' => 'required|array',
        'single_retail_rate.*' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $deal = Deal::findOrFail($id);
        $deal->deal_name = $request->deal_name;
        $deal->deal_price = $request->deal_price;
        $deal->remarks = $request->remarks;
        $deal->save();

        $existingItemIds = DealItem::where('deal_id', $id)->pluck('id')->toArray();
        $formItemIds = array_filter($request->input('item_ids', []));

        $itemsToDelete = array_diff($existingItemIds, $formItemIds);
        if (!empty($itemsToDelete)) {
            DealItem::whereIn('id', $itemsToDelete)->delete();
        }

        $products = array_values($request->input('products'));
        $quantities = array_values($request->input('quantity'));
        $purchaseRates = array_values($request->input('single_purchase_rate'));
        $retailRates = array_values($request->input('single_retail_rate'));

        foreach ($products as $index => $productName) {
            $data = [
                'deal_id' => $id,
                'products' => $productName,
                'quantity' => $quantities[$index],
                'single_purchase_rate' => $purchaseRates[$index],
                'single_retail_rate' => $retailRates[$index],
            ];

            if (isset($formItemIds[$index]) && !empty($formItemIds[$index])) {
                DealItem::where('id', $formItemIds[$index])->update($data);
            } else {
                DealItem::create($data);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Deal updated successfully',
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Deal update failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Deal update failed.',
            'error' => $e->getMessage(),
        ], 500);
    }
}




}
