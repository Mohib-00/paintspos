<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\emplyees;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function employeeattendance(){ 
        $user = Auth::user();      
        $employees = emplyees::orderBy('created_at', 'desc')
        ->get();    
        return view('adminpages.employeeattendance', ['userName' => $user->name,'userEmail' => $user->email],compact('employees'));
    }

    public function markAttendance(Request $request)
{
    $employee = emplyees::findOrFail($request->employee_id);

    $attendance = new Attendance();
    $attendance->user_id = $employee->id;
    $attendance->status = $request->status; 
    $attendance->save();

    return response()->json(['success' => true, 'message' => 'Attendance marked successfully']);
}
public function attendancereport(){ 
    $user = Auth::user();      
    $attendances = Attendance::whereDate('attendances.created_at', Carbon::today())
    ->join('emplyees', 'attendances.user_id', '=', 'emplyees.id')
    ->select('attendances.*', 'emplyees.employee_name', 'emplyees.area_id',) 
    ->orderBy('attendances.created_at', 'desc')
    ->get();
    $employes = emplyees::orderBy('employee_name')->get();
    return view('adminpages.employeeattendancereport', ['userName' => $user->name,'userEmail' => $user->email],compact('attendances','employes'));
}

public function report(Request $request)
{
    $query = Attendance::join('emplyees', 'attendances.user_id', '=', 'emplyees.id')
        ->select('attendances.*', 'emplyees.employee_name', 'emplyees.area_id');

    if ($request->from_date) {
        $query->whereDate('attendances.created_at', '>=', $request->from_date);
    }

    if ($request->to_date) {
        $query->whereDate('attendances.created_at', '<=', $request->to_date);
    }

    if ($request->employee_id) {
        $query->where('user_id', $request->employee_id);
    }

    $attendances = $query->orderBy('attendances.created_at', 'desc')->get();

    $employes = emplyees::orderBy('employee_name')->get();
    $user = Auth::user(); 

    return view('adminpages.employeeattendancereport',['userName' => $user->name,'userEmail' => $user->email], compact('attendances', 'employes'));
}
public function mark(Request $request)
{
    $request->validate([
        'employee_ids' => 'required|array',
        'status' => 'required|in:present,absent',
    ]);

    foreach ($request->employee_ids as $id) {
        Attendance::create([
            'user_id' => $id,
            'status' => $request->status,
        ]);
    }

    return response()->json(['message' => 'Attendance marked successfully!']);
}

}
