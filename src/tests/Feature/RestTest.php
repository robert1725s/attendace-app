<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class RestTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 休憩ボタンが正しく機能する
     * 1. ステータスが出勤中のユーザーにログインする
     * 2. 画面に「休憩入」ボタンが表示されていることを確認する
     * 3. 休憩の処理を行う
     * 画面上に「休憩入」ボタンが表示され、処理後に画面上に表示されるステータスが「休憩中」になる
     */
    public function test_rest_start_button_works_correctly()
    {
        // 1. ステータスが出勤中のユーザーにログインする
        $user = $this->createWorkingUser();
        $this->actingAs($user);

        // 2. 画面に「休憩入」ボタンが表示されていることを確認する
        $response = $this->get('/attendance');
        $response->assertSee('休憩入', false);

        // 3. 休憩の処理を行う
        $response = $this->post('/attendance/rest');

        // リダイレクトされる
        $response->assertRedirect('/attendance');

        // リダイレクト先でステータスが「休憩中」になる
        $response = $this->get('/attendance');
        $response->assertSee('休憩中', false);
    }

    /**
     * 休憩は一日に何回でもできる
     * 1. ステータスが出勤中であるユーザーにログインする
     * 2. 休憩入と休憩戻の処理を行う
     * 3. 「休憩入」ボタンが表示されることを確認する
     * 画面上に「休憩入」ボタンが表示される
     */
    public function test_rest_can_be_taken_multiple_times_per_day()
    {
        // 1. ステータスが出勤中であるユーザーにログインする
        $user = $this->createWorkingUser();
        $this->actingAs($user);

        // 2. 休憩入と休憩戻の処理を行う
        $this->post('/attendance/rest'); // 休憩入
        $this->post('/attendance/rest'); // 休憩戻

        // 3. 「休憩入」ボタンが表示されることを確認する
        $response = $this->get('/attendance');
        $response->assertSee('休憩入', false);
    }

    /**
     * 休憩戻ボタンが正しく機能する
     * 1. ステータスが出勤中であるユーザーにログインする
     * 2. 休憩入の処理を行う
     * 3. 休憩戻の処理を行う
     * 休憩戻ボタンが表示され、処理後にステータスが「出勤中」に変更される
     */
    public function test_rest_end_button_works_correctly()
    {
        // 1. ステータスが出勤中であるユーザーにログインする
        $user = $this->createWorkingUser();
        $this->actingAs($user);

        // 2. 休憩入の処理を行う
        $this->post('/attendance/rest');

        // 休憩戻ボタンが表示される
        $response = $this->get('/attendance');
        $response->assertSee('休憩戻', false);

        // 3. 休憩戻の処理を行う
        $response = $this->post('/attendance/rest');

        // リダイレクトされる
        $response->assertRedirect('/attendance');

        // リダイレクト先でステータスが「出勤中」になる
        $response = $this->get('/attendance');
        $response->assertSee('出勤中', false);
    }

    /**
     * 休憩戻は一日に何回でもできる
     * 1. ステータスが出勤中であるユーザーにログインする
     * 2. 休憩入と休憩戻の処理を行い、再度休憩入の処理を行う
     * 3. 「休憩戻」ボタンが表示されることを確認する
     * 画面上に「休憩戻」ボタンが表示される
     */
    public function test_rest_end_can_be_done_multiple_times_per_day()
    {
        // 1. ステータスが出勤中であるユーザーにログインする
        $user = $this->createWorkingUser();
        $this->actingAs($user);

        // 2. 休憩入と休憩戻の処理を行い、再度休憩入の処理を行う
        $this->post('/attendance/rest'); // 1回目の休憩入
        $this->post('/attendance/rest'); // 1回目の休憩戻
        $this->post('/attendance/rest'); // 2回目の休憩入

        // 3. 「休憩戻」ボタンが表示されることを確認する
        $response = $this->get('/attendance');
        $response->assertSee('休憩戻', false);
    }

    /**
     * 休憩時刻が勤怠一覧画面で確認できる
     * 1. ステータスが勤務中のユーザーにログインする
     * 2. 休憩入と休憩戻の処理を行う
     * 3. 勤怠一覧画面から休憩の日付を確認する
     * 勤怠一覧画面に休憩時刻が正確に記録されている
     */
    public function test_rest_time_is_displayed_on_list_page()
    {
        // 1. ステータスが勤務中のユーザーにログインする
        $user = $this->createWorkingUser();
        $this->actingAs($user);

        // 2. 休憩入と休憩戻の処理を行う
        $this->post('/attendance/rest'); // 休憩入
        sleep(1); // 1秒待機して時間差を作る
        $this->post('/attendance/rest'); // 休憩戻

        // 3. 勤怠一覧画面から休憩の日付を確認する
        $response = $this->get('/attendance/list');

        // 勤怠一覧画面に休憩時刻が正確に記録されている（休憩時間が表示される）
        $response->assertSee('0:00', false); // 1秒程度の休憩時間
    }
}
