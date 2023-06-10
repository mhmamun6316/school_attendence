<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Exception;

class PackageController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('package.create')){
            abort(403);
        }

        $categories = Category::all();
        return view('admin.package.index',compact('categories'));
    }

    public function packageList()
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('package.view')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $packages = Package::latest()->get();

        return DataTables::of($packages)
            ->addIndexColumn()
            ->addColumn('categories', function ($packages){
                $categories = '';
                foreach ($packages->categories as $category) {
                    $categories .= '<span class="badge badge-round badge-success badge-lg mr-1">' . $category->name . '</span>';
                }

                return $categories;
            })
            ->addColumn('action', function($packages) use ($authUser){
                $actionBtn = '<div class="actions">';

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('package.edit')) {
                    $actionBtn .= '<a id="edit_btn" data-package-id="'.$packages->id.'" class="btn btn-warning btn-xs btn-shadow-warning">Edit</a>';
                }

                if ($authUser->isSuperAdmin() || $authUser->hasPermission('package.delete')) {
                    $actionBtn .= '<a id="delete_btn" data-package-id="'.$packages->id.'" class="btn btn-danger btn-xs btn-shadow-danger">Delete</a>';
                }

                $actionBtn .= '</div>';

                return $actionBtn;
            })
            ->rawColumns(['action','categories'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('package.create')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        try{
            $package = Package::create([
                'name' => $request->name,
                'price' => $request->price,
                'status' => 1,
            ]);

            $categories = $request->input('categories');
            $package->categories()->attach($categories);

            return response()->json(['success'=>"Package Added Successfully"]);
        }catch(Exception $e){
            Log::info("Package adding error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function edit($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('package.edit')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $package = Package::with('categories')->findOrFail($id);

        if (!$package) {
            return response()->json(['error' => 'Package not found'], 404);
        }

        return response()->json(['package' => $package], 200);
    }

    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('package.edit')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();

            return response()->json(['error' => $firstError], 422);
        }

        try{
            $package = Package::findOrFail($id);
            $package->name = $request->name;
            $package->price = $request->price;
            $package->save();

            $package->categories()->sync($request->categories);

            return response()->json(['success'=>"Package Updated Successfully"]);
        }catch(Exception $e){
            Log::info("device updating error:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && !$authUser->hasPermission('package.delete')){
            return response()->json(['error' => "you are not authorized for this page"], 403);
        }

        try{
            $package = Package::findOrFail($id);

            if (!is_null($package)){
                $package->categories()->detach();
                $package->delete();
            }else{
                return response()->json(['error'=>"Package Not Found"],404);
            }
            return response()->json(['success' => 'Package Deleted successfully'], 200);

        }catch(Exception $e){
            Log::debug("Error in Package delete:".$e->getMessage());
            Log::debug("Error in Package delete:".$e->getLine());
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }
}
