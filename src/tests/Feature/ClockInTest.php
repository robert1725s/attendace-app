<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class ClockInTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 出勤ボタンが正しく機能する
     * 1. ステータスが勤務外のユーザーにログインする
     * 2. 画面に「出勤」ボタンが表示されていることを確認する
     * 3. 出勤の処理を行う
     * 画面上に「出勤」ボタンが表示され、処理後に画面上に表示されるステータスが「出勤中」になる
     */
    public function test_clock_in_button_works_correctly()
    {
        // 1. ステータスが勤務外のユーザーにログインする
        $user = $this->createVerifiedUser();
        $this->actingAs($user);

        // 2. 画面に「出勤」ボタンが表示されていることを確認する
        $response = $this->get('/attendance');
        $response->assertSee('出勤', false);

        // 3. 出勤の処理を行う
        $response = $this->post('/attendance/stamp');

        // リダイレクトされる
        $response->assertRedirect('/attendance');

        // リダイレクト先でステータスが「出勤中」になる
        $response = $this->get('/attendance');
        $response->assertSee('出勤中', false);
    }

    /**
     * 出勤は一日一回のみできる
     * 1. ステータスが退勤済であるユーザーにログインする
     * 2. 勤務ボタンが表示されないことを確認する
     * 画面上に「出勤」ボタンが表示されない
     */
    public function test_clock_in_is_only_allowed_once_per_day()
    {
        // 1. ステータスが退勤済であるユーザーにログインする
        $user = $this->createFinishedWorkUser();
        $this->actingAs($user);

        // 2. 勤務ボタンが表示されないことを確認する
        $response = $this->get('/attendance');
        $response->assertDontSee('出勤', false);
    }
}
