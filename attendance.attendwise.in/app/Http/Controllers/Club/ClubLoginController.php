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

        // First attempt standard club manager login (e.g. for the club admin)
        if (Auth::guard('club')->attempt($credentials, $remember)) {
            $manager = Auth::guard('club')->user();
            
            if ($manager->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended(route('club.dashboard'));
            }

            // Check if this member has a user group assigned
            $hasGroup = false;

            $student = \App\Models\Student::where('email', $manager->email)
                ->orWhere('roll_number', str_replace('@student.attendwise.in', '', $manager->email))
                ->first();
            
            $faculty = \App\Models\Faculty::where('email', $manager->email)->first();

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
                    'email' => 'Not Authorized. You do not have a user group assigned.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('club.dashboard'));
        }

        // If standard attempt fails, check if they are a student
        $student = \App\Models\Student::where('email', $credentials['email'])->first();
        if ($student && \Illuminate\Support\Facades\Hash::check($credentials['password'], $student->password)) {
            $member = \App\Models\ClubMember::where('member_type', 'student')
                ->where('member_id', $student->id)
                ->whereNotNull('club_user_group_id')
                ->first();
                
            if ($member) {
                $manager = \App\Models\ClubManager::firstOrCreate(
                    ['email' => $student->email],
                    [
                        'club_id' => $member->club_id,
                        'name' => $student->name,
                        'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                        'role' => 'member_manager' // default fallback role
                    ]
                );
                
                // If they exist but club_id differs (e.g. moved clubs), update it
                if ($manager->club_id !== $member->club_id) {
                    $manager->update(['club_id' => $member->club_id]);
                }

                Auth::guard('club')->login($manager, $remember);
                $request->session()->regenerate();
                return redirect()->intended(route('club.dashboard'));
            }
        }

        // Check if they are a faculty
        $faculty = \App\Models\Faculty::where('email', $credentials['email'])->first();
        if ($faculty && \Illuminate\Support\Facades\Hash::check($credentials['password'], $faculty->password)) {
            $member = \App\Models\ClubMember::where('member_type', 'faculty')
                ->where('member_id', $faculty->id)
                ->whereNotNull('club_user_group_id')
                ->first();
                
            if ($member) {
                $manager = \App\Models\ClubManager::firstOrCreate(
                    ['email' => $faculty->email],
                    [
                        'club_id' => $member->club_id,
                        'name' => $faculty->name,
                        'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                        'role' => 'member_manager'
                    ]
                );
                
                if ($manager->club_id !== $member->club_id) {
                    $manager->update(['club_id' => $member->club_id]);
                }

                Auth::guard('club')->login($manager, $remember);
                $request->session()->regenerate();
                return redirect()->intended(route('club.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or you are not authorized.',
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
