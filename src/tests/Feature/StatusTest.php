<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 勤務外の場合、勤怠ステータスが正しく表示される
     * 1. ステータスが勤務外のユーザーにログインする
     * 2. 勤怠打刻画面を開く
     * 3. 画面に表示されているステータスを確認する
     * 画面上に表示されているステータスが「勤務外」となる
     */
    public function test_status_is_displayed_as_off_work()
    {
        // 1. ステータスが勤務外のユーザーにログインする
        $user = $this->createVerifiedUser();
        $this->actingAs($user);

        // 2. 勤怠打刻画面を開く
        $response = $this->get('/attendance');

        // 3. 画面に表示されているステータスを確認する
        $response->assertSee('勤務外', false);
    }

    /**
     * 出勤中の場合、勤怠ステータスが正しく表示される
     * 1. ステータスが出勤中のユーザーにログインする
     * 2. 勤怠打刻画面を開く
     * 3. 画面に表示されているステータスを確認する
     * 画面上に表示されているステータスが「出勤中」となる
     */
    public function test_status_is_displayed_as_working()
    {
        // 1. ステータスが出勤中のユーザーにログインする
        $user = $this->createWorkingUser();
        $this->actingAs($user);

        // 2. 勤怠打刻画面を開く
        $response = $this->get('/attendance');

        // 3. 画面に表示されているステータスを確認する
        $response->assertSee('出勤中', false);
    }

    /**
     * 休憩中の場合、勤怠ステータスが正しく表示される
     * 1. ステータスが休憩中のユーザーにログインする
     * 2. 勤怠打刻画面を開く
     * 3. 画面に表示されているステータスを確認する
     * 画面上に表示されているステータスが「休憩中」となる
     */
    public function test_status_is_displayed_as_on_rest()
    {
        // 1. ステータスが休憩中のユーザーにログインする
        $user = $this->createOnRestUser();
        $this->actingAs($user);

        // 2. 勤怠打刻画面を開く
        $response = $this->get('/attendance');

        // 3. 画面に表示されているステータスを確認する
        $response->assertSee('休憩中', false);
    }

    /**
     * 退勤済の場合、勤怠ステータスが正しく表示される
     * 1. ステータスが退勤済のユーザーにログインする
     * 2. 勤怠打刻画面を開く
     * 3. 画面に表示されているステータスを確認する
     * 画面上に表示されているステータスが「退勤済」となる
     */
    public function test_status_is_displayed_as_finished_work()
    {
        // 1. ステータスが退勤済のユーザーにログインする
        $user = $this->createFinishedWorkUser();
        $this->actingAs($user);

        // 2. 勤怠打刻画面を開く
        $response = $this->get('/attendance');

        // 3. 画面に表示されているステータスを確認する
        $response->assertSee('退勤済', false);
    }
}
