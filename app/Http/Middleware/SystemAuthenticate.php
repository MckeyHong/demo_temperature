<?php

namespace App\Http\Middleware;

use Closure;

class SystemAuthenticate
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
        // 未登入
        if (!session()->has('user_id')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('/login');
            }
        }
        return $next($request);
    }
}
