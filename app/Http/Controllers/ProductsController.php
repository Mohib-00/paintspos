<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function addproduct(){ 
        $user = Auth::user();      
        $categorys = Category::all();
        $products = Product::all();
        $brands = Company::all();  
        $subs = SubCategory::all();
        return view('adminpages.products', ['userName' => $user->name,'userEmail' => $user->email],compact('categorys','brands','products','subs'));
    }


    public function productpricelist(){ 
        $user = Auth::user();      
        $products = Product::all();
        return view('adminpages.productsprice', ['userName' => $user->name,'userEmail' => $user->email],compact('products'));
    }

    public function productimport(){ 
        $user = Auth::user();      
        return view('adminpages.import', ['userName' => $user->name,'userEmail' => $user->email]);
    }
    
  public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'brand_name' => 'nullable|string',
            'category_name' => 'nullable|string',
            'subcategory_name' => 'nullable|string',
            'item_name' => 'required|string',
            'barcode' => 'required|string',
            'purchase_rate' => 'required|numeric', 
            'retail_rate' => 'required|numeric', 
            'quantity' => 'nullable|numeric',
            'image' => 'nullable|image',
            'shade' => 'nullable|string', 
            'code' => 'nullable|string',
        ]);

        $userId = Auth::id();
        $savedProducts = [];

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        if ($request->filled('shade')) {
            $units = ['Gallon', 'Quarter', 'Drummy'];

            foreach ($units as $unit) {
                $product = new Product();
                $product->brand_name = $request->brand_name;
                $product->category_name = $request->category_name;
                $product->subcategory_name = $request->subcategory_name;
                $product->item_name = $request->item_name . ' - ' . $unit;
                $product->barcode = $request->barcode . '-' . strtolower($unit);

                $product->purchase_rate = $request->purchase_rate * $request->quantity;
                $product->retail_rate   = $request->retail_rate * $request->quantity;
                $product->quantity      = $request->quantity;
                $product->single_purchase_rate = $request->purchase_rate;
                $product->single_retail_rate   = $request->retail_rate;
                $product->user_id = $userId;
                $product->shade   = $request->shade;
                $product->code    = $request->code;
                $product->image   = $imageName;

                $product->save();
                $savedProducts[] = $product;
            }
        } else {
            $product = new Product();
            $product->brand_name = $request->brand_name;
            $product->category_name = $request->category_name;
            $product->subcategory_name = $request->subcategory_name;
            $product->item_name = $request->item_name;
            $product->barcode = $request->barcode;

            $product->purchase_rate = $request->purchase_rate * $request->quantity;
            $product->retail_rate   = $request->retail_rate * $request->quantity;
            $product->quantity      = $request->quantity;
            $product->single_purchase_rate = $request->purchase_rate;
            $product->single_retail_rate   = $request->retail_rate;
            $product->user_id = $userId;
            $product->shade   = $request->shade;
            $product->code    = $request->code;
            $product->image   = $imageName;

            $product->save();
            $savedProducts[] = $product;
        }

        return response()->json([
            'success' => true,
            'products' => $savedProducts, 
            'user_name' => Auth::user()->name
        ]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    

public function show($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'product' => $product
    ]);
}


