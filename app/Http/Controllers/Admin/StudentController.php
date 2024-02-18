<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Device;
use App\Models\Admin\Package;
use App\Models\Admin\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    protected $formattedDate;

    public function __construct()
    {
        $this->formattedDate = time();
    }

    public function index()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.view')){
            abort(403);
        }

        $packages = Package::latest()->get();

        return view('admin.student.index',compact('packages'));
    }

    public function studentList(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.view')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $students = Student::FilterByOrganization()->latest()->get();

        return DataTables::of($students)
            ->addIndexColumn()
            ->editColumn('organization', function ($students){
                return '<span class="custom-badge">' . str_replace(' ', '_', $students->organization->name) . '</span>';
            })
            ->editColumn('package', function ($students){
                $package = $students->activePackage->first();
                if (!$package){
                    $package = "No Active Package";
                }else{
                    $package = $package->name;
                }
                return '<span class="badge badge-round badge-success badge-lg mr-1">'. $package .'</span>';
            })
            ->addColumn('action', function($students) use ($authUser){
                $actionBtn = '<div class="actions">';

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('student.edit')) {
                    $actionBtn .= '<a id="edit_btn" data-student-id="'.$students->id.'" class="btn btn-warning btn-xs btn-shadow-warning"><i class="fa-solid fa-pen-to-square"></i></a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('student.delete')) {
                    $actionBtn .= '<a id="delete_btn" data-student-id="'.$students->id.'" class="btn btn-danger btn-xs btn-shadow-danger"><i class="fa-solid fa-trash-can"></i></a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('student.history')) {
                    $actionBtn .= '<a id="log_btn" data-student-id="'.$students->id.'" class="btn btn-secondary btn-xs btn-shadow-secondary" title="Package history"><i class="fa-solid fa-file"></i></a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('student.deactivate')) {
                    $actionBtn .= '<a id="deactive_btn" data-student-id="'.$students->id.'" class="btn btn-success btn-xs btn-shadow-success" title="De-active package"><i class="fa-solid fa-power-off"></i></a>';
                }

                $actionBtn .= '</div>';

                return $actionBtn;
            })
            ->rawColumns(['action','organization','package'])
            ->make(true);
    }

    public function studentLog($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.history')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $student = Student::findOrFail($id);

        $logData = $student->packages()->withPivot('start_date', 'end_date')->get();
        return view('admin.student.log', ['logData' => $logData]);
    }

    public function studentDeactive($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.deactivate')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        try{
            $student = Student::findOrFail($id);
            $currentPackage = $student->activePackage->first();
            if (!$currentPackage){
                return response()->json(['error' => "Studen't dont have any active package"],500);
            }

            $currentPackage->pivot->update([
                'active_status' => false,
                'end_date' => now(),
            ]);

            return response()->json(['success' => 'Student deactivated successfully'], 200);
        }catch(\Exception $e){
            Log::info("Student deactivated error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.create')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:191',
            'student_id' => 'required|unique:students|max:191',
            'avatar' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'address' => 'nullable|string|max:191',
            'guardian_name' => 'nullable|string|max:191',
            'guardian_phone' => 'nullable|string|max:191',
            'guardian_email' => 'nullable|email|max:191',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        // Upload the avatar image if provided
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        try{
            // Create a new student
            $student = Student::create([
                'name' => $request->name,
                'student_id' => $request->student_id,
                'avatar' => $avatarPath,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'guardian_phone' => $request->guardian_phone,
                'guardian_email' => $request->guardian_email,
                'organization_id' => $request->organization_id,
            ]);

            // Assigning a Package to a Student
            if ($request->package_id){
                $package = Package::findOrFail($request->package_id);
                Log::info($this->formattedDate);
                $student->packages()->sync([$package->id => [
                    'history_id' =>  $this->formattedDate,
                    'start_date' => now(),
                    'end_date' => null,
                    'active_status' => true,
                ]]);
            }

            return response()->json(['success' => 'Student Added successfully'], 200);
        }catch(\Exception $e){
            Log::info("Student adding error:".$e->getLine());
            Log::info("Student adding error:".$e->getMessage());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function edit($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.edit')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $student = Student::with('activePackage')->findOrFail($id);

        if (!$student) {
            return response()->json(['error' => 'student not found'], 404);
        }

        return response()->json(['student' => $student], 200);
    }

    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.edit')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:191',
            'student_code' => 'required|max:191|unique:students,student_id,'.$id,
            'avatar' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'address' => 'nullable|string|max:191',
            'guardian_name' => 'nullable|string|max:191',
            'guardian_phone' => 'nullable|string|max:191',
            'guardian_email' => 'nullable|email|max:191',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        try{
            $newPackageId = $request->package_id;
            $student = Student::findOrFail($id);
            $currentPackage = $student->activePackage->first();

            if ($currentPackage) {
                if ($currentPackage->id != $newPackageId) {
                    // Deactivate the current package
                    $currentPackage->pivot->update([
                        'active_status' => false,
                        'end_date' => now(),
                    ]);

                    // Assign the new package
                    if ($newPackageId){
                        $student->packages()->attach($newPackageId, [
                            'history_id' => time(),
                            'start_date' =>  now(),
                            'end_date' => null,
                            'active_status' => true,
                        ]);
                    }
                }
            }else if (isset($newPackageId)){
                // Assign the new package
                $student->packages()->attach($newPackageId, [
                    'history_id' => time(),
                    'start_date' =>  now(),
                    'end_date' => null,
                    'active_status' => true,
                ]);
            }

            $student->update([
                'name' => $request->name,
                'student_id' => $request->student_code,
                'avatar' => $avatarPath,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'guardian_phone' => $request->guardian_phone,
                'guardian_email' => $request->guardian_email,
                'organization_id' => auth()->user()->organization_id,
            ]);

            return response()->json(['success' => 'Student Updated successfully'], 200);
        }catch(\Exception $e){
            Log::info("Student updating error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.delete')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        try{
            $student = Student::findOrFail($id);

            // Update the status of the student's records to false
            $student->packages()->update([
                'active_status' => false,
                'is_archived' => 1
            ]);

            if ($student->attendances()->exists()){
                $student->attendances()->update(['is_archived' => 1]);
            }

            $student->update(['is_archived' => 1]);

            return response()->json(['success' => 'Student Deleted successfully'], 200);
        }catch(\Exception $e){
            Log::debug("Error in Student delete:".$e->getMessage());
            Log::debug("Error in Student delete:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function newStudent()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.view')){
            abort(403);
        }

        $packages = Package::latest()->get();

        return view('admin.new_student.index',compact('packages'));
    }

    public function newStudentList(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.view')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $students = Student::FilterByOrganization()->where('is_archived',0)->latest()->get();

        return DataTables::of($students)
            ->addIndexColumn()
            ->editColumn('organization', function ($students){
                return '<span class="custom-badge">' . str_replace(' ', '_', $students->organization->name) . '</span>';
            })
            ->editColumn('package', function ($students){
                $package = $students->activePackage->first();
                if (!$package){
                    $package = "No Active Package";
                }else{
                    $package = $package->name;
                }
                return '<span class="badge badge-round badge-success badge-lg mr-1">'. $package .'</span>';
            })
            ->addColumn('action', function($students) use ($authUser){
                $actionBtn = '<div class="actions">';

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('student.edit')) {
                    $actionBtn .= '<a id="edit_btn" data-student-id="'.$students->id.'" class="btn btn-warning btn-xs btn-shadow-warning"><i class="fa-solid fa-pen-to-square"></i></a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('student.delete')) {
                    $actionBtn .= '<a id="delete_btn" data-student-id="'.$students->id.'" class="btn btn-danger btn-xs btn-shadow-danger"><i class="fa-solid fa-trash-can"></i></a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('student.history')) {
                    $actionBtn .= '<a id="log_btn" data-student-id="'.$students->id.'" class="btn btn-secondary btn-xs btn-shadow-secondary" title="Package history"><i class="fa-solid fa-file"></i></a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('student.deactivate')) {
                    $actionBtn .= '<a id="deactive_btn" data-student-id="'.$students->id.'" class="btn btn-success btn-xs btn-shadow-success" title="De-active package"><i class="fa-solid fa-power-off"></i></a>';
                }

                $actionBtn .= '</div>';

                return $actionBtn;
            })
            ->rawColumns(['action','organization','package'])
            ->make(true);
    }
}
