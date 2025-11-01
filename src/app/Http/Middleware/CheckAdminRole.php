<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
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
        // 認証済みで一般ユーザーの場合は一般ユーザーの勤怠一覧ページにリダイレクト
        if (Auth::check() && !Auth::user()->is_admin) {
            return redirect('/attendance/list');
        }

        return $next($request);
    }
}
