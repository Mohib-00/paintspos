<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Alertcontroller extends Controller
{
     public function alert(){ 
        $user = Auth::user();      
        $alerts = Alert::whereDate('alert_date', '<=', now()->toDateString())
               ->where('is_read', false)
               ->get();

        return view('adminpages.alerts', ['userName' => $user->name,'userEmail' => $user->email],compact('alerts'));
    }
    public function alertlist(){ 
        $user = Auth::user();      
        $alerts = Alert::all();
        return view('adminpages.alertslist', ['userName' => $user->name,'userEmail' => $user->email],compact('alerts'));
    }
public function markRead($id)
{
    $alert = Alert::find($id);
    if ($alert) {
        $alert->is_read = true;
        $alert->save();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false]);
}


    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'nullable|string',
        'alert_date' => 'required|date',
    ]);

    Alert::create([
        'title' => $request->title,
        'message' => $request->message,
        'alert_date' => $request->alert_date,
    ]);

    return response()->json(['success' => true]);
}


}
