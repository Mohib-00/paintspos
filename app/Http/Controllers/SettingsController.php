<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function adminprofile()
{
$user = Auth::user();   
 return view('adminpages.profile', ['userName' => $user->name,'userEmail' => $user->email,'userImage' => $user->image]);
}

public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'nullable|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
        if ($user->image && File::exists(public_path('images/' . $user->image))) {
            File::delete(public_path('images/' . $user->image));
        }

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        $user->image = $imageName;
    }

    if ($request->filled('name')) {
        $user->name = $request->name;
    }
    if ($request->filled('email')) {
        $user->email = $request->email;
    }

    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Profile updated successfully!',
        'image' => $user->image ? asset('images/' . $user->image) : null, 
        'name' => $user->name,
        'email' => $user->email
    ]);
}

}
