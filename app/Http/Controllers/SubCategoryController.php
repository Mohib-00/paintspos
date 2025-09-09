<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function addsubcategory(){ 
        $user = Auth::user();      
        $categorys = Category::all();
        $subs = SubCategory::all();
        $brands = Company::all();  
        return view('adminpages.subcategory', ['userName' => $user->name,'userEmail' => $user->email],compact('categorys','brands','subs'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'brand_name' => 'required',
                'category_name' => 'required',
                'subcategory_name' => 'required',
            ]);
    
            $sub = new SubCategory();
            $sub->brand_name = $request->brand_name;
            $sub->category_name = $request->category_name;
            $sub->subcategory_name = $request->subcategory_name;
            $sub->user_id = Auth::id();
            
            $sub->save();
    
            $user = \App\Models\User::find($sub->user_id); 
    
            return response()->json([
                'success' => true, 
                'sub' => $sub, 
                'user_name' => $user ? $user->name : 'Unknown'
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    
    

public function show($id)
{
    $sub = SubCategory::find($id);

    if (!$sub) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'sub' => $sub
    ]);
}


public function update(Request $request, $id)
{
    $sub = SubCategory::findOrFail($id);   

    $validator = Validator::make($request->all(), [
      'brand_name' => 'nullable|string|max:255',
      'category_name' => 'nullable|string|max:255',
      'subcategory_name' => 'nullable|string|max:255',
  ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

     
    if ($request->has('brand_name')) {
        $sub->brand_name = $request->brand_name;
    }
    if ($request->has('category_name')) {
        $sub->category_name = $request->category_name;
    }
    if ($request->has('subcategory_name')) {
        $sub->subcategory_name = $request->subcategory_name;
    }
    
    $user_name = $sub->user ? $sub->user->name : 'N/A';
   
     $sub->save();

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'sub' => $sub,
        'user_name' => $user_name,
    ], 200);
}

public function deletedeletesub(Request $request)
{
    $sub = SubCategory::find($request->sub_id);

    if ($sub) {
        $sub->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}
}
