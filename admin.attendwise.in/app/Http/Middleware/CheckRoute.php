<?php

namespace App\Http\Middleware;

use App\Models\AdminGroup;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions;
            // print_r();
            $allowed_permissions = unserialize(AdminGroup::find(Crypt::decrypt(Session::get("group_id")))->permissions);
        // if ($request->ajax()) {
        //     if (!in_array(Route::currentRouteName(), $allowed_permissions)) {
        //         return abort(401, "Access not granted ".Route::currentRouteName()." ".json_encode($allowed_permissions));
        //     }else{
        //         // abort(401, "Access Granted.");
        //         // return abort(401, "Access granted ".Route::currentRouteName()." ".json_encode($allowed_permissions));
        //     }
        // }
        if (!in_array(Route::currentRouteName(), $allowed_permissions)) {
            if ($request->ajax()) {
                return abort("401", json_encode(["msg"=>Route::currentRouteName()." Access not granted.", "color"=>"danger", "icon"=>"exclamation-triangle"]));
            }
            return abort("401");
        }
        } catch (\Throwable $th) {
            throw $th;
        }
        return $next($request);
    }
}
