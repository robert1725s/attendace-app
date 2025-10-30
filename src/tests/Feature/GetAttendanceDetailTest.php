<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class GetAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 勤怠詳細画面の「名前」がログインユーザーの氏名になっている
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 名前欄を確認する
     * 名前がログインユーザーの名前になっている
     */
    public function test_name_matches_logged_in_user()
    {
        // 1. 勤怠情報が登録されたユーザーにログインをする
        $user = $this->loginAsUser();

        // 勤怠データを作成
        $today = now();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->format('Y-m-d'),
            'start_time' => $today->copy()->setTime(9, 0, 0),
            'end_time' => $today->copy()->setTime(18, 0, 0),
        ]);

        // 2. 勤怠詳細ページを開く
        $response = $this->from('/attendance/list')
            ->get('/attendance/detail/' . $attendance->id);

        // 3. 名前欄を確認する
        $response->assertSee($user->name, false);
    }

    /**
     * 勤怠詳細画面の「日付」が選択した日付になっている
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 日付欄を確認する
     * 日付が選択した日付になっている
     */
    public function test_date_matches_selected_date()
    {
        // 1. 勤怠情報が登録されたユーザーにログインをする
        $user = $this->loginAsUser();

        // 勤怠データを作成
        $today = now();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->format('Y-m-d'),
            'start_time' => $today->copy()->setTime(9, 0, 0),
            'end_time' => $today->copy()->setTime(18, 0, 0),
        ]);

        // 2. 勤怠詳細ページを開く
        $response = $this->get('/attendance/detail/' . $attendance->id);

        // 3. 日付欄を確認する
        $response->assertSee($today->format('Y年'), false);
        $response->assertSee($today->format('n月j日'), false);
    }

    /**
     * 「出勤・退勤」にて記されている時間がログインユーザーの打刻と一致している
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 出勤・退勤欄を確認する
     * 「出勤・退勤」にて記されている時間がログインユーザーの打刻と一致している
     */
    public function test_work_times_match_user_stamps()
    {
        // 1. 勤怠情報が登録されたユーザーにログインをする
        $user = $this->loginAsUser();

        // 勤怠データを作成
        $today = now();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->format('Y-m-d'),
            'start_time' => $today->copy()->setTime(9, 30, 0),
            'end_time' => $today->copy()->setTime(18, 45, 0),
        ]);

        // 2. 勤怠詳細ページを開く
        $response = $this->get('/attendance/detail/' . $attendance->id);

        // 3. 出勤・退勤欄を確認する
        $response->assertSee('09:30', false);
        $response->assertSee('18:45', false);
    }

    /**
     * 「休憩」にて記されている時間がログインユーザーの打刻と一致している
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 休憩欄を確認する
     * 「休憩」にて記されている時間がログインユーザーの打刻と一致している
     */
    public function test_rest_times_match_user_stamps()
    {
        // 1. 勤怠情報が登録されたユーザーにログインをする
        $user = $this->loginAsUser();

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

        Rest::create([
            'attendance_id' => $attendance->id,
            'start_time' => $today->copy()->setTime(15, 0, 0),
            'end_time' => $today->copy()->setTime(15, 15, 0),
        ]);

        // 2. 勤怠詳細ページを開く
        $response = $this->get('/attendance/detail/' . $attendance->id);

        // 3. 休憩欄を確認する
        $response->assertSee('12:00', false);
        $response->assertSee('13:00', false);
        $response->assertSee('15:00', false);
        $response->assertSee('15:15', false);
    }
}
