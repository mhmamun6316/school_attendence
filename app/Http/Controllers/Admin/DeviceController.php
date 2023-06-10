<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Exception;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        return view('admin.device.index',compact('devices'));
    }

    public function deviceList()
    {
        $devices = Device::latest()->get();

        return DataTables::of($devices)
            ->addIndexColumn()
            ->editColumn('organization', function ($devices){
                return '<span class="custom-badge">' . $devices->organization->name . '</span>';
            })
            ->addColumn('action', function($devices){
                $actionBtn = '<div class="actions">
                                    <a id="edit_btn" data-device-id="'.$devices->id.'" class="btn btn-warning btn-xs btn-shadow-warning">Edit</a>
                                    <a id="delete_btn" data-device-id="'.$devices->id.'" class="btn btn-danger btn-xs btn-shadow-danger">Delete</a>
                                </div>';
                return $actionBtn;
            })
            ->rawColumns(['action','organization'])
            ->make(true);
    }

    public function store(Request $request)
    {
        //        if(!auth()->device()->can('create',Organization::class)){
//            return response()->json(['message','UnAuthorized '],403);
//        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'number' => 'required|string|unique:devices,device_number',
            'description' => 'nullable',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        try{
            $device = new Device();
            $device->name = $request->name;
            $device->device_number = $request->number;
            $device->description = $request->description;
            $device->organization_id = $request->organization_id;
            $device->save();

            return response()->json(['success'=>"Device Added Successfully"]);
        }catch(Exception $e){
            Log::info("device adding error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function edit($id)
    {
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        return response()->json(['device' => $device], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'number' => 'required|string|unique:devices,device_number,' . $id,
            'organization_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        try{
            $device = Device::find($id);
            $device->name = $request->name;
            $device->device_number = $request->number;
            $device->description = $request->description;
            $device->organization_id = $request->organization_id;
            $device->save();

            return response()->json(['success'=>"Device Updated Successfully"]);
        }catch(Exception $e){
            Log::info("device updating error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        try{
            $device = Device::find($id);
            if (!is_null($device)){
                $device->delete();
            }else{
                return response()->json(['error'=>"Device Not Found"],404);
            }
            return response()->json(['success' => 'Device Deleted successfully'], 200);

        }catch(Exception $e){
            Log::debug("Error in Device delete:".$e->getMessage());
            Log::debug("Error in Device delete:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
