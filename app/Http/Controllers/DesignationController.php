<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function adddesignation(){ 
        $user = Auth::user();      
        $designations = Designation::orderBy('created_at', 'desc')
        ->get();    
        return view('adminpages.designations', ['userName' => $user->name,'userEmail' => $user->email],compact('designations'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'designation_name' => 'required',

            ]);
    
            $designation = new Designation();
            $designation->designation_name = $request->designation_name;

            
            $designation->save();
    
    
            return response()->json([
                'success' => true, 
                'designation' => $designation, 
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    
    

public function show($id)
{
    $designation = Designation::find($id);

    if (!$designation) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'designation' => $designation
    ]);
}


public function update(Request $request, $id)
{
    $designation = Designation::findOrFail($id);   

    $validator = Validator::make($request->all(), [
      'designation_name' => 'nullable|string|max:255',
    
  ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

     
    if ($request->has('designation_name')) {
        $designation->designation_name = $request->designation_name;
    }
    
   
     $designation->save();

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'designation' => $designation,
    ], 200);
}

public function deletedesignation(Request $request)
{
    $designation = Designation::find($request->designation_id);

    if ($designation) {
        $designation->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}

}
