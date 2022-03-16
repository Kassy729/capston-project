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
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($login_token = $request->cookie('login_token')) {
            return response([
                'tt' => "성공"
            ]);
            $request->headers->set('Authorization', 'Bearer ' . $login_token);
        } else {
            return response([
                'tt' => '실패',
                'message' => '로그인 하고 시도하세요'
            ], 401);
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
