<?php

namespace App\Http\Middleware;

use App\Permission;
use App\RolePermission;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::user()) {
            return redirect('login');
        }

        if ((in_array(Auth::user()->role_id, [SUPER_ADMIN, ADMIN, ACCOUNTANT, MANAGER, HUMAN_RESOURCE]) === false)) {
            return redirect('login');
        }

        if (!$this->checkPermissions($request)) {
            \Session::flash(ERROR, __("default_label.no_action_allowed"));
            return Redirect()->back();
        }

        return $next($request);
    }

    private function checkPermissions($request)
    {
        $path = head(explode('/', last(explode('admin/', $request->getRequestUri()))));
        $lastPath = last(explode('/', last(explode('admin/', $request->getRequestUri()))));
        $method = $request->method();

        // $path = str_replace('-', '_', $path);

        if (is_string($path)) {
            if (strpos($request->getRequestUri(), 'complete-profile') == false && Auth::user()->role_id == SUPER_ADMIN) {
                return true;
            }

            if ($path != 'profile' && $lastPath != 'logout' && $lastPath != 'dashboard') {

                $rolPermissions = Auth::User()->role;
                if (count($rolPermissions->permissions)) {

                    $permission = Permission::whereCode($path)->first();

                    if ($permission) {
                        if (strpos($request->getRequestUri(), 'delete') !== false || strpos($request->getRequestUri(), 'edit') !== false) {
                            $method = WRITE;
                        } else {
                            $method = $this->postToGet($lastPath, $method);
                        }
                        // $action = $this->checkRouteAction($lastPath);
                        // return RolePermission::wherePermissionId($permission->id)->whereRoleId($rolPermissions->id)->whereActionId($action)->exists();
                        return RolePermission::wherePermissionId($permission->id)->whereRoleId($rolPermissions->id)->whereActionId($method == 'GET' ? READ : WRITE)->exists();
                    } else return false;
                } else return false;
            } else return true;
        }
    }

    /* private function checkRouteAction($lastPath)
    {
        $writeRequests = ['add', 'store', 'save', 'delete', 'update', 'lock'];
        $readRequests = ['listing', 'details', 'edit'];
        if (in_array($lastPath, $writeRequests)) {
            return WRITE;
        } else if (in_array($lastPath, $readRequests)) {
            return READ;
        }
    } */

    private function postToGet($lastPath, $method)
    {
        $listArr = ['listing', 'details'];

        if (in_array($lastPath, $listArr)) {
            return 'GET';
        } else {
            return $method;
        }
    }
}
