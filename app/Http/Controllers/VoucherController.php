<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AddAccount;
use App\Models\GrnAccount;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function addvoucher(){ 
        $user = Auth::user();     
        $accounts = AddAccount::all();
        return view('adminpages.addvoucher', ['userName' => $user->name,'userEmail' => $user->email],compact('accounts'));
    }
    public function voucher()
{
    $user = Auth::user();    
    $currentDate = Carbon::today()->toDateString();

    $vouchers = Voucher::with(['voucherItems', 'user'])
        ->where('voucher_status', 'Complete')
        ->whereDate('created_at', $currentDate)
        ->get();

    return view('adminpages.voucher', [
        'userName' => $user->name,
        'userEmail' => $user->email
    ], compact('vouchers'));
}


     public function search(Request $request)
    {
        $user = Auth::user();
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $vouchers = Voucher::when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query, $toDate) {
                return $query->whereDate('created_at', '<=', $toDate);
            })
            ->get();

        return view('adminpages.voucher',['userName' => $user->name,'userEmail' => $user->email], compact('vouchers'));
    }

    public function getCashInHand(Request $request)
{
    $voucherType = $request->input('voucher_type');
    
    $cashInHand = AddAccount::where('sub_head_name', 'Cash In Hand')->first();

    if (!$cashInHand) {
        return response()->json(['error' => 'Cash In Hand account not found.'], 404);
    }

    $cashInHandId = $cashInHand->id;

    $grnAccounts = GrnAccount::where('vendor_account_id', $cashInHandId)->get();

    $debitValues = $grnAccounts->pluck('debit')->toArray();
    $creditValues = $grnAccounts->pluck('vendor_net_amount')->toArray();

    $totalDebit = array_sum($debitValues);
    $totalCredit = array_sum($creditValues);

    if ($totalDebit > $totalCredit) {
        $cashBalance = $totalDebit - $totalCredit;
    } else {
        $cashBalance = $totalCredit - $totalDebit;
    }

    Log::info('Cash Balance: ' . $cashBalance);

    return response()->json(['cash_in_hand' => $cashBalance]);
}


public function getAccountBalance(Request $request)
{
    $accountId = $request->input('account_id');

    $grnAccounts = GrnAccount::where('vendor_account_id', $accountId)->get();

    if ($grnAccounts->isEmpty()) {
        return response()->json(['balance' => 0]);
    }

    $totalDebit = $grnAccounts->sum('debit');
    $totalCredit = $grnAccounts->sum('vendor_net_amount');

    $balance = $totalCredit - $totalDebit;

    return response()->json(['balance' => abs($balance)]);  
}


