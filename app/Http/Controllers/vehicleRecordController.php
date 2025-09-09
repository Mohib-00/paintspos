<?php

namespace App\Http\Controllers;

use App\Models\AlertVehicle;
use App\Models\VehicleRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class vehicleRecordController extends Controller
{

    public function vehiclelist(){ 
    $user = Auth::user();      

    $vehicles = VehicleRecord::orderBy('created_at', 'desc')
    ->get();

        return view('adminpages.vehiclelist', ['userName' => $user->name,'userEmail' => $user->email],compact('vehicles'));
    }


    public function vehiclerecordadd(){ 
    $user = Auth::user();      

    $vehicles = VehicleRecord::orderBy('created_at', 'desc')
    ->get();
    return view('adminpages.vehicles', ['userName' => $user->name,'userEmail' => $user->email],compact('vehicles'));
    }



       public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'vehicle_no' => 'nullable',
            'owner_name' => 'nullable',
            'phone_no' => 'nullable',
            'currentoil_reading' => 'nullable',
            'nextoil_reading' => 'nullable',
            'oil_brand' => 'nullable',
            'quantity' => 'nullable',
            'gear_oil' => 'nullable',
            'oil_filter' => 'nullable',
            'air_filter' => 'nullable',
            'Ac_filter' => 'nullable',
            'battery_checkup' => 'nullable',
            'type_air_pressure' => 'nullable',
            'invoice_no' => 'nullable',
            'total_bill' => 'nullable',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',   
        ]);

        $vehicle = new VehicleRecord();
        $vehicle->vehicle_no = $validatedData['vehicle_no'] ?? null;
        $vehicle->owner_name = $validatedData['owner_name'] ?? null;
        $vehicle->phone_no = $validatedData['phone_no'] ?? null;
        $vehicle->currentoil_reading = $validatedData['currentoil_reading'] ?? null;
        $vehicle->nextoil_reading = $validatedData['nextoil_reading'] ?? null;
        $vehicle->oil_brand = $validatedData['oil_brand'] ?? null;
        $vehicle->quantity = $validatedData['quantity'] ?? null;
        $vehicle->gear_oil = $validatedData['gear_oil'] ?? null;
        $vehicle->oil_filter = $validatedData['oil_filter'] ?? null;
        $vehicle->air_filter = $validatedData['air_filter'] ?? null;
        $vehicle->Ac_filter = $validatedData['Ac_filter'] ?? null;
        $vehicle->battery_checkup = $validatedData['battery_checkup'] ?? null;
        $vehicle->type_air_pressure = $validatedData['type_air_pressure'] ?? null;
        $vehicle->invoice_no = $validatedData['invoice_no'] ?? null;
        $vehicle->total_bill = $validatedData['total_bill'] ?? null;
        $vehicle->created_at = $validatedData['created_at'] ?? now();
        $vehicle->updated_at = $validatedData['created_at'] ?? now();


        $vehicle->save();

        return response()->json([
            'success' => true,
            'vehicle' => $vehicle,
        ], 201);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


public function show($id)
{
    $vehicle = VehicleRecord::find($id);

    if (!$vehicle) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'vehicle' => $vehicle
    ]);
}



public function update(Request $request, $id)
{
    try {
        $vehicle = VehicleRecord::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'vehicle_no' => 'nullable',
            'owner_name' => 'nullable',
            'phone_no' => 'nullable',
            'currentoil_reading' => 'nullable',
            'nextoil_reading' => 'nullable',
            'oil_brand' => 'nullable',
            'quantity' => 'nullable',
            'gear_oil' => 'nullable',
            'oil_filter' => 'nullable',
            'air_filter' => 'nullable',
            'Ac_filter' => 'nullable',
            'battery_checkup' => 'nullable',
            'type_air_pressure' => 'nullable',
            'invoice_no' => 'nullable',
            'total_bill' => 'nullable',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',   
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        if ($request->has('vehicle_no')) {
            $vehicle->vehicle_no = $request->vehicle_no;
        }
        if ($request->has('owner_name')) {
            $vehicle->owner_name = $request->owner_name;
        }
        if ($request->has('phone_no')) {
            $vehicle->phone_no = $request->phone_no;
        }
        if ($request->has('currentoil_reading')) {
            $vehicle->currentoil_reading = $request->currentoil_reading;
        }
        if ($request->has('nextoil_reading')) {
            $vehicle->nextoil_reading = $request->nextoil_reading;
        }
        if ($request->has('oil_brand')) {
            $vehicle->oil_brand = $request->oil_brand;
        }
        if ($request->has('quantity')) {
            $vehicle->quantity = $request->quantity;
        }
        if ($request->has('gear_oil')) {
            $vehicle->gear_oil = $request->gear_oil;
        }
        if ($request->has('oil_filter')) {
            $vehicle->oil_filter = $request->oil_filter;
        }
        if ($request->has('air_filter')) {
            $vehicle->air_filter = $request->air_filter;
        }
        if ($request->has('Ac_filter')) {
            $vehicle->Ac_filter = $request->Ac_filter;
        }
        if ($request->has('battery_checkup')) {
            $vehicle->battery_checkup = $request->battery_checkup;
        }
        if ($request->has('type_air_pressure')) {
            $vehicle->type_air_pressure = $request->type_air_pressure;
        }
        if ($request->has('invoice_no')) {
            $vehicle->invoice_no = $request->invoice_no;
        }
        if ($request->has('total_bill')) {
            $vehicle->total_bill = $request->total_bill;
        }
        if ($request->has('created_at')) {
            $vehicle->created_at = $request->created_at;
        }
        if ($request->has('updated_at')) {
            $vehicle->created_at = $request->created_at;
        }

        $vehicle->save();

       

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'vehicle' => $vehicle,
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}



public function deletevehicle(Request $request)
{
    try {
        $vehicle = VehicleRecord::find($request->vehicle_id);

        if (!$vehicle) {
            return response()->json(['success' => false, 'message' => 'vehicle not found'], 404);
        }

        $vehicle->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


public function storealert(Request $request)
{
    $request->validate([
        'vehicle_id' => 'required|exists:vehicle_records,id',
        'alert' => 'required|string|max:255',
        'created_at' => 'nullable|date',
    ]);

    AlertVehicle::create([
        'vehicle_id' => $request->vehicle_id,
        'alert' => $request->alert,
        'created_at' => $request->created_at ?? now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Alert saved successfully.']);
}


public function vehiclealert()
{
    $user = Auth::user();  

    $today = Carbon::today()->toDateString();

    $vehicles = AlertVehicle::whereDate('created_at', $today)->with('vehicle')->get();

    return view('adminpages.vehiclealert', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'vehicles' => $vehicles
    ]);
}


}
