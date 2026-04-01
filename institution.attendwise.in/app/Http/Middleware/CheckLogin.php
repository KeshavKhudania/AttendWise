<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has("user_id")) {
            Session::put("redirect_url", $request->fullUrl());
            return redirect()->to(route("login_view"));
        }
        if (Session::has("redirect_url") && Session::get("redirect_url") != $request->fullUrl()) {
            $uri = Session::get("redirect_url");
            Session::forget("redirect_url");
            return redirect()->to($uri);
        }
        
        return $next($request);
    }
}
