<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'You have successfully logged in.');;
        } else {
            // Authentication failed
            return redirect()->back()->withErrors(['message' => 'Username or password dont match']);
        }
    }

    public function changePassword()
    {
        $currentDate = Carbon::now()->format('M d, Y');
        return view('admin.auth.change_password',compact('currentDate'));
    }

    public function saveChangePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'old_password.required' => 'Please enter your old password.',
            'new_password.required' => 'Please enter a new password.',
            'new_password.min' => 'The new password must be at least 8 characters long.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ]);

        $user = Auth::user();

        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->route('dashboard')->with('success', 'Password changed successfully.');
        } else {
            return redirect()->back()->withErrors(['old_password' => 'Incorrect old password.']);
        }
    }

    public function profile()
    {
        $currentDate = Carbon::now()->format('M d, Y');
        $user = Auth::user();
        return view('admin.auth.profile', compact('user','currentDate'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'address' => 'nullable|string|max:191',
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female',
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'email', 'address', 'phone', 'dob', 'gender'));

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }
}
