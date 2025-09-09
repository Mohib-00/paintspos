<?php

namespace App\Http\Controllers;

use App\Models\ManufactureCategory;
use App\Models\ManufactureCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ManufactureCategoryController extends Controller
{
     public function addmanufacturecategory(){ 
        $user = Auth::user();      
        $categorys = ManufactureCategory::orderBy('created_at', 'desc')
        ->get();  
        $brands = ManufactureCompany::all();  
        return view('adminpages.manufacturecategorys', ['userName' => $user->name,'userEmail' => $user->email],compact('categorys','brands'));
    }

   public function storemanufacturecategory(Request $request)
{
    try {
        $validatedData = $request->validate([
            'brand_name' => 'required',
            'category_name' => 'required',
            'image' => 'nullable',
        ]);

        $category = new ManufactureCategory();
        $category->brand_name = $request->brand_name;
        $category->category_name = $request->category_name;
        $category->user_id = Auth::id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        $user = \App\Models\User::find($category->user_id); 

        return response()->json([
            'success' => true, 
            'category' => $category, 
            'user_name' => $user ? $user->name : 'Unknown'
        ]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    
    
    

public function showmanufacturecategory($id)
{
    $category = ManufactureCategory::find($id);

    if (!$category) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'category' => $category
    ]);
}


public function updatemanufacturecategory(Request $request, $id)
{
    $category = ManufactureCategory::findOrFail($id);   

    $validator = Validator::make($request->all(), [
        'brand_name' => 'nullable|string|max:255',
        'category_name' => 'nullable|string|max:255',
        'image' => 'nullable',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    if ($request->has('brand_name')) {
        $category->brand_name = $request->brand_name;
    }

    if ($request->has('category_name')) {
        $category->category_name = $request->category_name;
    }

    if ($request->hasFile('image')) {
        if ($category->image && file_exists(public_path('images/' . $category->image))) {
            unlink(public_path('images/' . $category->image));
        }

        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        $category->image = $imageName;
    }

    $category->save();

    $user_name = $category->user ? $category->user->name : 'N/A';

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'category' => $category,
        'user_name' => $user_name,
    ], 200);
}


public function deletemanufacturecategory(Request $request)
{
    $category = ManufactureCategory::find($request->category_id);

    if ($category) {
        $category->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}
}
