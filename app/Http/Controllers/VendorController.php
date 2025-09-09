<?php

namespace App\Http\Controllers;

use App\Models\Vendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function addvendor(){ 
        $user = Auth::user();      
        $vendors = Vendors::orderBy('created_at', 'desc')
        ->get();    
        return view('adminpages.vendors', ['userName' => $user->name,'userEmail' => $user->email],compact('vendors'));
    }

   public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'name' => [
    'required',
    'string',
    'max:255',
    function ($attribute, $value, $fail) {
        
        if (preg_match('/[\[\]\(\)]/', $value)) {
            $fail('The vendor name must not contain brackets.');
        }
    },
],
            'mobile' => 'nullable',
            'address' => 'nullable',
        ]);

        $existingVendor = Vendors::whereRaw('LOWER(name) = ?', [strtolower($request->name)])->first();

        if ($existingVendor) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'name' => ['The name has already been taken.']
                ]
            ], 422);
        }

        $vendor = new Vendors();
        $vendor->name = $request->name;
        $vendor->mobile = $request->mobile;
        $vendor->address = $request->address;
        $vendor->user_id = Auth::id();
        $vendor->save();

        \App\Models\AddAccount::create([
            'head_name' => 'Accounts Payable',
            'sub_head_name' => $vendor->name,
            'child' => 'Accounts Payable',
        ]);

        $user = \App\Models\User::find($vendor->user_id);

        return response()->json([
            'success' => true,
            'vendor' => $vendor,
            'user_name' => $user ? $user->name : 'Unknown'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    
public function show($id)
{
    $vendor = Vendors::find($id);

    if (!$vendor) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'vendor' => $vendor
    ]);
}


public function update(Request $request, $id)
{
    try {
        $vendor = Vendors::findOrFail($id);   

        $validator = Validator::make($request->all(), [
             'name' => [
    'nullable',
    'string',
    'max:255',
    function ($attribute, $value, $fail) {
        
        if (preg_match('/[\[\]\(\)]/', $value)) {
            $fail('The vendor name must not contain brackets.');
        }
    },
],
            'mobile' => 'nullable|string|max:255',
            'address' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('name')) {
            $existingVendor = Vendors::whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                ->where('id', '!=', $id)
                ->first();

            if ($existingVendor) {
                return response()->json([
                    'errors' => [
                        'name' => ['The name has already been taken.']
                    ]
                ], 422);
            }

            $vendor->name = $request->name;

            \App\Models\AddAccount::where('sub_head_name', $vendor->getOriginal('name'))
                ->update(['sub_head_name' => $request->name]);
        }

        if ($request->has('mobile')) {
            $vendor->mobile = $request->mobile;
        }

        if ($request->has('address')) {
            $vendor->address = $request->address;
        }

        $vendor->save();

        $user_name = $vendor->user ? $vendor->user->name : 'N/A';

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'vendor' => $vendor,
            'user_name' => $user_name,
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['errors' => ['server' => $e->getMessage()]], 500);
    }
}


public function deletevendor(Request $request)
{
    try {
        $vendor = Vendors::find($request->vendor_id);

        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor not found']);
        }

        $addAccount = \App\Models\AddAccount::where('sub_head_name', $vendor->name)->first();

        if ($addAccount) {
            $isUsedInGRN = \App\Models\GrnAccount::where('vendor_account_id', $addAccount->id)->exists();

            if ($isUsedInGRN) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete vendor: This vendor is in use in accounts.'
                ]);
            }

            $addAccount->delete();
        }

        $vendor->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}



}
