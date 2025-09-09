<?php

namespace App\Http\Controllers;

use App\Models\ManufactureCategory;
use App\Models\ManufactureCompany;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RawMaterialController extends Controller
{
     public function addrawmaterial(){ 
        $user = Auth::user();      
        $categorys = ManufactureCategory::all();
        $rawmaterials = RawMaterial::all();
        $brands = ManufactureCompany::all();  
        return view('adminpages.rawmaterials', ['userName' => $user->name,'userEmail' => $user->email],compact('categorys','brands','rawmaterials'));
    }

     public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'brand_name' => 'nullable|string',
            'category_name' => 'nullable|string',
            'item_name' => 'nullable|string',
            'purchase_rate' => 'nullable|numeric', 
            'quantity' => 'nullable|',
        ]);
        

        $rawmaterial = new RawMaterial();
        $rawmaterial->brand_name = $request->brand_name;
        $rawmaterial->category_name = $request->category_name;
        $rawmaterial->item_name = $request->item_name;
        $rawmaterial->quantity = $request->quantity;
        $rawmaterial->purchase_rate = $request->purchase_rate;
        $rawmaterial->save();

        return response()->json([
            'success' => true,
            'rawmaterial' => $rawmaterial,
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


public function show($id)
{
    $rawmaterial = RawMaterial::find($id);

    if (!$rawmaterial) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'rawmaterial' => $rawmaterial
    ]);
}

public function update(Request $request, $id)
{
    $rawmaterial = RawMaterial::findOrFail($id);   

    $validator = Validator::make($request->all(), [
        'brand_name' => 'nullable|string',
        'category_name' => 'nullable|string',
        'item_name' => 'nullable|string',
        'purchase_rate' => 'nullable|numeric', 
        'quantity' => 'nullable',
    ]);

    if ($request->has('brand_name')) {
        $rawmaterial->brand_name = $request->brand_name;
    }
    if ($request->has('category_name')) {
        $rawmaterial->category_name = $request->category_name;
    }
  
    if ($request->has('item_name')) {
        $rawmaterial->item_name = $request->item_name;
    }
  
    if ($request->has('quantity')) {
        $rawmaterial->quantity = $request->quantity;
    }

     if ($request->has('purchase_rate')) {
        $rawmaterial->purchase_rate = $request->purchase_rate;
    }

    $rawmaterial->save();


    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'rawmaterial' => $rawmaterial,
    ], 200);
}



public function deleterawmaterial(Request $request)
{
    $rawmaterial = RawMaterial::find($request->rawmaterial_id);

    if ($rawmaterial) {
        $rawmaterial->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}


 public function rawmaterialsss($id)
    {
        $product = RawMaterial::find($id);
        
        if(!$product) {
            return response()->json(['error' => 'Raw Material Product not found'], 404);
        }
        
        return response()->json([
            'id' => $product->id,
            'item_name' => $product->item_name,
            'quantity' => $product->quantity,
       
            'purchase_rate' => $product->purchase_rate,
        ]);
    }


}
