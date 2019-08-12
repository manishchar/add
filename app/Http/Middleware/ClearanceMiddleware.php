<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

class ClearanceMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {       
    $roles = Auth::user()->roles()->pluck('name')->implode(' ');
    $role_id = Auth::user()->roles()->pluck('id')->implode(' ');
    $modulePermission = DB::table('role_has_permissions')->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')->where('permissions.parent_id','0')->where('role_id',$role_id)->select('permissions.id')->get()->pluck('id')->toArray();    
    //dd('manish');
if ($request->is('admin/client')) {
    if(!in_array("1", $modulePermission)){
        abort('401');
    }
}
if($request->is('admin/client/create')){
            $moduleChildPermission = DB::table('role_has_permissions')->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')->where('permissions.parent_id','1')->where('role_id',$role_id)->select('permissions.id')->get()->pluck('id')->toArray(); 
            if(!in_array("21", $moduleChildPermission)){
                abort('401');
            }
}
    
    // if ($request->is('admin/job')) {
    //         if (!Auth::user()->hasPermissionTo('See Job Posts')) {
    //             abort('401');
    //         } else {
    //             return $next($request);
    //         }
    // }

   

   
   
        return $next($request);
    }
}