public function store(Request $request)
{
    $request->validate([
        'receiving_location' => 'required|string',
        'voucher_type' => 'required|string',
        'created_at' => 'nullable|date',
        'remarks' => 'nullable|string',
        'account.*' => 'required',
        'amount.*' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $createdAt = $request->created_at ?? now();
        $updatedAt = $request->updated_at ?? $createdAt;

        $voucher = Voucher::create([
            'user_id' => Auth::id(),
            'receiving_location' => $request->receiving_location,
            'voucher_type' => $request->voucher_type,
            'cash_in_hand' => $request->input('cash_in_hand', 0),
            'totalAmount' => $request->input('totalAmount', 0),
            'remarks' => $request->remarks,
            'voucher_status' => 'complete',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]);

        $voucherId = $voucher->id; 

        $accounts = $request->input('account');
        $balances = $request->input('balance');
        $narrations = $request->input('narration');
        $amounts = $request->input('amount');

        $voucherItems = [];

        foreach ($accounts as $index => $accountId) {
            $voucherItems[] = VoucherItem::create([
                'voucher_id' => $voucherId,  
                'account' => $accountId,
                'balance' => $balances[$index] ?? 0,
                'narration' => $narrations[$index] ?? '',
                'amount' => $amounts[$index],
                'created_at' => $voucher->created_at,
                'updated_at' => $voucher->created_at,
            ]);
        }


        $cashInHandAccount = DB::table('add_accounts')
            ->where('sub_head_name', 'Cash In Hand')
            ->first();

        if ($cashInHandAccount) {
            $totalAmount = collect($voucherItems)->sum('amount');

            if ($request->voucher_type === 'Cash Payment') {
                DB::table('grn_accounts')->insert([
                    'voucher_id' => $voucherId,  
                    'vendor_account_id' => $cashInHandAccount->id,
                    'vendor_net_amount' => $totalAmount,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($voucherItems as $voucherItem) {
                    DB::table('grn_accounts')->insert([
                        'voucher_id' => $voucherId,  
                        'vendor_account_id' => $voucherItem->account,
                        'debit' => $voucherItem->amount,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }

            } elseif ($request->voucher_type === 'Cash Receipt') {
                DB::table('grn_accounts')->insert([
                    'voucher_id' => $voucherId,  
                    'vendor_account_id' => $cashInHandAccount->id,
                    'debit' => $totalAmount,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($voucherItems as $voucherItem) {
                    DB::table('grn_accounts')->insert([
                        'voucher_id' => $voucherId,  
                        'vendor_account_id' => $voucherItem->account,
                        'vendor_net_amount' => $voucherItem->amount,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json(['success' => true, 'message' => 'Voucher created successfully']);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Voucher creation failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Voucher creation failed.',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}


public function destroy($id)
{
    try {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voucher deleted successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete voucher.',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function voucheritems(Request $request, $id)
{
    $user = Auth::user();

    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');

    $query = Voucher::with(['voucherItems', 'user', 'grnAccounts.vendorAccount'])
        ->where('id', $id);

    if ($fromDate && $toDate) {
        $query->whereBetween('created_at', [$fromDate, $toDate]);
    }

    $voucher = $query->first();

    if (!$voucher) {
        return redirect()->back()->with('error', 'Voucher not found.');
    }

    return view('adminpages.voucheritems', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'voucher' => $voucher,
    ]);
}



public function editvoucher($id)
{
    $user = Auth::user();
    
    $accounts = AddAccount::all();

    $vouchers = Voucher::with(['voucherItems', 'user'])->findOrFail($id);

    if (!$vouchers) {
        return redirect()->back()->with('error', 'Voucher not found.');
    }

    return view('adminpages.editvoucher', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'vouchers' => $vouchers,
        'accounts' => $accounts
    ]);
}


public function editvouchers(Request $request, $id)
{
    $request->validate([
        'receiving_location' => 'required|string',
        'voucher_type' => 'required|string',
        'created_at' => 'nullable|date',
        'remarks' => 'nullable|string',
        'account.*' => 'required',
        'amount.*' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $createdAt = $request->created_at ? Carbon::parse($request->created_at) : now();
        $updatedAt = $request->created_at ? Carbon::parse($request->created_at) : now();

        $voucher = Voucher::findOrFail($id);

        $voucher->receiving_location = $request->receiving_location;
        $voucher->voucher_type = $request->voucher_type;
        $voucher->cash_in_hand = $request->input('cash_in_hand', 0);
        $voucher->totalAmount = $request->input('totalAmount', 0);
        $voucher->remarks = $request->remarks;
        $voucher->updated_at = $updatedAt;
        $voucher->created_at = $createdAt;
        $voucher->save();

        VoucherItem::where('voucher_id', $id)->delete();

        $accounts = $request->input('account');
        $balances = $request->input('balance');
        $narrations = $request->input('narration');
        $amounts = $request->input('amount');

        foreach ($accounts as $index => $accountId) {
            VoucherItem::create([
                'voucher_id' => $id,
                'account' => $accountId,
                'balance' => $balances[$index] ?? 0,
                'narration' => $narrations[$index] ?? '',
                'amount' => $amounts[$index],
                'created_at' => $voucher->created_at,
                'updated_at' => $voucher->created_at,
            ]);
        }

         $cashInHandAccount = DB::table('add_accounts')
            ->where('sub_head_name', 'Cash In Hand')
            ->first();

        if ($cashInHandAccount) {
            $totalAmount = collect($amounts)->sum();

            DB::table('grn_accounts')->where('voucher_id', $id)->delete();

            if ($request->voucher_type === 'Cash Payment') {
                DB::table('grn_accounts')->insert([
                    'voucher_id' => $id,
                    'vendor_account_id' => $cashInHandAccount->id,
                    'vendor_net_amount' => $totalAmount,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($accounts as $index => $accountId) {
                    DB::table('grn_accounts')->insert([
                        'voucher_id' => $id,
                        'vendor_account_id' => $accountId,
                        'debit' => $amounts[$index],
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            } elseif ($request->voucher_type === 'Cash Receipt') {
                DB::table('grn_accounts')->insert([
                    'voucher_id' => $id,
                    'vendor_account_id' => $cashInHandAccount->id,
                    'debit' => $totalAmount,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                foreach ($accounts as $index => $accountId) {
                    DB::table('grn_accounts')->insert([
                        'voucher_id' => $id,
                        'vendor_account_id' => $accountId,
                        'vendor_net_amount' => $amounts[$index],
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json(['success' => true, 'message' => 'Voucher updated successfully']);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Voucher update failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Voucher update failed.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


//JV START
  public function jvvoucher(){ 
        $user = Auth::user();     
        $accounts = AddAccount::all();
       $vouchers = Voucher::where('voucher_type', 'JV')
                   ->with('voucherItems')
                   ->where('voucher_status', 'pending')
                   ->get();


        return view('adminpages.addjvvoucher', ['userName' => $user->name,'userEmail' => $user->email],compact('accounts','vouchers'));
    }


public function storejv(Request $request)
{
    $request->validate([
        'receiving_location' => 'required|string',
        'voucher_type' => 'required|string',
        'created_at' => 'nullable|date',
        'remarks' => 'nullable|string',
        'account.*' => 'required',
        'amount.*' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $createdAt = $request->created_at ?? now();
        $updatedAt = $request->updated_at ?? $createdAt;

        $jv = 'JV-' . strtoupper(uniqid());

        $voucher = Voucher::create([
            'user_id' => Auth::id(),
            'receiving_location' => $request->receiving_location,
            'voucher_type' => $request->voucher_type,
            'cash_in_hand' => $request->input('cash_in_hand', 0),
            'totalAmount' => $request->input('totalAmount', 0),
            'remarks' => $request->remarks,
            'voucher_status' => 'pending',
            'jv' => $jv,
            'status' => 'pending',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]);

        $voucherId = $voucher->id; 

        $accounts = $request->input('account');
        $balances = $request->input('balance');
        $narrations = $request->input('narration');
        $amounts = $request->input('amount');

        foreach ($accounts as $index => $accountId) {
            $amount = $amounts[$index];

            VoucherItem::create([
                'voucher_id' => $voucherId,  
                'account' => $accountId,
                'balance' => $balances[$index] ?? 0,
                'narration' => $narrations[$index] ?? '',
                'amount' => $amount,
                'jv' => $jv,
                'status' => 'pending',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            GrnAccount::create([
                'vendor_account_id' => $accountId,
                'voucher_id' => $voucherId,
                'debit' => $amount,
                'jv' => $jv,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'JV created successfully and entries added to GRN accounts.'
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}



public function editjvvoucher($id)
{
    $user = Auth::user();
    
    $accounts = AddAccount::all();

    $vouchers = Voucher::with(['voucherItems', 'user'])->findOrFail($id);

    if (!$vouchers) {
        return redirect()->back()->with('error', 'Voucher not found.');
    }

    return view('adminpages.editjvvoucher', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'vouchers' => $vouchers,
        'accounts' => $accounts
    ]);
}

public function editjvvouchers(Request $request, $id)
{
    $request->validate([
        'receiving_location' => 'required|string',
        'voucher_type' => 'required|string',
        'created_at' => 'nullable|date',
        'remarks' => 'nullable|string',
        'account.*' => 'required',
        'amount.*' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $createdAt = $request->created_at ?? now();
        $updatedAt = $createdAt;

        $voucher = Voucher::findOrFail($id);

        $jv = $voucher->jv ?? 'JV-' . strtoupper(uniqid());

        $voucher->update([
            'receiving_location' => $request->receiving_location,
            'voucher_type' => $request->voucher_type,
            'cash_in_hand' => $request->input('cash_in_hand', 0),
            'totalAmount' => $request->input('totalAmount', 0),
            'remarks' => $request->remarks,
            'jv' => $jv,
            'status' => 'pending',
            'updated_at' => $updatedAt,
        ]);

        VoucherItem::where('voucher_id', $id)->delete();
        GrnAccount::where('voucher_id', $id)->delete();

        $accounts = $request->input('account');
        $balances = $request->input('balance');
        $narrations = $request->input('narration');
        $amounts = $request->input('amount');

        foreach ($accounts as $index => $accountId) {
            $amount = $amounts[$index];

            VoucherItem::create([
                'voucher_id' => $id,
                'account' => $accountId,
                'balance' => $balances[$index] ?? 0,
                'narration' => $narrations[$index] ?? '',
                'amount' => $amount,
                'jv' => $jv,
                'status' => 'pending',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            GrnAccount::create([
                'vendor_account_id' => $accountId,
                'voucher_id' => $id,
                'debit' => $amount,
                'jv' => $jv,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'JV Voucher updated successfully.'
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('JV Voucher update failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'JV Voucher update failed.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function completejvvoucher($id)
{
    $user = Auth::user();
    
    $accounts = AddAccount::all();

    $vouchers = Voucher::with(['voucherItems', 'user'])->findOrFail($id);

    if (!$vouchers) {
        return redirect()->back()->with('error', 'Voucher not found.');
    }

    return view('adminpages.completejvvoucher', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'vouchers' => $vouchers,
        'accounts' => $accounts
    ]);
}


public function complatejvvoucher(Request $request, $id)
{
    $request->validate([
        'receiving_location' => 'required|string',
        'voucher_type' => 'required|string',
        'created_at' => 'nullable|date',
        'remarks' => 'nullable|string',
        'account.*' => 'required',
        'amount.*' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $createdAt = $request->created_at ?? now();
        $updatedAt = $createdAt;

        $voucher = Voucher::findOrFail($id);

        $jv = $voucher->jv ?? 'JV-' . strtoupper(uniqid());

        $voucher->timestamps = false;
        $voucher->receiving_location = $request->receiving_location;
        $voucher->voucher_type = $request->voucher_type;
        $voucher->cash_in_hand = $request->input('cash_in_hand', 0);
        $voucher->totalAmount = $request->input('totalAmount', 0);
        $voucher->remarks = $request->remarks;
        $voucher->jv = $jv;
        $voucher->voucher_status = 'complete';
        $voucher->status = 'complete';
        $voucher->updated_at = $updatedAt;
        $voucher->save();

        VoucherItem::where('voucher_id', $id)->delete();

        $accounts = $request->input('account');
        $balances = $request->input('balance');
        $narrations = $request->input('narration');
        $amounts = $request->input('amount');

        foreach ($accounts as $index => $accountId) {
            $amount = $amounts[$index];

            VoucherItem::create([
                'voucher_id' => $id,
                'account' => $accountId,
                'balance' => $balances[$index] ?? 0,
                'narration' => $narrations[$index] ?? '',
                'amount' => $amount,
                'jv' => $jv,
                'status' => 'complete',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            GrnAccount::create([
                'vendor_account_id' => $accountId,
                'voucher_id' => $id,
                'vendor_net_amount' => $amount,
                'jv' => $jv,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'JV Voucher Completed successfully.'
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('JV Voucher complete failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'JV Voucher complete failed.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}