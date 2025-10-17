<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        // viewを指定
        Fortify::registerView(function () {
            return view('auth.register');
        });
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 作成したLoginRequestに紐づけ
        $this->app->bind('Laravel\Fortify\Http\Requests\LoginRequest', \App\Http\Requests\LoginRequest::class);

        //登録後はメール認証誘導画面に遷移
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect('/verify_email');
            }
        });

        // ログイン後の遷移先を制御
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                $user = auth()->user();

                // 管理者の場合は管理画面に遷移
                if ($user->is_admin) {
                    return redirect('/admin/attendances/list');
                }

                // 一般ユーザーでメール未認証の場合
                if ($user->email_verified_at == null) {
                    // メール未認証の場合、認証メールを送信
                    /** @var \App\Models\User $user */
                    $user->sendEmailVerificationNotification();

                    return redirect('/verify_email');
                }

                // 一般ユーザーでメール認証済みの場合
                return redirect('/attendance');
            }
        });

        // メール認証完了後のリダイレクト先を指定
        $this->app->instance(VerifyEmailResponse::class, new class implements VerifyEmailResponse {
            public function toResponse($request)
            {
                return redirect('/attendance');
            }
        });

        //ログアウト後はログイン画面に遷移
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/login');
            }
        });
    }
}
