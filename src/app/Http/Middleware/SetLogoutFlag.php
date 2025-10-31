<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetLogoutFlag
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // ログアウトリクエストの場合、ユーザー情報をリクエスト属性に保存
        if ($request->is('logout') && Auth::check()) {
            $user = Auth::user();
            $request->attributes->set('logout_is_admin', $user->is_admin);
        }

        return $next($request);
    }
}
