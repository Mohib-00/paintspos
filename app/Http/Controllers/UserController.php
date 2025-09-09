<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
public function updateUser(Request $request, $id)
{
    $user = User::findOrFail($id);

    if ($request->filled('name')) {
        $user->name = $request->input('name');
    }

    if ($request->filled('email')) {
        $user->email = $request->input('email');
    }

    if ($request->filled('role')) {
        $user->role = $request->input('role');
    }

    if ($request->filled('gender')) {
        $user->gender = $request->input('gender');
    }

    if ($request->filled('dashboard')) {
        $user->dashboard = $request->input('dashboard');
    }

    if ($request->filled('discount')) {
        $user->discount = $request->input('discount');
    }

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('images'), $imageName);
        $user->image = 'images/' . $imageName;
    }

    $user->save();

    return response()->json(['status' => 'success', 'message' => 'User updated successfully.']);
}


public function changePassword(Request $request, $id)
{
    $request->validate([
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::findOrFail($id);
    $user->password = Hash::make($request->password);
    $user->save();

    return response()->json(['success' => true, 'message' => 'Password changed successfully']);
}


public function savePermissions(Request $request, $userId)
{
    $allPermissions = [
        'user_read', 'user_add', 'user_update', 'user_delete',
        'pos_read', 'pos_add', 'pos_update', 'pos_delete', 'pos_pastdate',
        'sale_read', 'sale_update', 'sale_delete',
        'pur_read', 'pur_add', 'pur_update', 'pur_delete', 'pur_pastdate',
        'purchase_return_read',
        'acc_read',
        'vo_read', 'vo_add', 'vo_update', 'vo_delete', 'vo_pastdate',
        'paysalary_read',
        'payedsalary_read',
        'reports_read',
        'salereport_read',
        'stockreport_read',
        'dcreport_read',
        'gl_read',
        'vend_read', 'vend_add', 'vend_update', 'vend_delete',
        'custmers_read', 'custmers_add', 'custmers_update', 'custmers_delete',
        'area_read', 'area_add', 'area_update', 'area_delete',
        'block_read', 'block_add', 'block_update', 'block_delete',
        'empl_read', 'empl_add', 'empl_update', 'empl_delete',
        'emplleave_read', 'emplleave_add', 'emplleave_update', 'emplleave_delete', 'emplleave_pastdate',
        'dgnation_read', 'dgnation_add', 'dgnation_update', 'dgnation_delete', 'dgnation_pastdate',
        'atndnce_read',
        'atndncereport_read',
        'cmppny_read', 'cmppny_add', 'cmppny_update', 'cmppny_delete',
        'ctgry_read', 'ctgry_add', 'ctgry_update', 'ctgry_delete',
        'subctgry_read', 'subctgry_add', 'subctgry_update', 'subctgry_delete',
        'product_read', 'product_add', 'product_update', 'product_delete',
        'productprice_read',
        'productimport_read',
    ];

    // Set checked fields to 0, unchecked to 1
    $permissionsToUpdate = [];
    foreach ($allPermissions as $permission) {
        $permissionsToUpdate[$permission] = $request->has($permission) ? 0 : 1;
    }

    // Update the user table
    \App\Models\User::where('id', $userId)->update($permissionsToUpdate);

    return response()->json(['success' => true, 'message' => 'Permissions updated successfully.']);
}


}
