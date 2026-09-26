<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class CheckClubRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::guard('club')->check()) {
            return redirect()->route('club.login');
        }

        $user = Auth::guard('club')->user();

        if (!in_array($user->role, $roles)) {
            return redirect()->route('club.dashboard')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
