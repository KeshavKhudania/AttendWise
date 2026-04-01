<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Faculty;

class FacultyLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('faculty.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // if (Auth::guard('faculty')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
        //     return redirect()->intended(route('faculty.dashboard'));
        // }
        $faculty = Faculty::where('email_hash', search_hash($request->email))->first();
        if ($faculty && ($faculty->password) === $request->password) {
            Auth::guard('faculty')->login($faculty, $request->filled('remember'));
            return redirect()->intended(route('faculty.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'.$faculty->password,
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('faculty')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('faculty.login');
    }
}
