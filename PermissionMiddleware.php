<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    /**
     * recreate permission namespace one by one
     *
     * example:
     * admin.auth.users.modify.create
     *
     * checks this permissions in this order and exit successfully if found
     * admin
     * admin.auth
     * admin.auth.users
     * admin.auth.users.modify
     * admin.auth.users.modify.create
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        //guests are not allowed
        if (Auth::guest()) {
            abort(403);
        }

        //make array
        $permissions = is_array($permission) ? $permission : explode('|', $permission);

        foreach ($permissions as $p) {

            //explode by the point
            $parts   = explode('.', $p);
            $ability = '';
            foreach ($parts as $part) {

                //recreate
                $ability .= $ability ? '.' . $part : $part;

                if (Auth::user()->can($ability)) {
                    //exit on first match
                    return $next($request);
                }

            }

        }
        //if no permission is matched, deny
        abort(403);

        return $next($request);
    }
}
