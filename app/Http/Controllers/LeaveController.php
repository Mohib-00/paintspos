<?php

namespace App\Http\Controllers;

use App\Models\emplyees;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function employeesleave(){ 
        $user = Auth::user();      

        $leaves = Leave::orderBy('created_at', 'desc')
        ->get(); 
        $employees = emplyees::all();
    
        return view('adminpages.employeesleave', ['userName' => $user->name,'userEmail' => $user->email],compact('leaves','employees'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'employee_name' => 'nullable',
                'leave_type' => 'nullable',
                'from_date' => 'nullable',
                'to_date' => 'nullable',
                'leave_reason' => 'nullable',
            ]);
    
            $leave = new Leave();
            $leave->employee_name = $request->employee_name;
            $leave->leave_type = $request->leave_type;
            $leave->from_date = $request->from_date;
            $leave->to_date = $request->to_date;
            $leave->leave_reason = $request->leave_reason;
            $leave->save();
        
            return response()->json([
                'success' => true, 
                'leave' => $leave, 
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    
    

public function show($id)
{
    $leave = Leave::find($id);

    if (!$leave) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'leave' => $leave
    ]);
}


public function update(Request $request, $id)
{
    $leave = Leave::findOrFail($id);   

    $validator = Validator::make($request->all(), [
    'employee_name' => 'nullable',
    'leave_type' => 'nullable',
    'from_date' => 'nullable',
    'to_date' => 'nullable',
    'leave_reason' => 'nullable',
  ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

     
    if ($request->has('employee_name')) {
        $leave->employee_name = $request->employee_name;
    }
    if ($request->has('leave_type')) {
        $leave->leave_type = $request->leave_type;
    }
    if ($request->has('from_date')) {
        $leave->from_date = $request->from_date;
    }

    if ($request->has('to_date')) {
        $leave->to_date = $request->to_date;
    }

    if ($request->has('leave_reason')) {
        $leave->leave_reason = $request->leave_reason;
    }
   
     $leave->save();

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'leave' => $leave,
    ], 200);
}

public function deleteleave(Request $request)
{
    $leave = Leave::find($request->leave_id);

    if ($leave) {
        $leave->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}

}
