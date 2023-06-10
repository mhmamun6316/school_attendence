<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('student.create')){
            abort(403);
        }

        return view('admin.student.index');
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
            ->rawColumns(['action','organization'])
            ->make(true);
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