public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);   

    $validator = Validator::make($request->all(), [
        'brand_name' => 'nullable|string|max:255',
        'category_name' => 'nullable|string|max:255',
        'subcategory_name' => 'nullable|string|max:255',
        'item_name' => 'nullable|string|max:255',
        'barcode' => 'nullable|string|max:255',
        'purchase_rate' => 'nullable|numeric', 
        'retail_rate' => 'nullable|numeric',   
        'quantity' => 'nullable|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $quantity = $request->has('quantity') ? $request->quantity : $product->quantity;
    $unitPurchaseRate = $request->has('purchase_rate') ? $request->purchase_rate : $product->single_purchase_rate;
    $unitRetailRate = $request->has('retail_rate') ? $request->retail_rate : $product->single_retail_rate;

   

    $totalPurchaseRate = $unitPurchaseRate * $quantity;
    $totalRetailRate = $unitRetailRate * $quantity;

    if ($request->has('brand_name')) {
        $product->brand_name = $request->brand_name;
    }
    if ($request->has('category_name')) {
        $product->category_name = $request->category_name;
    }
    if ($request->has('subcategory_name')) {
        $product->subcategory_name = $request->subcategory_name;
    }
    if ($request->has('item_name')) {
        $product->item_name = $request->item_name;
    }
    if ($request->has('barcode')) {
        $product->barcode = $request->barcode;
    }
  
    if ($request->has('quantity')) {
        $product->quantity = $quantity;
    }
    if ($request->has('code')) {
        $product->code =  $request->code;
    }
    if ($request->has('shade')) {
        $product->shade =  $request->shade;
    }

    if ($request->hasFile('image')) {
        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }

        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        $product->image = $imageName;
    }

    $product->single_purchase_rate = $unitPurchaseRate;
    $product->single_retail_rate = $unitRetailRate;
    $product->purchase_rate = $totalPurchaseRate;
    $product->retail_rate = $totalRetailRate;

    $product->save();

    $user_name = $product->user ? $product->user->name : 'N/A';

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'product' => $product,
        'user_name' => $user_name,
    ], 200);
}



public function deleteproduct(Request $request)
{
    $product = Product::find($request->product_id);

    if ($product) {
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}

public function addOpeningQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'opening_qty' => 'required|numeric|min:0'
        ]);

        $product = Product::find($request->product_id);

        $product->opening_quantity = $request->opening_qty;
        $product->save();

        return response()->json(['success' => true]);
    }

    public function updateOpeningQuantity(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'opening_qty' => 'required|numeric|min:0'
    ]);

    $product = Product::find($request->product_id);
    $product->opening_quantity = $request->opening_qty;
    $product->save();

    return response()->json(['success' => true]);
}

public function importCSV(Request $request)
{
    if (!$request->hasFile('excelFile')) {
        return response()->json(['message' => 'No file selected'], 400);
    }

    $file = $request->file('excelFile');

    if (!in_array($file->getClientOriginalExtension(), ['csv'])) {
        return response()->json(['message' => 'Only CSV files are allowed'], 400);
    }

    $handle = fopen($file->getRealPath(), 'r');
    $header = true;

    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
        if ($header) {
            $header = false;
            continue;
        }

        if (empty(array_filter($row))) {
            continue;
        }

        if (count($row) < 8) {
            continue;
        }

        $unit_purchase_rate = is_numeric($row[7]) ? $row[7] : 0;
        $unit_retail_rate   = is_numeric($row[8]) ? $row[8] : 0;
        $quantity           = is_numeric($row[9]) ? $row[9] : 0;

        Product::create([
            'brand_name'           => $row[0] ?? null,
            'category_name'        => $row[1] ?? null,
            'subcategory_name'     => $row[2] ?? null,
            'item_name'            => $row[3] ?? null,
            'shade'                => $row[4] ?? null,
            'code'                 => $row[5] ?? null,
            'barcode'              => $row[6] ?? null,
            'quantity'             => $quantity,
            'single_purchase_rate' => $unit_purchase_rate,
            'single_retail_rate'   => $unit_retail_rate,
            'user_id'              => Auth::id(),
        ]);
    }

    fclose($handle);

    return response()->json(['message' => 'CSV imported successfully!']);
}




public function getUpdatedPrice($productId, Request $request)
{
    $product = Product::find($productId);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found'
        ]);
    }

    $quantity = (int) $request->query('quantity', 1);
    $price = $product->price * $quantity; 
    $retailRate = $product->retail_rate * $quantity; 
    $wholesaleRate = $product->wholesale_rate * $quantity; 
    $miniWholesaleRate = $product->mini_whole_rate * $quantity; 
    $typeARate = $product->type_a_rate * $quantity; 
    $typeBRate = $product->type_b_rate * $quantity; 
    $typeCRate = $product->type_c_rate * $quantity; 

    $amount = $price; 

    return response()->json([
        'success' => true,
        'product' => [
            'price' => $price,
            'retail_rate' => $retailRate,
            'wholesale_rate' => $wholesaleRate,
            'mini_whole_rate' => $miniWholesaleRate,
            'type_a_rate' => $typeARate,
            'type_b_rate' => $typeBRate,
            'type_c_rate' => $typeCRate,
            'amount' => $amount
        ]
    ]);
}
public function getProductData($id)
{
    $product = Product::find($id);

    if ($product) {
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Product not found'
    ]);

}

