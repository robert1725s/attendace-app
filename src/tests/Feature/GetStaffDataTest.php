<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class GetStaffDataTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 管理者ユーザーが全一般ユーザーの「氏名」「メールアドレス」を確認できる
     * 1. 管理者でログインする
     * 2. スタッフ一覧ページを開く
     * 全ての一般ユーザーの氏名とメールアドレスが正しく表示されている
     */
    public function test_admin_can_view_all_staff_names_and_emails()
    {
        // 1. 管理者でログインする
        $this->loginAsAdmin();

        // 一般ユーザーを3人作成
        $user1 = $this->createVerifiedUser([
            'name' => '山田太郎',
            'email' => 'yamada@example.com',
        ]);
        $user2 = $this->createVerifiedUser([
            'name' => '佐藤花子',
            'email' => 'sato@example.com',
        ]);
        $user3 = $this->createVerifiedUser([
            'name' => '鈴木一郎',
            'email' => 'suzuki@example.com',
        ]);

        // 2. スタッフ一覧ページを開く
        $response = $this->get('/admin/staff/list');

        // 全ての一般ユーザーの氏名とメールアドレスが正しく表示されている
        $response->assertStatus(200);
        $response->assertSee('山田太郎', false);
        $response->assertSee('yamada@example.com', false);
        $response->assertSee('佐藤花子', false);
        $response->assertSee('sato@example.com', false);
        $response->assertSee('鈴木一郎', false);
        $response->assertSee('suzuki@example.com', false);
    }

    /**
     * ユーザーの勤怠情報が正しく表示される
     * 1. 管理者ユーザーでログインする
     * 2. 選択したユーザーの勤怠一覧ページを開く
     * 勤怠情報が正確に表示される
     */
    public function test_staff_attendance_information_displayed_correctly()
    {
        // 1. 管理者ユーザーでログインする
        $this->loginAsAdmin();

        // 一般ユーザーを作成
        $user = $this->createVerifiedUser(['name' => 'テストユーザー']);

        // 勤怠データを作成
        $today = now();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->format('Y-m-d'),
            'start_time' => $today->copy()->setTime(9, 0, 0),
            'end_time' => $today->copy()->setTime(18, 0, 0),
        ]);

        // 休憩データを作成
        Rest::create([
            'attendance_id' => $attendance->id,
            'start_time' => $today->copy()->setTime(12, 0, 0),
            'end_time' => $today->copy()->setTime(13, 0, 0),
        ]);

        // 2. 選択したユーザーの勤怠一覧ページを開く
        $response = $this->get('/admin/attendance/staff/' . $user->id);

        // 勤怠情報が正確に表示される
        $response->assertStatus(200);
        $response->assertSee('テストユーザー', false);
        $response->assertSee('09:00', false);
        $response->assertSee('18:00', false);
        $response->assertSee('1:00', false); // 休憩時間
        $response->assertSee('8:00', false); // 合計時間
    }

    /**
     * 「前月」を押下した時に表示月の前月の情報が表示される
     * 1. 管理者ユーザーにログインをする
     * 2. 勤怠一覧ページを開く
     * 3. 「前月」ボタンを押す
     * 前月の情報が表示されている
     */
    public function test_previous_month_button_displays_previous_month_data()
    {
        // 1. 管理者ユーザーにログインをする
        $this->loginAsAdmin();

        // 一般ユーザーを作成
        $user = $this->createVerifiedUser();

        // 現在の月
        $currentMonth = now();
        $prevMonth = $currentMonth->copy()->subMonth();

        // 前月の勤怠データを作成
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $prevMonth->format('Y-m-d'),
            'start_time' => $prevMonth->copy()->setTime(9, 0, 0),
            'end_time' => $prevMonth->copy()->setTime(18, 0, 0),
        ]);

        // 2. 勤怠一覧ページを開く（現在の月）
        $this->get('/admin/attendance/staff/' . $user->id);

        // 3. 「前月」ボタンを押す
        $response = $this->get('/admin/attendance/staff/' . $user->id . '?month=' . $prevMonth->format('Y-m'));

        // 前月の情報が表示されている
        $response->assertStatus(200);
        $response->assertSee($prevMonth->format('Y/m'), false);
        $response->assertSee('09:00', false);
        $response->assertSee('18:00', false);
    }

    /**
     * 「翌月」を押下した時に表示月の翌月の情報が表示される
     * 1. 管理者ユーザーにログインをする
     * 2. 勤怠一覧ページを開く
     * 3. 「翌月」ボタンを押す
     * 翌月の情報が表示されている
     */
    public function test_next_month_button_displays_next_month_data()
    {
        // 1. 管理者ユーザーにログインをする
        $this->loginAsAdmin();

        // 一般ユーザーを作成
        $user = $this->createVerifiedUser();

        // 現在の月
        $currentMonth = now();
        $nextMonth = $currentMonth->copy()->addMonth();

        // 翌月の勤怠データを作成
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $nextMonth->format('Y-m-d'),
            'start_time' => $nextMonth->copy()->setTime(10, 0, 0),
            'end_time' => $nextMonth->copy()->setTime(19, 0, 0),
        ]);

        // 2. 勤怠一覧ページを開く（現在の月）
        $this->get('/admin/attendance/staff/' . $user->id);

        // 3. 「翌月」ボタンを押す
        $response = $this->get('/admin/attendance/staff/' . $user->id . '?month=' . $nextMonth->format('Y-m'));

        // 翌月の情報が表示されている
        $response->assertStatus(200);
        $response->assertSee($nextMonth->format('Y/m'), false);
        $response->assertSee('10:00', false);
        $response->assertSee('19:00', false);
    }

    /**
     * 「詳細」を押下すると、その日の勤怠詳細画面に遷移する
     * 1. 管理者ユーザーにログインをする
     * 2. 勤怠一覧ページを開く
     * 3. 「詳細」ボタンを押下する
     * その日の勤怠詳細画面に遷移する
     */
    public function test_detail_button_navigates_to_attendance_detail_page()
    {
        // 1. 管理者ユーザーにログインをする
        $this->loginAsAdmin();

        // 一般ユーザーを作成
        $user = $this->createVerifiedUser();

        // 勤怠データを作成
        $today = now();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->format('Y-m-d'),
            'start_time' => $today->copy()->setTime(9, 0, 0),
            'end_time' => $today->copy()->setTime(18, 0, 0),
        ]);

        // 2. 勤怠一覧ページを開く
        $this->get('/admin/attendance/staff/' . $user->id);

        // 3. 「詳細」ボタンを押下する
        $response = $this->get('/admin/attendance/' . $attendance->id);

        // その日の勤怠詳細画面に遷移する
        $response->assertStatus(200);
        $response->assertSee('勤怠詳細', false);
        $response->assertSee($user->name, false);
        $response->assertSee('09:00', false);
        $response->assertSee('18:00', false);
    }
}
