<?php

namespace App\Http\Controllers;

use App\Models\Admin\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('user.index',compact('roles'));
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

            if ($user){
                $user->roles()->attach($request->role_id);
            }

            return response()->json(['success'=>"User Added Successfully"]);
        }catch(Exception $e){
            Log::info("organization adding error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function show($id)
    {
        //
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
        //
    }
}
