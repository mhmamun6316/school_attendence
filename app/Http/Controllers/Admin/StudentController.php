<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Package;
use App\Models\Admin\Student;
use App\Models\Admin\StudentPackageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.create')){
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
                return '<span class="custom-badge">' . $students->organization->name . '</span>';
            })
            ->editColumn('package', function ($students){
                $package = $students->package->first();
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
                    $actionBtn .= '<a id="edit_btn" data-student-id="'.$students->id.'" class="btn btn-warning btn-xs btn-shadow-warning">Edit</a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('student.delete')) {
                    $actionBtn .= '<a id="delete_btn" data-student-id="'.$students->id.'" class="btn btn-danger btn-xs btn-shadow-danger">Delete</a>';
                }

                $actionBtn .= '</div>';

                return $actionBtn;
            })
            ->rawColumns(['action','organization','package'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.create')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'avatar' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:255',
            'guardian_email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return back()->with(['error' => $firstError], 422);
        }

        // Upload the avatar image if provided
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            Log::info($avatarPath);
        }

        try{
            // Create a new student
            $student = Student::create([
                'name' => $request->name,
                'avatar' => $avatarPath,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'guardian_phone' => $request->guardian_phone,
                'guardian_email' => $request->guardian_email,
                'organization_id' => auth()->user()->organization_id,
            ]);

            // Log the student package assignment
            $package = Package::findOrFail($request->package_id);
            if ($package){
                StudentPackageLog::create([
                    'student_id' => $student->id,
                    'package_id' => $package->id,
                    'status' => true,
                ]);
            }

            return response()->json(['success' => 'Student Added successfully'], 200);
        }catch(\Exception $e){
            Log::info("Student adding error:".$e->getLine());
            return back()->with(['error'=>$e->getMessage()],500);
        }
    }

    public function edit($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.edit')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $student = Student::with('package')->findOrFail($id);

        if (!$student) {
            return response()->json(['error' => 'student not found'], 404);
        }

        return response()->json(['student' => $student], 200);
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('students.delete')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        try{
            $student = Student::findOrFail($id);
            $student->logs()->delete();

            $student->delete();

            return response()->json(['success' => 'Student Deleted successfully'], 200);

        }catch(\Exception $e){
            Log::debug("Error in Student delete:".$e->getMessage());
            Log::debug("Error in Student delete:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
