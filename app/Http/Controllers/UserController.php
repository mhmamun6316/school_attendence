<?php

namespace App\Http\Controllers;

use App\Models\Admin\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('user.index',compact('roles'));
    }

    public function usersList()
    {
        $users = User::latest()->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('organization', function ($users){
                return $users->organization->name;
            })
            ->addColumn('role', function ($users){
                $role = $users->roles[0];
                $role = '<span class="custom-badge">' . $role->name . '</span>';

                return $role;
            })
            ->addColumn('action', function($users){
                $actionBtn = '<div class="actions">
                                    <a id="edit_btn" data-user-id="'.$users->id.'" class="btn btn-warning btn-xs btn-shadow-warning">Edit</a>
                                    <a id="delete_btn" data-user-id="'.$users->id.'" class="btn btn-danger btn-xs btn-shadow-danger">Delete</a>
                                </div>';
                return $actionBtn;
            })
            ->rawColumns(['action','role'])
            ->make(true);
    }

    public function store(Request $request)
    {
        //        if(!auth()->user()->can('create',Organization::class)){
//            return response()->json(['message','UnAuthorized '],403);
//        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'role_id' => 'required|exists:roles,id',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->organization_id = $request->organization_id;
            $user->password = Hash::make('123456');
            $user->save();

            $user->roles()->attach($request->role_id);

            return response()->json(['success'=>"User Added Successfully"]);
        }catch(Exception $e){
            Log::info("user adding error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user['role_id'] = $user->roles[0]->id;

        return response()->json(['user' => $user], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        try{
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->organization_id = $request->organization_id;
            $user->save();

            $user->roles()->sync($request->role_id);

            return response()->json(['success'=>"User Updated Successfully"]);
        }catch(Exception $e){
            Log::info("user updating error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        try{
            $user = User::find($id);
            if (!is_null($user)){
                $user->roles()->detach();
                $user->delete();
            }else{
                return response()->json(['error'=>"User Not Found"],404);
            }
            return response()->json(['success' => 'User Deleted successfully'], 200);

        }catch(Exception $e){
            Log::debug("Error in User delete:".$e->getMessage());
            Log::debug("Error in User delete:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
