<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        // if ($this->auth->guard($guard)->guest()) {
        if ($request->bearerToken()) {
            $token = $request->bearerToken();
            $checkToken = User::where("remember_token", $token)->count();
            // $check_token = Participant::where('token', $token)->count();
            if ($checkToken == 0) {
                $res['success'] = false;
                $res['message'] = 'Permission not allowed!';

                return response($res);
            }
        } else {
            $res['success'] = false;
            $res['message'] = 'Login please!';

            return response($res);
        }
        // }

        return $next($request);
    }
}
