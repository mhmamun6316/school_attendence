<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Device;
use App\Models\Admin\Organization;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now()->format('M d, Y');
        $roles = Role::count();
        $permissions = Permission::count();
        $admins = User::count();
        $organizations = Organization::count();
        $devices = Device::count();

        return view('admin.home',compact([
            'currentDate','roles','permissions','admins','organizations','devices']
        ));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
