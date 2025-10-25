<?php

namespace Tests\Feature;

use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class GetStaffAttendanceListTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 自分が行った勤怠情報が全て表示されている
     * 1. 勤怠情報が登録されたユーザーにログインする
     * 2. 勤怠一覧ページを開く
     * 3. 自分の勤怠情報がすべて表示されていることを確認する
     * 自分の勤怠情報が全て表示されている
     */
    public function test_all_user_attendance_records_are_displayed()
    {
        // 1. 勤怠情報が登録されたユーザーにログインする
        $user = $this->createVerifiedUser();
        $this->actingAs($user);

        // 今月の勤怠データを作成
        $today = now();
        Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->copy()->subDays(5)->format('Y-m-d'),
            'start_time' => $today->copy()->subDays(5)->setTime(9, 0, 0),
            'end_time' => $today->copy()->subDays(5)->setTime(18, 0, 0),
        ]);

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->copy()->subDays(3)->format('Y-m-d'),
            'start_time' => $today->copy()->subDays(3)->setTime(8, 30, 0),
            'end_time' => $today->copy()->subDays(3)->setTime(17, 30, 0),
        ]);

        // 2. 勤怠一覧ページを開く
        $response = $this->get('/attendance/list');

        // 3. 自分の勤怠情報がすべて表示されていることを確認する
        $response->assertSee('09:00', false);
        $response->assertSee('18:00', false);
        $response->assertSee('08:30', false);
        $response->assertSee('17:30', false);
    }

    /**
     * 勤怠一覧画面に遷移した際に現在の月が表示される
     * 1. ユーザーにログインをする
     * 2. 勤怠一覧ページを開く
     * 現在の月が表示されている
     */
    public function test_current_month_is_displayed_on_list_page()
    {
        // 1. ユーザーにログインをする
        $user = $this->createVerifiedUser();
        $this->actingAs($user);

        // 2. 勤怠一覧ページを開く
        $response = $this->get('/attendance/list');

        // 現在の月が表示されている
        $currentMonth = now()->format('Y/m');
        $response->assertSee($currentMonth, false);
    }

    /**
     * 「前月」を押下した時に表示月の前月の情報が表示される
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠一覧ページを開く
     * 3. 「前月」ボタンを押す
     * 前月の情報が表示されている
     */
    public function test_previous_month_is_displayed_when_clicking_prev_button()
    {
        // 1. 勤怠情報が登録されたユーザーにログインをする
        $user = $this->createVerifiedUser();
        $this->actingAs($user);

        // 前月の勤怠データを作成
        $prevMonthDate = now()->subMonth()->startOfMonth()->addDays(10);
        Attendance::create([
            'user_id' => $user->id,
            'work_date' => $prevMonthDate->format('Y-m-d'),
            'start_time' => $prevMonthDate->copy()->setTime(10, 0, 0),
            'end_time' => $prevMonthDate->copy()->setTime(19, 0, 0),
        ]);

        // 2. 勤怠一覧ページを開く
        $response = $this->get('/attendance/list');

        // 3. 「前月」ボタンを押す
        $prevMonth = now()->subMonth()->format('Y-m');
        $response = $this->get('/attendance/list?month=' . $prevMonth);

        // 前月の情報が表示されている
        $expectedMonth = now()->subMonth()->format('Y/m');
        $response->assertSee($expectedMonth, false);
        $response->assertSee('10:00', false);
        $response->assertSee('19:00', false);
    }

    /**
     * 「翌月」を押下した時に表示月の翌月の情報が表示される
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠一覧ページを開く
     * 3. 「翌月」ボタンを押す
     * 翌月の情報が表示されている
     */
    public function test_next_month_is_displayed_when_clicking_next_button()
    {
        // 1. 勤怠情報が登録されたユーザーにログインをする
        $user = $this->createVerifiedUser();
        $this->actingAs($user);

        // 翌月の勤怠データを作成
        $nextMonthDate = now()->addMonth()->startOfMonth()->addDays(5);
        Attendance::create([
            'user_id' => $user->id,
            'work_date' => $nextMonthDate->format('Y-m-d'),
            'start_time' => $nextMonthDate->copy()->setTime(8, 0, 0),
            'end_time' => $nextMonthDate->copy()->setTime(17, 0, 0),
        ]);

        // 2. 勤怠一覧ページを開く
        $response = $this->get('/attendance/list');

        // 3. 「翌月」ボタンを押す
        $nextMonth = now()->addMonth()->format('Y-m');
        $response = $this->get('/attendance/list?month=' . $nextMonth);

        // 翌月の情報が表示されている
        $expectedMonth = now()->addMonth()->format('Y/m');
        $response->assertSee($expectedMonth, false);
        $response->assertSee('08:00', false);
        $response->assertSee('17:00', false);
    }
}