public function updateInline(Request $request)
{
    $product = Product::find($request->id);

    if (!$product) {
        return response()->json(['status' => 'error', 'message' => 'Product not found.']);
    }

    $column = $request->column;
    $value = $request->value;

    if (!in_array($column, ['quantity', 'purchase_rate', 'retail_rate', 'item_name', 'barcode', 'single_purchase_rate', 'single_retail_rate'])) {
        return response()->json(['status' => 'error', 'message' => 'Invalid column.']);
    }

    $previous_quantity = $product->quantity ?? 0;
    $previous_purchase_rate = $product->purchase_rate ?? 0;
    $previous_retail_rate = $product->retail_rate ?? 0;

    $product->$column = $value;

    if ($column == 'quantity') {
        $new_quantity = (float) $value;

        if ($new_quantity > 0) {
            if ($previous_quantity > 0) {
                $product->purchase_rate = $previous_purchase_rate * ($new_quantity / $previous_quantity);
                $product->retail_rate = $previous_retail_rate * ($new_quantity / $previous_quantity);
            } else {
                $product->purchase_rate = $product->single_purchase_rate * $new_quantity;
                $product->retail_rate = $product->single_retail_rate * $new_quantity;
            }

            $product->single_purchase_rate = $product->purchase_rate / $new_quantity;
            $product->single_retail_rate = $product->retail_rate / $new_quantity;
        } else {
            $product->single_purchase_rate = 0;
            $product->single_retail_rate = 0;
            $product->purchase_rate = 0;
            $product->retail_rate = 0;
        }
    }

    if ($column == 'single_purchase_rate') {
        $product->purchase_rate = $value * $product->quantity;
    }

    if ($column == 'single_retail_rate') {
        $product->retail_rate = $value * $product->quantity;
    }

    if (in_array($column, ['purchase_rate', 'retail_rate'])) {
        $product->single_purchase_rate = $product->quantity > 0 ? $product->purchase_rate / $product->quantity : 0;
        $product->single_retail_rate = $product->quantity > 0 ? $product->retail_rate / $product->quantity : 0;
    }

    $product->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Product updated successfully.',
        'quantity' => $product->quantity,
        'single_purchase_rate' => round($product->single_purchase_rate, 2),
        'single_retail_rate' => round($product->single_retail_rate, 2),
        'purchase_rate' => round($product->purchase_rate, 2),
        'retail_rate' => round($product->retail_rate, 2)
    ]);
}






public function getProduct($id)
{
    $product = Product::findOrFail($id);

    $quantity = $product->quantity;
    $purchaseRate = $product->purchase_rate;
    $retailRate = $product->retail_rate;
    $singlePurchaseRate = $product->single_purchase_rate;
    $singleRetailRate = $product->single_retail_rate;

    return response()->json([
        'success' => true,
        'data' => [
            'quantity' => $quantity,
            'purchase_rate' => $purchaseRate,
            'retail_rate' => $retailRate,
            'single_purchase_rate' => $singlePurchaseRate,
            'single_retail_rate' => $singleRetailRate,
        ]
    ]);
}


public function getByBarcode($barcode)
{
    $product = Product::where('barcode', $barcode)->first();

    if ($product) {
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'item_name' => $product->item_name,
                'shade' => $product->shade,
            ]
        ]);
    }

    return response()->json(['success' => false]);
}

 public function getProductsssssssssssss($id)
    {
        $product = Product::find($id);
        
        if(!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        
        return response()->json([
            'id' => $product->id,
            'item_name' => $product->item_name,
            'quantity' => $product->quantity,
            'single_retail_rate' => $product->single_retail_rate,
            'single_purchase_rate' => $product->single_purchase_rate,
            'retail_rate' => $product->retail_rate,
            'purchase_rate' => $product->purchase_rate,
        ]);
    }

}