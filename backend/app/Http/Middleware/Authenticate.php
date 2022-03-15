<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        //로그인하지않고 무언가를 하려 하면 리다이렉트 시킴
        if (!$request->expectsJson()) {
            return route('api/login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($jwt = $request->cookie('jwt')) {
            $request->headers->set('Authorization', 'Bearer ' . $jwt);
        } else {
            return response([
                'message' => '로그인 하고 시도하세요'
            ], 401);
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
