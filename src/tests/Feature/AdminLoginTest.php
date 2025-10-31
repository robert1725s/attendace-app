<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * メールアドレスが未入力の場合、バリデーションメッセージが表示される
     * 1. ユーザーを登録する
     * 2. メールアドレス以外のユーザー情報を入力する
     * 3. ログインの処理を行う
     * 「メールアドレスを入力してください」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_email_is_empty()
    {
        // 1. ユーザーを登録する
        $this->createAdminUser();

        // 2. メールアドレス以外のユーザー情報を入力する
        // 3. ログインの処理を行う
        $this->from('/admin/login')
            ->post('/login', [
                'email' => '',
                'password' => 'password123',
                'login_type' => 'admin',
            ]);

        // 「メールアドレスを入力してください」というバリデーションメッセージが表示される
        $errors = session('errors');
        $this->assertStringContainsString('メールアドレスを入力してください', $errors->first('email'));
    }

    /**
     * パスワードが未入力の場合、バリデーションメッセージが表示される
     * 1. ユーザーを登録する
     * 2. パスワード以外のユーザー情報を入力する
     * 3. ログインの処理を行う
     * 「パスワードを入力してください」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_password_is_empty()
    {
        // 1. ユーザーを登録する
        $this->createAdminUser();

        // 2. パスワード以外のユーザー情報を入力する
        // 3. ログインの処理を行う
        $this->from('/admin/login')
            ->post('/login', [
                'email' => 'admin@example.com',
                'password' => '',
                'login_type' => 'admin',
            ]);

        // 「パスワードを入力してください」というバリデーションメッセージが表示される
        $errors = session('errors');
        $this->assertStringContainsString('パスワードを入力してください', $errors->first('password'));
    }

    /**
     * 登録内容と一致しない場合、バリデーションメッセージが表示される
     * 1. ユーザーを登録する
     * 2. 誤ったメールアドレスのユーザー情報を入力する
     * 3. ログインの処理を行う
     * 「ログイン情報が登録されていません」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_credentials_do_not_match()
    {
        // 1. ユーザーを登録する
        $this->createAdminUser();

        // 2. 誤ったメールアドレスのユーザー情報を入力する
        // 3. ログインの処理を行う
        $this->from('/admin/login')
            ->post('/login', [
                'email' => 'wrong@example.com',
                'password' => 'password123',
                'login_type' => 'admin',
            ]);

        // 「ログイン情報が登録されていません」というバリデーションメッセージが表示される
        $errors = session('errors');
        $this->assertStringContainsString('ログイン情報が登録されていません', $errors->first('email'));
    }
}
