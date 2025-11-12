<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class GetAdminAttendanceListTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * その日になされた全ユーザーの勤怠情報が正確に確認できる
     * 1. 管理者ユーザーにログインする
     * 2. 勤怠一覧画面を開く
     * その日の全ユーザーの勤怠情報が正確な値になっている
     */
    public function test_all_users_attendance_displayed_correctly()
    {
        // 1. 管理者ユーザーにログインする
        $this->loginAsAdmin();

        // 一般ユーザーを作成
        $user1 = $this->createVerifiedUser(['name' => 'テストユーザー1', 'email' => 'user1@example.com']);
        $user2 = $this->createVerifiedUser(['name' => 'テストユーザー2', 'email' => 'user2@example.com']);

        $today = now();

        // ユーザー1の勤怠データを作成
        $attendance1 = Attendance::create([
            'user_id' => $user1->id,
            'work_date' => $today->format('Y-m-d'),
            'start_time' => $today->copy()->setTime(9, 0, 0),
            'end_time' => $today->copy()->setTime(18, 0, 0),
        ]);

        // 休憩時間を追加
        Rest::create([
            'attendance_id' => $attendance1->id,
            'start_time' => $today->copy()->setTime(12, 0, 0),
            'end_time' => $today->copy()->setTime(13, 0, 0),
        ]);

        // ユーザー2の勤怠データを作成
        $attendance2 = Attendance::create([
            'user_id' => $user2->id,
            'work_date' => $today->format('Y-m-d'),
            'start_time' => $today->copy()->setTime(10, 0, 0),
            'end_time' => $today->copy()->setTime(19, 0, 0),
        ]);

        // 休憩時間を追加
        Rest::create([
            'attendance_id' => $attendance2->id,
            'start_time' => $today->copy()->setTime(12, 0, 0),
            'end_time' => $today->copy()->setTime(13, 0, 0),
        ]);

        // 2. 勤怠一覧画面を開く
        $response = $this->get('/admin/attendances/list');

        // その日の全ユーザーの勤怠情報が正確な値になっている
        $response->assertSee('テストユーザー1', false);
        $response->assertSee('テストユーザー2', false);
        $response->assertSee('09:00', false);
        $response->assertSee('18:00', false);
        $response->assertSee('10:00', false);
        $response->assertSee('19:00', false);
        $response->assertSee('1:00', false); // 休憩時間
        $response->assertSee('8:00', false); // 勤務時間（9時間-1時間休憩）
        $response->assertSee('9:00', false); // 勤務時間（9時間）
    }

    /**
     * 遷移した際に現在の日付が表示される
     * 1. 管理者ユーザーにログインする
     * 2. 勤怠一覧画面を開く
     * 勤怠一覧画面にその日の日付が表示されている
     */
    public function test_current_date_displayed_on_page()
    {
        // 1. 管理者ユーザーにログインする
        $this->loginAsAdmin();

        // 2. 勤怠一覧画面を開く
        $response = $this->get('/admin/attendances/list');

        // 勤怠一覧画面にその日の日付が表示されている
        $today = now();
        $response->assertSee($today->format('Y年n月j日'), false);
        $response->assertSee($today->format('Y/m/d'), false);
    }

    /**
     * 「前日」を押下した時に前の日の勤怠情報が表示される
     * 1. 管理者ユーザーにログインする
     * 2. 勤怠一覧画面を開く
     * 3. 「前日」ボタンを押す
     * 前日の日付の勤怠情報が表示される
     */
    public function test_previous_day_attendance_displayed()
    {
        // 1. 管理者ユーザーにログインする
        $this->loginAsAdmin();

        // 一般ユーザーを作成
        $user = $this->createVerifiedUser(['name' => '前日ユーザー']);

        $today = now();
        $yesterday = $today->copy()->subDay();

        // 前日の勤怠データを作成
        Attendance::create([
            'user_id' => $user->id,
            'work_date' => $yesterday->format('Y-m-d'),
            'start_time' => $yesterday->copy()->setTime(9, 0, 0),
            'end_time' => $yesterday->copy()->setTime(17, 0, 0),
        ]);

        // 2. 勤怠一覧画面を開く（今日の日付で）
        $this->get('/admin/attendances/list');

        // 3. 「前日」ボタンを押す
        $response = $this->from('/admin/attendances/list')->get('/admin/attendances/list?date=' . $yesterday->format('Y-m-d'));

        // 前日の日付の勤怠情報が表示される
        $response->assertSee($yesterday->format('Y年n月j日'), false);
        $response->assertSee('前日ユーザー', false);
        $response->assertSee('09:00', false);
        $response->assertSee('17:00', false);
    }

    /**
     * 「翌日」を押下した時に次の日の勤怠情報が表示される
     * 1. 管理者ユーザーにログインする
     * 2. 勤怠一覧画面を開く
     * 3. 「翌日」ボタンを押す
     * 翌日の日付の勤怠情報が表示される
     */
    public function test_next_day_attendance_displayed()
    {
        // 1. 管理者ユーザーにログインする
        $this->loginAsAdmin();

        // 一般ユーザーを作成
        $user = $this->createVerifiedUser(['name' => '翌日ユーザー']);

        $today = now();
        $tomorrow = $today->copy()->addDay();

        // 翌日の勤怠データを作成
        Attendance::create([
            'user_id' => $user->id,
            'work_date' => $tomorrow->format('Y-m-d'),
            'start_time' => $tomorrow->copy()->setTime(8, 0, 0),
            'end_time' => $tomorrow->copy()->setTime(16, 0, 0),
        ]);

        // 2. 勤怠一覧画面を開く（今日の日付で）
        $this->get('/admin/attendances/list');

        // 3. 「翌日」ボタンを押す
        $response = $this->from('/admin/attendances/list')->get('/admin/attendances/list?date=' . $tomorrow->format('Y-m-d'));

        // 翌日の日付の勤怠情報が表示される
        $response->assertSee($tomorrow->format('Y年n月j日'), false);
        $response->assertSee('翌日ユーザー', false);
        $response->assertSee('08:00', false);
        $response->assertSee('16:00', false);
    }
}
