<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    public function rolesList(Request $request)
    {
        $roles = Role::latest()->get();

        return DataTables::of($roles)
            ->addIndexColumn()
            ->editColumn('permission', function ($roles){
                $permissions = '';
                foreach ($roles->permissions as $permission) {
                    $permissions .= '<span class="badge badge-round badge-success badge-lg mr-1">' . $permission->name . '</span>';
                }

                return $permissions;
            })
            ->addColumn('action', function($roles){
                $actionBtn = '<div class="actions">
                                    <a class="btn btn-warning btn-xs btn-shadow-warning">Edit</a>
                                    <a id="delete_btn" data-role-id="'.$roles->id.'" class="btn btn-danger btn-xs btn-shadow-danger">Delete</a>
                                </div>';
                return $actionBtn;
            })
            ->rawColumns(['action','permission'])
            ->make(true);
    }

    public function create()
    {
        $allPermissions  = Permission::all();
        $permissionGroups= Permission::permissionGroups();

        return view('admin.roles.create', compact('allPermissions', 'permissionGroups'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:roles'
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        $role = Role::create(['name' => $request->name]);
        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
            $role->permissions()->sync($permissions);
        }

        return back()->with("success","Role has been created !!");
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        $role = Role::find($id);

        if (!is_null($role)){
            $role->permissions()->detach();
            $role->delete();
        }

        return response()->json(['message' => 'Role Deleted successfully'], 200);
    }
}
