<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Exception;

class DeviceController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('device.view')){
            abort(403);
        }
        return view('admin.device.index');
    }

    public function deviceList()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('device.view')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $devices = Device::filterByOrganization()->where('is_archived',0)->latest()->get();

        return DataTables::of($devices)
            ->addIndexColumn()
            ->editColumn('organization', function ($devices){
                return '<span class="custom-badge">' . $devices->organization->name . '</span>';
            })
            ->addColumn('action', function($devices) use ($authUser){

                $actionBtn = '<div class="actions">';

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('device.edit')) {
                    $actionBtn .= '<a id="edit_btn" data-device-id="'.$devices->id.'" class="btn btn-warning btn-xs btn-shadow-warning">Edit</a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('device.delete')) {
                    $actionBtn .= '<a id="delete_btn" data-device-id="'.$devices->id.'" class="btn btn-danger btn-xs btn-shadow-danger">Delete</a>';
                }

                $actionBtn .= '</div>';

                return $actionBtn;
            })
            ->rawColumns(['action','organization'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('device.create')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }
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

        DB::beginTransaction();
        try{
            $device = new Device();
            $device->name = $request->name;
            $device->device_number = $request->number;
            $device->description = $request->description;
            $device->organization_id = $request->organization_id;
            $device->save();

            DB::commit();
            return response()->json(['success'=>"Device Added Successfully"]);
        }catch(Exception $e){
            DB::rollBack();
            Log::info("device adding error:".$e->getLine());
            Log::info("device adding error:".$e->getMessage());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function edit($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('device.edit')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        return response()->json(['device' => $device], 200);
    }

    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('device.edit')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }
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
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('device.delete')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }
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
