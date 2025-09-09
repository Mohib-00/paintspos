<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AddAccount;
use App\Models\customer;
use App\Models\Vendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function chartofaccount(){ 
        $user = Auth::user();      
        $accounts = Account::all();   
        return view('adminpages.accounts', ['userName' => $user->name,'userEmail' => $user->email],compact('accounts'));
    }

    public function addaccount(){ 
        $user = Auth::user();      
        $accounts = Account::all();   
        return view('adminpages.addaccounts', ['userName' => $user->name,'userEmail' => $user->email],compact('accounts'));
    }
    
public function show($id)
{
    $account = Account::find($id);

    if (!$account) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'account' => $account
    ]);
}


public function update(Request $request, $id)
{
    $account = Account::findOrFail($id);   

    $validator = Validator::make($request->all(), [
      'account_name' => 'nullable|string|max:255',
  ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

     
    if ($request->has('account_name')) {
        $account->account_name = $request->account_name;
    }
   
   
     $account->save();

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'account' => $account,
    ], 200);

}

public function store(Request $request)
{
    $request->validate([
        'head_name' => 'nullable|string',
        'sub_head_name' => 'nullable|string',
    ]);

    AddAccount::create([
        'head_name' => $request->head_name,
        'sub_head_name' => $request->sub_head_name,
        'child' => $request->head_name, 
    ]);

    return response()->json(['success' => true, 'message' => 'Account added successfully!']);
}


public function showByHeadName($head_name)
{
    $accounts = AddAccount::where('head_name', $head_name)->get();
    $user = Auth::user();
    return view('adminpages.assets_child',['userName' => $user->name,'userEmail' => $user->email], compact('accounts', 'head_name'));
}

public function liabilitychild($head_name)
{
    $liabilitys = AddAccount::where('head_name', $head_name)->get();
    $user = Auth::user();
    return view('adminpages.liability_child',['userName' => $user->name,'userEmail' => $user->email], compact('liabilitys', 'head_name'));
}

public function revenuechild($head_name)
{
    $revenues = AddAccount::where('head_name', $head_name)->get();
    $user = Auth::user();
    return view('adminpages.revenue_child',['userName' => $user->name,'userEmail' => $user->email], compact('revenues', 'head_name'));
}

public function vendoraccountssss($head_name){ 
    $user = Auth::user();      
    $vendors = AddAccount::where('head_name', $head_name)->get();
    return view('adminpages.vendoraccount', ['userName' => $user->name,'userEmail' => $user->email],compact('vendors'));
}

public function customersaccount($head_name)
{
    $user = Auth::user();      
    $customers = AddAccount::where('head_name', $head_name)->get();  
    return view('adminpages.customersaccount', ['userName' => $user->name, 'userEmail' => $user->email], compact('customers'));
}


public function equitychild($head_name)
{
    $equitys = AddAccount::where('head_name', $head_name)->get();
    $user = Auth::user();
    return view('adminpages.equity_child',['userName' => $user->name,'userEmail' => $user->email], compact('equitys', 'head_name'));
}

public function expensechild($head_name)
{
    $expenses = AddAccount::where('head_name', $head_name)->get();
    $user = Auth::user();
    return view('adminpages.expense_child',['userName' => $user->name,'userEmail' => $user->email], compact('expenses', 'head_name'));
}

public function getSubHeadsByHeadName($accountName)
{
    $account = AddAccount::where('head_name', $accountName)->first();

    if (!$account) {
        return response()->json([], 404);
    }

    $subHeads = AddAccount::where('head_name', $account->head_name)
        ->whereNotNull('sub_head_name')
        ->whereRaw("sub_head_name NOT LIKE '%(%)%'") 
        ->pluck('sub_head_name')
        ->unique()
        ->values();

    return response()->json($subHeads);
}


public function storeSubHead(Request $request)
{
    $request->validate([
        'head_name' => 'required|string',
        'sub_head_name' => 'required|string',
    ]);

    AddAccount::create([
        'head_name'      => $request->head_name,
        'sub_head_name'  => $request->sub_head_name,
        'child'     => $request->head_name, 
    ]);

    return response()->json(['message' => 'Sub Head Name saved successfully']);
}


public function deleteAccount($id)
{
    $account = AddAccount::find($id);

    if (!$account) {
        return response()->json(['message' => 'Account not found.'], 404);
    }

    $account->delete();

    return response()->json(['message' => 'Account deleted successfully.']);
}

public function getAccount($id)
{
    $account = AddAccount::findOrFail($id);
    $accounts = AddAccount::all();
    

    if (request()->ajax()) {
        return response()->json([
            'account' => $account,
            'accounts' => $accounts,
        ]);
    }

    $user = Auth::user();
    return view('adminpages.accountedit', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'account' => $account,
        'accounts' => $accounts,
        'user' => $user,
    ]);
}

public function saveAccount(Request $request, $id)
{
    $validated = $request->validate([
        'head_name' => 'required|string',
        'sub_head_name' => 'required|string',
        'child_sub_head_name' => 'nullable|string',
    ]);

    $account = AddAccount::findOrFail($id);

    $account->head_name = $validated['head_name'];

    if (!empty($validated['child_sub_head_name'])) {
        $account->sub_head_name = $validated['sub_head_name'] . ' (' . $validated['child_sub_head_name'] . ')';
    } else {
        $account->sub_head_name = $validated['sub_head_name'];
    }

    $account->child = $validated['head_name'];

    $account->save();

    return response()->json(['message' => 'Account updated successfully!'], 200);
}

public function saveacccount(Request $request, $id)
{
    $validated = $request->validate([
        'head_name' => 'required|string',
        'sub_head_name' => 'required|string',
    ]);

    $account = AddAccount::findOrFail($id);

    $account->head_name = $validated['head_name'];
    $account->sub_head_name = $validated['sub_head_name'];
    $account->child = $validated['head_name']; 

    $account->save();

    return response()->json(['message' => 'Account updated successfully!']);
}

public function updateOpening(Request $request)
{
    $request->validate([
        'account_id' => 'required|exists:add_accounts,id',
        'opening' => 'required|numeric|min:0',
    ]);

    $account = AddAccount::findOrFail($request->account_id);
    $account->opening = $request->opening;
    $account->save();

    return response()->json([
        'success' => true,
        'message' => 'Opening amount updated successfully.',
        'account_id' => $account->id,
        'new_opening' => $account->opening
    ]);}


}
