<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class ClockOutTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 退勤ボタンが正しく機能する
     * 1. ステータスが勤務中のユーザーにログインする
     * 2. 画面に「退勤」ボタンが表示されていることを確認する
     * 3. 退勤の処理を行う
     * 画面上に「退勤」ボタンが表示され、処理後に画面上に表示されるステータスが「退勤済」になる
     */
    public function test_clock_out_button_works_correctly()
    {
        // 1. ステータスが勤務中のユーザーにログインする
        $user = $this->createWorkingUser();
        $this->actingAs($user);

        // 2. 画面に「退勤」ボタンが表示されていることを確認する
        $response = $this->get('/attendance');
        $response->assertSee('退勤', false);

        // 3. 退勤の処理を行う
        $response = $this->post('/attendance/stamp');

        // リダイレクトされる
        $response->assertRedirect('/attendance');

        // リダイレクト先でステータスが「退勤済」になる
        $response = $this->get('/attendance');
        $response->assertSee('退勤済', false);
    }
}
