<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function pay(){ 
        $user = Auth::user();      
        $purchases = Purchase::where('payment_status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();
        return view('adminpages.payment', ['userName' => $user->name,'userEmail' => $user->email],compact('purchases'));
    }


    public function storePayment(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string',
            'payment_method' => 'required|string',
            'bank_name' => 'nullable|string',
            'amount_payed' => 'required|numeric',
            'amount_remain' => 'required|numeric',
        ]);
    
        $purchase = Purchase::where('invoice_no', $request->invoice_no)->first();
    
        if (!$purchase) {
            return response()->json(['error' => 'Invoice not found.'], 404);
        }
        
    
        $updateData = [
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name,
            'amount_payed' => $request->amount_payed,
            'amount_remain' => $request->amount_remain,
        ];
    
        if ($request->amount_remain == 0) {
            $updateData['payment_status'] = 'complete';
        }
        $purchase->timestamps = false;
    
        $purchase->update($updateData);
    
        $vendorAccount = DB::table('add_accounts')
            ->where('sub_head_name', $purchase->vendors)
            ->first();
    
        if (!$vendorAccount) {
            return response()->json(['error' => 'Vendor account not found.']);
        }
    
        $grnCreatedAt = $request->created_at ?? now();
        DB::table('grn_accounts')->insert([
            'vendor_account_id' => $vendorAccount->id,
            'debit' => $request->amount_payed,
            'purchase_id' => $purchase->id,
            'created_at' => $grnCreatedAt,
            'updated_at' => $grnCreatedAt,
            'payment' => 'payment',
        ]);
    
        if (strtolower($request->payment_method) === 'cash') {
            $cashAccount = DB::table('add_accounts')
                ->where('sub_head_name', 'Cash In Hand')
                ->first();
    
            if ($cashAccount) {
                DB::table('grn_accounts')->insert([
                    'vendor_account_id' => $cashAccount->id,
                    'vendor_net_amount' => $request->amount_payed,
                    'purchase_id' => $purchase->id,
                    'created_at' => $grnCreatedAt,
                    'updated_at' => $grnCreatedAt,
                    'payment' => 'payment',
                ]);
            } else {
                return response()->json(['error' => 'Account "Cash In Hand" not found.']);
            }
        }
    
        if (strtolower($request->payment_method) === 'bank') {
            $bankAccount = DB::table('add_accounts')
                ->where('sub_head_name', 'Cash At Bank')
                ->first();
    
            if ($bankAccount) {
                DB::table('grn_accounts')->insert([
                    'vendor_account_id' => $bankAccount->id,
                    'vendor_net_amount' => $request->amount_payed,
                    'purchase_id' => $purchase->id,
                    'created_at' => $grnCreatedAt,
                    'updated_at' => $grnCreatedAt,
                    'payment' => 'payment',
                ]);
            } else {
                return response()->json(['error' => 'Account "Cash At Bank" not found.']);
            }
        }
    
        return response()->json(['success' => 'Payment data saved and accounts updated successfully.']);
    }
    
    
    
    

    

}
