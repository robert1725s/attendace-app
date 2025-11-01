<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
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
        // 認証済みで管理者の場合は管理者の勤怠一覧ページにリダイレクト
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect('/admin/attendances/list');
        }

        return $next($request);
    }
}
