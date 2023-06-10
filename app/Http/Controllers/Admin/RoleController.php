<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Exception;

class RoleController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('role.create')){
            abort(403);
        }

        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function rolesList(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('role.view')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

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
                                    <a id="edit_btn" href="'. route('admin.roles.edit',$roles->id) .'" class="btn btn-warning btn-xs btn-shadow-warning">Edit</a>
                                    <a id="delete_btn" data-role-id="'.$roles->id.'" class="btn btn-danger btn-xs btn-shadow-danger">Delete</a>
                                </div>';
                return $actionBtn;

                $actionBtn = '<div class="actions">';

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('device.edit')) {
                    $actionBtn .= '<a id="edit_btn" href="'. route('admin.roles.edit',$roles->id) .'" class="btn btn-warning btn-xs btn-shadow-warning">Edit</a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('device.delete')) {
                    $actionBtn .= '<a id="delete_btn" data-role-id="'.$roles->id.'" class="btn btn-danger btn-xs btn-shadow-danger">Delete</a>';
                }

                $actionBtn .= '</div>';
            })
            ->rawColumns(['action','permission'])
            ->make(true);
    }

    public function create()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('role.create')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $allPermissions  = Permission::all();
        $permissionGroups= Permission::permissionGroups();

        return view('admin.roles.create', compact('allPermissions', 'permissionGroups'));
    }


    public function store(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('role.create')){
            abort(403);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:100|unique:roles'
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return back()->with(['error' => $firstError], 422);
        }

        try{
            $role = Role::create(['name' => $request->name]);
            $permissions = $request->input('permissions');

            if (!empty($permissions)) {
                $role->permissions()->sync($permissions);
            }

            return redirect()->route('admin.roles.index')->with("success","Role has been created !!");
        }catch(Exception $e){
            Log::info("Roles adding error:".$e->getLine());
            return back()->with(['error'=>$e->getMessage()],500);
        }
    }

    public function edit($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('role.edit')){
            abort(403);
        }

        $role = Role::find($id);
        $allPermissions  = Permission::all();
        $permissionGroups= Permission::permissionGroups();

        return view('admin.roles.edit', compact('role','allPermissions', 'permissionGroups'));
    }


    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('role.edit')){
            abort(403);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:100|unique:roles,name,' . $id
        ], [
            'name.requried' => 'Please give a role name'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return back()->with(['error' => $firstError], 422);
        }

        try{
            $role = Role::find($id);
            $permissions = $request->input('permissions');

            if (!empty($permissions)) {
                $role->name = $request->name;
                $role->save();
                $role->permissions()->sync($permissions);
            }

            return redirect()->route('admin.roles.index')->with("success","Role has been updated !!");
        }catch(Exception $e){
            Log::info("Roles updating error:".$e->getLine());
            return back()->with(['error'=>$e->getMessage()],500);
        }
    }


    public function destroy($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('role.delete')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        try{
            $role = Role::find($id);
            if (!is_null($role)){
                $role->permissions()->detach();
                $role->delete();
            }else{
                return response()->json(['error'=>"Role Not Found"],404);
            }
            return response()->json(['success' => 'Role Deleted successfully'], 200);

        }catch(Exception $e){
            Log::debug("Error in role delete:".$e->getMessage());
            Log::debug("Error in role delete:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
