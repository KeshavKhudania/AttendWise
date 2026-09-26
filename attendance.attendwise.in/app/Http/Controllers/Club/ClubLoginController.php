<?php

namespace App\Http\Controllers\Club;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('club')->check()) {
            return redirect()->route('club.dashboard');
        }
        return view('club.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        if (Auth::guard('club')->attempt($credentials, $remember)) {
            $manager = Auth::guard('club')->user();
            
            // Check if this member has a user group assigned
            $student = \App\Models\Student::where('email', $manager->email)
                ->orWhere('roll_number', str_replace('@student.attendwise.in', '', $manager->email))
                ->first();
            
            $faculty = \App\Models\Faculty::where('email', $manager->email)->first();

            $hasGroup = false;

            if ($student) {
                $member = \App\Models\ClubMember::where('club_id', $manager->club_id)
                    ->where('member_type', 'student')
                    ->where('member_id', $student->id)
                    ->first();
                if ($member && $member->club_user_group_id) {
                    $hasGroup = true;
                }
            }

            if (!$hasGroup && $faculty) {
                $member = \App\Models\ClubMember::where('club_id', $manager->club_id)
                    ->where('member_type', 'faculty')
                    ->where('member_id', $faculty->id)
                    ->first();
                if ($member && $member->club_user_group_id) {
                    $hasGroup = true;
                }
            }

            if (!$hasGroup) {
                Auth::guard('club')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Not Authorized',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('club.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('club')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('club.login');
    }
}
