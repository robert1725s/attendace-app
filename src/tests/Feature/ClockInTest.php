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
        $this->loginAsUser();

        // 2. 画面に「出勤」ボタンが表示されていることを確認する
        $response = $this->get('/attendance');
        $response->assertSee('出勤', false);

        // 3. 出勤の処理を行う
        $response = $this->from('/attendance')->post('/attendance/stamp');

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

    /**
     * 出勤時刻が勤怠一覧画面で確認できる
     * 1. ステータスが勤務外のユーザーにログインする
     * 2. 出勤の処理を行う
     * 3. 勤怠一覧画面から出勤の日付を確認する
     * 勤怠一覧画面に出勤時刻が正確に記録されている
     */
    public function test_clock_in_time_is_displayed_on_list_page()
    {
        // 1. ステータスが勤務外のユーザーにログインする
        $this->loginAsUser();

        // 2. 出勤の処理を行う
        $clockInTime = now();
        $this->post('/attendance/stamp');

        // 3. 勤怠一覧画面から出勤の日付を確認する
        $response = $this->get('/attendance/list');

        // 出勤時刻が正確に記録されている
        $expectedTime = $clockInTime->format('H:i');
        $response->assertSee($expectedTime, false);
    }
}
