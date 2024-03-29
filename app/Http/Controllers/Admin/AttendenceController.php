<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AttendanceMail;
use App\Models\Admin\Attendence;
use App\Models\Admin\Device;
use App\Models\Admin\Organization;
use App\Models\Admin\Student;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class AttendenceController extends Controller
{
    private $client;
    public function __construct()
    {
        $this->client = new Client();
    }

    public function getScanData(Request $request)
    {
//        $this->sendFacebookMessage('100009107791391', 'Hello, this is your message!');
        $device_id     = $request->device_number;
        $student_id = $request->student_id;
        $timestamp     = $request->timestamp;

        if (!isset($device_id)) {
            return response()->json(['error' => 'device number Not Exists!'], 422);
        }
        if (!isset($student_id)) {
            return response()->json(['error' => 'Student Id Not Exists!'], 422);
        }
        if (!isset($timestamp)) {
            return response()->json(['error' => 'Timestamp Not Exists!'], 422);
        }

        $device = Device::where('device_number', $device_id)->first();
        if(!$device){
            return response()->json(['error' => 'Device is not found!!'], 422);
        }

        $student = Student::where('student_id',$student_id)->first();
        if(!$student){
            return response()->json(['error' => 'Student is not found!!'], 422);
        }

        if ($student->organization_id != $device->organization_id){
            return response()->json(['error' => 'Student is not found!!'], 422);
        }

        if ($student->guardian_email)
        {
            Mail::to($student->guardian_email)
                ->send(new AttendanceMail());
        }

        DB::table('attendences')->insertOrIgnore([
            'device_id' => $device->id,
            'student_id' => $student->id,
            'organization_id' => $device->organization_id,
            'arrived_time' => $timestamp
        ]);

        return response(['message' => "successfully attendance inserted"],200);
    }

    public function attendance()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('attendance.view')){
            abort(403);
        }

        if ($authUser->isSuperAdmin()){
            $organizations = Organization::latest()->get();
        }else{
            $organizations = Organization::where('id',$authUser->organization_id)->get();
        }

        $students = Student::FilterByOrganization()->latest()->get();
        $devices = Device::FilterByOrganization()->latest()->get();

        return view('admin.attendance.index',compact('organizations','devices','students'));
    }

    public function attendanceList(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('attendance.view')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $requestData = $request->all();
        $organization = $requestData['columns'][0]['search']['value'];
        $device = $requestData['columns'][1]['search']['value'];
        $student = $requestData['columns'][2]['search']['value'];
        $startDate = $requestData['columns'][3]['search']['value'];
        $endDate = $requestData['columns'][4]['search']['value'];

        $attendances = Attendence::FilterByOrganization()->latest();

        if ($organization) {
            $attendances->where('organization_id',$organization);
        }
        if ($device) {
            $attendances->where('device_id', $device);
        }
        if ($student) {
            $attendances->where('student_id', $student);
        }

        if ($startDate) {
            $startDate = Carbon::parse($startDate);
            $attendances->where('arrived_time', '>=', $startDate->startOfDay());
        }
        if ($endDate) {
            $endDate = Carbon::parse($endDate);
            $attendances->where('arrived_time', '<=', $endDate->endOfDay());
        }

        return DataTables::of($attendances)
            ->addIndexColumn()
            ->addColumn('student', function ($attendances){
                return '<span class="badge badge-round badge-success badge-lg">' . $attendances->student->name . '</span>';
            })
            ->addColumn('device', function ($attendances){
                return '<span class="badge badge-round badge-primary badge-lg">' . $attendances->device->name . '</span>';
            })
            ->addColumn('organization', function ($attendances){
                return '<span class="badge badge-round badge-danger badge-lg">' . $attendances->organization->name . '</span>';
            })
            ->addColumn('date', function ($attendances){
                return (new \DateTime($attendances->arrived_time))->format('Y-m-d');
            })
            ->addColumn('time', function ($attendances){
                return (new \DateTime($attendances->arrived_time))->format('g:i A');;
            })
            ->rawColumns(['student','organization','device','date','time'])
            ->make(true);
    }

    public function sendFacebookMessage($recipientId, $messageText)
    {
        $accessToken = 'EAAQxBhJZCcu8BAEZA52AnQxZAE7VJtU7VVXACSpuGgaMMoU9FWEvZA2cphsZAyxTuU7Hx4GZAYYtKVwpiuJFyaNOxu9eRTEhfMd5lqlZAOp9ez9zU2uC58qZC7CfB2ZCVbNsoysZBTLQXTzdr9dFxZAwnAC0PlH906UYwp0bJhTWisZCh6ZA253ccA1PH';

        $url = 'https://graph.facebook.com/v14.0/me/messages';

        $response = $this->client->post($url, [
            'query' => ['access_token' => $accessToken],
            'json' => [
                'recipient' => ['id' => $recipientId],
                'message' => ['text' => $messageText]
            ]
        ]);
    }

}
