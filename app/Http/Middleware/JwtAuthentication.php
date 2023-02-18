<?php

namespace App\Http\Middleware;

use App\Models\NotificationDevice;
use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTAuthentication
{

    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;


    /**
     * Create a new BaseMiddleware instance.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory  $response
     * @param \Illuminate\Contracts\Events\Dispatcher  $events
     * @param \Tymon\JWTAuth\JWTAuth  $auth
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->header('Authorization')) {

            try {
                $user = JWTAuth::parseToken()->authenticate();
                // dd($user);
                if ($user) {
                    Auth::guard();
                    $response = $next($request);
                    return $response;
                } else {
                    return response()->json(['success' => false, 'message' => __('default_label.no_action_allowed')], 403);
                }

                auth('api')->invalidate();
                return response()->json(['success' => false, 'message' => 'Token has been expired'], 401);
            } catch (TokenExpiredException $e) {
                return response()->json(['success' => false, 'message' => 'Token has been expired'], 401);
            } catch (TokenBlacklistedException $e) {
                return response()->json(['success' => false, 'message' => 'Token has been expired'], 401);
            } catch (JWTException $e) {
                return response()->json(['success' => false, 'message' => 'Token has been expired'], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Authorization token is missing'
            ], 401);
        }
    }
}
