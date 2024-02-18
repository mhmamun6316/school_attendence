<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrganizationController extends Controller
{

    public function index()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('organization.view')){
            abort(403);
        }

        return view('admin.organization.index');
    }

    public function organizationList()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('organization.view')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $userOrganizationId = $authUser->organization_id;

        $organizations = Organization::where('id', $userOrganizationId)
            ->with('childrenRecursive')
            ->get();

        return response()->json($organizations);
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('organization.create')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'address' => 'required|string',
            'parent_id' => 'required|exists:organizations,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        try{
            $organization = new Organization();
            $organization->name = $request->name;
            $organization->address = $request->address;
            $organization->parent_id = $request->parent_id;
            $organization->save();

            return response()->json(['success'=>"Organization Added Successfully"]);
        }catch(Exception $e){
            Log::info("organization adding error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('organization.edit')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $organization = Organization::find($id);
        if(!$organization){
            return response()->json(['error'=>"Organization Not Found"],404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        try{
            $organization = Organization::findOrFail($id);
            $organization->name = $request->input('name');
            $organization->address = $request->input('address');
            $organization->save();

            return response()->json(['success'=>"Organization Updated Successfully"]);
        }catch(Exception $e){
            Log::info("organization update error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('organization.delete')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $organization = Organization::find($id);
        if(!$organization){
            return response()->json(['error'=>"Organization Not Found"],404);
        }

        DB::beginTransaction();
        try{
            if($organization->parent_id === 0){
                return response()->json(['error'=>"Can't Delete Parent Organization"],404);
            }

            // checking associated records
            if ($organization->users()->exists()) {
                return response()->json(['error'=>"Cannot delete organization with associated users."],404);
            }

            if ($organization->students()->exists()) {
                return response()->json(['error'=>"Cannot delete organization with associated students."],404);
            }

            if ($organization->devices()->exists()){
                $organization->devices()->update(['is_archived' => 1]);
            }

            $organization->delete();

            DB::commit();
            return response()->json(['success'=>"Organization Deleted Successfully"]);
        }catch(Exception $e){
            DB::rollBack();
            Log::debug("Error in organization delete:".$e->getMessage());
            Log::debug("Error in organization delete:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
