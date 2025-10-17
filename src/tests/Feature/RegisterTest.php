<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 名前が未入力の場合、バリデーションメッセージが表示される
     */
    public function test_name_is_required()
    {
        // 1. 名前以外のユーザー情報を入力する
        // 2. 会員登録の処理を行う
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 「お名前を入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください'
        ]);
    }

    /**
     * メールアドレスが未入力の場合、バリデーションメッセージが表示される
     */
    public function test_email_is_required()
    {
        // 1. メールアドレス以外のユーザー情報を入力する
        // 2. 会員登録の処理を行う
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 「メールアドレスを入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください'
        ]);
    }

    /**
     * パスワードが8文字未満の場合、バリデーションメッセージが表示される
     */
    public function test_password_must_be_at_least_8_characters()
    {
        // 1. パスワードを8文字未満にし、ユーザー情報を入力する
        // 2. 会員登録の処理を行う
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'pass123',
            'password_confirmation' => 'pass123',
        ]);

        // 「パスワードは8文字以上で入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください'
        ]);
    }

    /**
     * パスワードが一致しない場合、バリデーションメッセージが表示される
     */
    public function test_password_confirmation_must_match()
    {
        // 1. 確認用のパスワードとパスワードを一致させず、ユーザー情報を入力する
        // 2. 会員登録の処理を行う
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        // 「パスワードと一致しません」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'password_confirmation' => 'パスワードと一致しません'
        ]);
    }

    /**
     * パスワードが未入力の場合、バリデーションメッセージが表示される
     */
    public function test_password_is_required()
    {
        // 1. パスワード以外のユーザー情報を入力する
        // 2. 会員登録の処理を行う
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        // 「パスワードを入力してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください'
        ]);
    }

    /**
     * フォームに内容が入力されていた場合、データが正常に保存される
     */
    public function test_user_can_register_successfully()
    {
        // 1. ユーザー情報を入力する
        // 2. 会員登録の処理を行う
        $response = $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // データベースに登録したユーザー情報が保存される
        $this->assertDatabaseHas('users', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
        ]);

        // パスワードがハッシュ化されて保存されているか確認
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }
}
