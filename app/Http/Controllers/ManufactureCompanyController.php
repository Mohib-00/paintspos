<?php

namespace App\Http\Controllers;

use App\Models\ManufactureCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ManufactureCompanyController extends Controller
{
     public function addmanufacturecompany(){ 
        $user = Auth::user();      
        $companys = ManufactureCompany::orderBy('created_at', 'desc')
        ->get();    
        return view('adminpages.addcompanycompany', [
            'userName' => $user->name,
            'userEmail' => $user->email,
            'companys' => $companys
        ]);
            }

 public function storemanufacturecompany(Request $request)
{
    try {
        $validatedData = $request->validate([
            'designation_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable',
        ]);

        $company = new ManufactureCompany();
        $company->designation_name = $request->designation_name;
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->user_id = Auth::id();

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'), $imageName);
            $company->image = $imageName;
        }

        $company->save();

        $user = \App\Models\User::find($company->user_id);

        return response()->json([
            'success' => true,
            'company' => $company,
            'user_name' => $user ? $user->name : 'Unknown'
        ]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    
    
    

public function manufacturecompanyshow($id)
{
    $company = ManufactureCompany::find($id);

    if (!$company) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'company' => $company
    ]);
}


public function manufacturecompanyupdate(Request $request, $id)
{
    $company = ManufactureCompany::findOrFail($id);   

    $validator = Validator::make($request->all(), [
        'designation_name' => 'nullable|string|max:255',
        'address' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'image' => 'nullable',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    if ($request->has('designation_name')) {
        $company->designation_name = $request->designation_name;
    }

    if ($request->has('address')) {
        $company->address = $request->address;
    }

    if ($request->has('phone')) {
        $company->phone = $request->phone;
    }

    if ($request->hasFile('image')) {
        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('images'), $imageName);
        $company->image = $imageName;
    }

    $company->save();

    $user_name = $company->user ? $company->user->name : 'N/A';

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'company' => $company,
        'user_name' => $user_name,
    ], 200);
}


public function deletemanufacturecompany(Request $request)
{
    $company = ManufactureCompany::find($request->company_id);

    if ($company) {
        $company->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}
}
