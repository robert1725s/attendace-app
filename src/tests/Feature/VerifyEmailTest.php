<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 会員登録後、認証メールが送信される
     * 1. 会員登録をする
     * 2. 認証メールを送信する
     */
    public function test_verification_email_is_sent_after_registration()
    {
        // メール送信をフェイク
        Notification::fake();

        // 1. 会員登録をする
        $this->from('/register')->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 2. 登録したメールアドレス宛に認証メールが送信されている
        $user = User::where('email', 'test@example.com')->first();

        // 認証メールが送信されたことを確認
        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }

    /**
     * メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する
     * 1. メール認証導線画面を表示する
     * 2. 「認証はこちらから」ボタンを押下
     * 3. メール認証サイトを表示する
     */
    public function test_verification_button_redirects_to_mailhog()
    {
        // 未認証ユーザーを作成
        $user = $this->createUnverifiedUser();

        // 1. メール認証導線画面を表示する
        $this->actingAs($user);
        $response = $this->get('/verify_email');

        // 「認証はこちらから」ボタンが表示されている
        $response->assertSee('認証はこちらから');

        // 2. Mailhogへのリンクが存在することを確認
        $response->assertSee('http://localhost:8025/', false);
    }

    /**
     * メール認証サイトのメール認証を完了すると、勤怠登録画面に遷移する
     * 1. メール認証を完了する
     * 2. 勤怠登録画面を表示する
     */
    public function test_email_verification_redirects_to_attendance_page()
    {
        // 未認証ユーザーを作成
        $user = $this->createUnverifiedUser();

        // メール認証URLを生成
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(10),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // 1. メール認証を完了する
        $this->actingAs($user);
        $response = $this->get($verificationUrl);

        // 2. 勤怠登録画面に遷移する
        $response->assertRedirect('/attendance');

        // メール認証が完了していることを確認
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
