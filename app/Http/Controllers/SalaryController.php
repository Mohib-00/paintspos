<?php

namespace App\Http\Controllers;

use App\Models\emplyees;
use App\Models\Fine;
use App\Models\GrnAccount;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
  public function salarys()
{
    $user = Auth::user();
    $today = Carbon::today()->toDateString();

    $employees = emplyees::with(['salaries' => function($query) use ($today) {
        //$query->whereDate('created_at', $today);
    }])->get();

    return view('adminpages.salary', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'employees' => $employees,
    ]);
}

public function paysalary($id)
{
    $user = Auth::user();
    $today = Carbon::today()->toDateString();

    $employee = emplyees::with(['salaries' => function($query) use ($today) {
        //$query->whereDate('created_at', $today);
    }])->find($id);

    if (!$employee) {
        return redirect()->back()->with('error', 'Employee not found.');
    }

    return view('adminpages.paysalrytouser', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'employees' => $employee,
    ]);
}
public function storeSalary(Request $request, $employee_id)
{
    $request->validate([
        'paid' => 'required|numeric',
    ]);

    $today = Carbon::today()->toDateString();

    $salary = Salary::where('employee_id', $employee_id)
                    ->whereDate('created_at', $today)
                    ->first();

    if ($salary) {
        $salary->paid = $request->paid;
        $salary->save();
    } else {
        $salary = Salary::create([
            'employee_id' => $employee_id,
            'paid' => $request->paid,
        ]);
    }

    $salaryId = $salary->id;

    $salaryExpenseAccount = DB::table('add_accounts')
                              ->where('sub_head_name', 'Salary Expense')
                              ->first();

    if (!$salaryExpenseAccount) {
        return response()->json(['success' => false, 'message' => 'Salary Expense account not found.'], 404);
    }

    DB::table('grn_accounts')->insert([
        'vendor_account_id' => $salaryExpenseAccount->id,
        'debit' => $request->paid,
        'salary' => 'salary',
        'salary_id' => $salaryId, 
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $cashinhandAccount = DB::table('add_accounts')
                           ->where('sub_head_name', 'Cash In Hand')
                           ->first();

    if (!$cashinhandAccount) {
        return response()->json(['success' => false, 'message' => 'Cash In Hand account not found.'], 404);
    }

    DB::table('grn_accounts')->insert([
        'vendor_account_id' => $cashinhandAccount->id,
        'vendor_net_amount' => $request->paid,
        'salary' => 'salary',
        'salary_id' => $salaryId, 
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Salary paid successfully!']);
}


public function store(Request $request)
{
    $date = $request->updated_at ? Carbon::parse($request->updated_at)->startOfDay() : now()->startOfDay();

    $advance = Salary::where('employee_id', $request->employee_id)
        ->whereDate('created_at', $date)
        ->first();

    if ($advance) {
        $advance->paid += $request->paid;
        $advance->remarks = $request->remarks;
        $advance->updated_at = $date;  
        $advance->save();

        $message = 'Advance salary updated successfully!';
    } else {
        $advance = new Salary();
        $advance->employee_id = $request->employee_id;
        $advance->paid = $request->paid;
        $advance->remarks = $request->remarks;
        $advance->created_at = $date;
        $advance->updated_at = $date;
        $advance->save();

        $message = 'Advance salary saved successfully!';
    }

    $salaryId = $advance->id;

    $salaryExpenseAccount = DB::table('add_accounts')
        ->where('sub_head_name', 'Salary Expense')
        ->first();

    if (!$salaryExpenseAccount) {
        return response()->json(['success' => false, 'message' => 'Salary Expense account not found.'], 404);
    }

    DB::table('grn_accounts')->insert([
        'vendor_account_id' => $salaryExpenseAccount->id,
        'debit' => $request->paid,
        'salary_id' => $salaryId,
        'salary' => 'advance', 
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $cashinhandAccount = DB::table('add_accounts')
        ->where('sub_head_name', 'Cash In Hand')
        ->first();

    if (!$cashinhandAccount) {
        return response()->json(['success' => false, 'message' => 'Cash In Hand account not found.'], 404);
    }

    DB::table('grn_accounts')->insert([
        'vendor_account_id' => $cashinhandAccount->id,
        'vendor_net_amount' => $request->paid,
        'salary_id' => $salaryId,
        'salary' => 'advance',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json([
        'message' => $message,
        'advance' => $advance,
    ]);
}


public function getSalaryInfo($id)
{
    $employee = emplyees::find($id);

    if (!$employee) {
        return response()->json(['error' => 'Employee not found'], 404);
    }

    $totalPaid = Salary::where('employee_id', $id)->sum('paid');
    $totalFine = Fine::where('employee_id', $id)->sum('fine');

    return response()->json([
        'client_salary' => $employee->client_salary,
        'paid' => $totalPaid,
        'fine' => $totalFine,
    ]);
}



public function payedsalary()
{
    $user = Auth::user();

    $today = Carbon::today();

    $employees = emplyees::with(['salaries' => function ($query) use ($today) {
        $query->whereDate('created_at', $today);
    }])->get();


     $employeesalll = emplyees::all();

    return view('adminpages.payedsalary', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'employees' => $employees,
        'employeesalll' => $employeesalll
    ]);
}


public function srchpayedsalary(Request $request)
{
    $user = Auth::user();

    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $employeeId = $request->input('employee_id');

    $employeesQuery = emplyees::query();

    $employeesQuery->with(['salaries' => function($query) use ($fromDate, $toDate) {
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }
    }]);

    if ($employeeId) {
        $employeesQuery->where('id', $employeeId);
    }

    $employees = $employeesQuery->get();

    $employeesalll = emplyees::all();

    return view('adminpages.payedsalary', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'employees' => $employees,
        'fromDate' => $fromDate,
        'toDate' => $toDate,
        'employeeId' => $employeeId,
        'employeesalll' => $employeesalll
    ]);
}


public function destroy($id)
{
    $salry = Salary::find($id);

    if (!$salry) {
        return response()->json(['message' => 'Salary record not found.'], 404);
    }

    try {
        DB::table('grn_accounts')->where('salary_id', $salry->id)->delete();

        $salry->delete();

        return response()->json(['message' => 'Salary and related GRN records deleted successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to delete salary or related records.'], 500);
    }
}


public function show($id)
{
    $salry = Salary::find($id);

    if (!$salry) {
        return response()->json(['message' => 'Salary not found'], 404);
    }

    return response()->json($salry);
}


public function update(Request $request, $id)
{
    $salry = Salary::find($id);

    if (!$salry) {
        return response()->json(['message' => 'Salary not found'], 404);
    }

    $request->validate([
        'paid' => 'required|numeric|min:0',
    ]);

    $salry->paid = $request->paid;
    $salry->save();

    DB::table('grn_accounts')->where('salary_id', $salry->id)->delete();

    $salaryExpenseAccount = DB::table('add_accounts')
        ->where('sub_head_name', 'Salary Expense')
        ->first();

    if (!$salaryExpenseAccount) {
        return response()->json(['success' => false, 'message' => 'Salary Expense account not found.'], 404);
    }

    DB::table('grn_accounts')->insert([
        'vendor_account_id' => $salaryExpenseAccount->id,
        'debit' => $request->paid,
        'salary_id' => $salry->id,
        'salary' => 'advance',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $cashinhandAccount = DB::table('add_accounts')
        ->where('sub_head_name', 'Cash In Hand')
        ->first();

    if (!$cashinhandAccount) {
        return response()->json(['success' => false, 'message' => 'Cash In Hand account not found.'], 404);
    }

    DB::table('grn_accounts')->insert([
        'vendor_account_id' => $cashinhandAccount->id,
        'vendor_net_amount' => $request->paid,
        'salary_id' => $salry->id,
        'salary' => 'advance',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['message' => 'Salary updated successfully']);
}


}
