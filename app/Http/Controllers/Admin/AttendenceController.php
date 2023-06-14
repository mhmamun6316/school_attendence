<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Attendence;
use App\Models\Admin\Device;
use Illuminate\Http\Request;

class AttendenceController extends Controller
{
    public function getScanData(Request $request)
    {
        $device_id     = $request->device_number;
        $student_id = $request->student_id;
        $timestamp     = $request->timestamp;

        if (!isset($device_id)) {
            return response()->json(['error' => 'device number Not Exists!'], 404);
        }
        if (!isset($student_id)) {
            return response()->json(['error' => 'Student Id Not Exists!'], 404);
        }
        if (!isset($timestamp)) {
            return response()->json(['error' => 'Timestamp Not Exists!'], 404);
        }

        $device = Device::where('device_number', $device_id)->first();
        if(!$device){
            return response()->json(['error' => 'Device is not found!!'], 404);
        }

        Attendence::create([
            'device_id' => $device_id,
            'student_id' => $student_id,
            'organization_id' => $device->organization_id,
            'arrived_time' => $timestamp
        ]);

        return response(['message' => "successfully attendance inserted"],200);
    }
}
