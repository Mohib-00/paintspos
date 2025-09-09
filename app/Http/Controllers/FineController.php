<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\emplyees;
use App\Models\Fine;
use App\Models\GrnAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
     public function showfine(){ 
        $user = Auth::user();      
        $fines = Fine::with('employee')
            ->orderBy('created_at', 'desc')
            ->get();
        $employees = emplyees::all();  
        return view('adminpages.fine', ['userName' => $user->name,'userEmail' => $user->email],compact('fines','employees'));
    }


     public function store(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:emplyees,id',
        'narration' => 'required|string',
        'fine' => 'required|numeric',
        'created_at' => 'required|date',
    ]);

    $fine = Fine::create([
        'employee_id' => $request->employee_id,
        'narration' => $request->narration,
        'fine' => $request->fine,
        'created_at' => $request->created_at,
        'updated_at' => now(),
    ]);

    $fine->load('employee');

    $account = AddAccount::where('sub_head_name', 'Fine & Penalty')->first();

    if ($account) {
        GrnAccount::create([
            'vendor_account_id' => $account->id,
            'fine_id' => $fine->id,
            'vendor_net_amount' => $request->fine,
            'created_at' => $fine->created_at,
            'updated_at' =>  $fine->created_at,
        ]);
    }

    return response()->json([
        'message' => 'Fine saved successfully',
        'fine' => $fine
    ]);
}



public function show($id)
{
    $fine = Fine::findOrFail($id);
    return response()->json($fine);
}


public function update(Request $request, $id)
{
    $request->validate([
        'employee_id' => 'required|exists:emplyees,id',
        'narration' => 'required|string',
        'fine' => 'required|numeric',
        'created_at' => 'date',
    ]);

    $fine = Fine::findOrFail($id);

    GrnAccount::where('fine_id', $fine->id)->delete();

    $fine->update([
        'employee_id' => $request->employee_id,
        'narration' => $request->narration,
        'fine' => $request->fine,
        'created_at' => $request->created_at,
        'updated_at' => now(),
    ]);

    $fine->load('employee');

    $fineAccount = AddAccount::where('sub_head_name', 'Fine & Penalty')->first();

    if ($fineAccount) {
       GrnAccount::create([
            'vendor_account_id' => $fineAccount->id,
            'vendor_net_amount' => $request->fine,
            'fine_id' => $fine->id,
            'created_at' => $fine->created_at,
            'updated_at' =>  $fine->created_at,
        ]);
    }

    return response()->json([
        'message' => 'Fine updated successfully',
        'fine' => $fine
    ]);
}



public function destroy($id)
{
    $fine = Fine::findOrFail($id);
    GrnAccount::where('fine_id', $fine->id)->delete();

    $fine->delete();

    return response()->json([
        'message' => 'Fine deleted successfully',
        'fine_id' => $id
    ]);
}



}
