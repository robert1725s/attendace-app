<?php

namespace Tests\Feature;

use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class GetAdminAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 勤怠詳細画面に表示されるデータが選択したものになっている
     * 1. 管理者ユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 詳細画面の内容が選択した情報と一致する
     */
    public function test_attendance_detail_displays_correct_data()
    {
        // 1. 管理者ユーザーにログインをする
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

        // 2. 勤怠詳細ページを開く
        $response = $this->get('/admin/attendance/' . $attendance->id);

        // 詳細画面の内容が選択した情報と一致する
        $response->assertSee('勤怠詳細', false);
        $response->assertSee('テストユーザー', false);
        $response->assertSee($today->format('Y年'), false);
        $response->assertSee($today->format('n月j日'), false);
        $response->assertSee('09:00', false);
        $response->assertSee('18:00', false);
    }

    /**
     * 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
     * 1. 管理者ユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 出勤時間を退勤時間より後に設定する
     * 4. 保存処理をする
     * 「出勤時間もしくは退勤時間が不適切な値です」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_start_time_is_after_end_time()
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

        // 2. 勤怠詳細ページを開く
        $response = $this->get('/admin/attendance/' . $attendance->id);

        // 3. 出勤時間を退勤時間より後に設定する
        // 4. 保存処理をする
        $response = $this->from('/admin/attendance/' . $attendance->id)
            ->post('/admin/attendance/modify/' . $attendance->id, [
                'start_time' => '18:00',
                'end_time' => '09:00',
                'reason' => '時間を修正します',
            ]);

        // 「出勤時間もしくは退勤時間が不適切な値です」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'start_time' => '出勤時間もしくは退勤時間が不適切な値です'
        ]);
    }

    /**
     * 休憩開始時間が退勤時間より後になっている場合、エラーメッセージが表示される
     * 1. 管理者ユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 休憩開始時間を退勤時間より後に設定する
     * 4. 保存処理をする
     * 「休憩時間が不適切な値です」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_rest_start_is_after_end_time()
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

        // 2. 勤怠詳細ページを開く
        $response = $this->get('/admin/attendance/' . $attendance->id);

        // 3. 休憩開始時間を退勤時間より後に設定する
        // 4. 保存処理をする
        $response = $this->from('/admin/attendance/' . $attendance->id)
            ->post('/admin/attendance/modify/' . $attendance->id, [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'rest' => [
                    ['start' => '19:00', 'end' => '20:00'],
                ],
                'reason' => '休憩時間を修正します',
            ]);

        // 「休憩時間が不適切な値です」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'rest.0.start' => '休憩時間が不適切な値です'
        ]);
    }

    /**
     * 休憩終了時間が退勤時間より後になっている場合、エラーメッセージが表示される
     * 1. 管理者ユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 休憩終了時間を退勤時間より後に設定する
     * 4. 保存処理をする
     * 「休憩時間もしくは退勤時間が不適切な値です」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_rest_end_is_after_end_time()
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

        // 2. 勤怠詳細ページを開く
        $response = $this->get('/admin/attendance/' . $attendance->id);

        // 3. 休憩終了時間を退勤時間より後に設定する
        // 4. 保存処理をする
        $response = $this->from('/admin/attendance/' . $attendance->id)
            ->post('/admin/attendance/modify/' . $attendance->id, [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'rest' => [
                    ['start' => '12:00', 'end' => '19:00'],
                ],
                'reason' => '休憩時間を修正します',
            ]);

        // 「休憩時間もしくは退勤時間が不適切な値です」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'rest.0.end' => '休憩時間もしくは退勤時間が不適切な値です'
        ]);
    }

    /**
     * 備考欄が未入力の場合のエラーメッセージが表示される
     * 1. 管理者ユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 備考欄を未入力のまま保存処理をする
     * 「備考を記入してください」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_reason_is_empty()
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

        // 2. 勤怠詳細ページを開く
        $response = $this->get('/admin/attendance/' . $attendance->id);

        // 3. 備考欄を未入力のまま保存処理をする
        $response = $this->from('/admin/attendance/' . $attendance->id)
            ->post('/admin/attendance/modify/' . $attendance->id, [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'reason' => '',
            ]);

        // 「備考を記入してください」というバリデーションメッセージが表示される
        $response->assertSessionHasErrors([
            'reason' => '備考を記入してください'
        ]);
    }
}
