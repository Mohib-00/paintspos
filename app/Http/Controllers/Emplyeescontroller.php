<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\emplyees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Emplyeescontroller extends Controller
{
    public function employeeslist(){ 
        $user = Auth::user();      

        $employees = emplyees::orderBy('created_at', 'desc')
        ->get(); 
        $designations =Designation::all(); 
    
        return view('adminpages.employees', ['userName' => $user->name,'userEmail' => $user->email],compact('employees','designations'));
    }
    public function addemployee(){ 
        $user = Auth::user();  
        $designations =Designation::all();    
        return view('adminpages.addemployee', ['userName' => $user->name,'userEmail' => $user->email],compact('designations'));
    }



    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'employee_name' => 'nullable|string|max:255',
                'area_id' => 'nullable',
                'phone_1' => 'nullable|string|max:255',
                'phone_2' => 'nullable|string|max:255',
                'client_gender' => 'nullable|string|max:255',
                'client_cnic' => 'nullable|string|max:255',
                'client_father_name' => 'nullable|string|max:255',
                'client_residence' => 'nullable|string|max:255',
                'client_salary' => 'nullable|numeric',
                'client_permanent_address' => 'nullable|string|max:255',
                'client_residential_address' => 'nullable|string|max:255',
            ]);
    
            $employee = new emplyees();
            $employee->employee_name = $validatedData['employee_name'] ?? null;
            $employee->area_id = $validatedData['area_id'] ?? null;
            $employee->assigned_user_id = auth()->id(); 
            $employee->phone_1 = $validatedData['phone_1'] ?? null;
            $employee->phone_2 = $validatedData['phone_2'] ?? null;
            $employee->client_gender = $validatedData['client_gender'] ?? null;
            $employee->client_cnic = $validatedData['client_cnic'] ?? null;
            $employee->client_father_name = $validatedData['client_father_name'] ?? null;
            $employee->client_residence = $validatedData['client_residence'] ?? null;
            $employee->client_salary = $validatedData['client_salary'] ?? null;
            $employee->client_permanent_address = $validatedData['client_permanent_address'] ?? null;
            $employee->client_residential_address = $validatedData['client_residential_address'] ?? null;
    
            $employee->save();
    
            return response()->json([
                'success' => true,
                'employee' => $employee,
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    

public function show($id)
{
    $employee = emplyees::find($id);

    if (!$employee) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'employee' => $employee
    ]);
}


public function update(Request $request, $id)
{
    try {
        $employee = emplyees::findOrFail($id);

        $validator = Validator::make($request->all(), [
          'employee_name' => 'nullable|string|max:255',
                'area_id' => 'nullable',
                'phone_1' => 'nullable|string|max:255',
                'phone_2' => 'nullable|string|max:255',
                'client_gender' => 'nullable|string|max:255',
                'client_cnic' => 'nullable|string|max:255',
                'client_father_name' => 'nullable|string|max:255',
                'client_residence' => 'nullable|string|max:255',
                'client_salary' => 'nullable|numeric',
                'client_permanent_address' => 'nullable|string|max:255',
                'client_residential_address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('employee_name')) {
            $employee->employee_name = $request->employee_name;
        }
        if ($request->has('area_id')) {
            $employee->area_id = $request->area_id;
        }
    
        if ($request->has('phone_1')) {
            $employee->phone_1 = $request->phone_1;
        }
        if ($request->has('phone_2')) {
            $employee->phone_2 = $request->phone_2;
        }
        if ($request->has('client_gender')) {
            $employee->client_gender = $request->client_gender;
        }
        if ($request->has('client_cnic')) {
            $employee->client_cnic = $request->client_cnic;
        }
        if ($request->has('client_father_name')) {
            $employee->client_father_name = $request->client_father_name;
        }
        if ($request->has('client_residence')) {
            $employee->client_residence = $request->client_residence;
        }
     
        if ($request->has('client_salary')) {
            $employee->client_salary = $request->client_salary;
        }
      
     
        if ($request->has('client_permanent_address')) {
            $employee->client_permanent_address = $request->client_permanent_address;
        }
        if ($request->has('client_residential_address')) {
            $employee->client_residential_address = $request->client_residential_address;
        }
    

        $employee->save();

        $user_name = $employee->assignedUser ? $employee->assignedUser->name : 'N/A';

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'employee' => $employee,
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function deleteemployee(Request $request)
{
    $employee = emplyees::find($request->employee_id);

    if ($employee) {
        $employee->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}

}
