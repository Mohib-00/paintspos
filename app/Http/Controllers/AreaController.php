<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function areas(){ 
        $user = Auth::user();      
        $areas = Area::orderBy('created_at', 'desc')
        ->get();    
        return view('adminpages.area', ['userName' => $user->name,'userEmail' => $user->email],compact('areas'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'area_name' => 'required',
            ]);
    
            $area = new Area();
            $area->area_name = $request->area_name;
            $area->save();
    
    
            return response()->json([
                'success' => true, 
                'area' => $area, 
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    
    

public function show($id)
{
    $area = Area::find($id);

    if (!$area) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'area' => $area
    ]);
}


public function update(Request $request, $id)
{
    $area = Area::findOrFail($id);   

    $validator = Validator::make($request->all(), [
      'area_name' => 'nullable|string|max:255',
  ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

     
    if ($request->has('area_name')) {
        $area->area_name = $request->area_name;
    }
    
   
     $area->save();

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'area' => $area,
    ], 200);
}

public function deletearea(Request $request)
{
    $area = Area::find($request->area_id);

    if ($area) {
        $area->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}
}
