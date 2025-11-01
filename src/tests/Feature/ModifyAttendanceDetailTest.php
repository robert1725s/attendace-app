<?php

namespace Tests\Feature;

use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class ModifyAttendanceDetailTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 出勤時間が退勤時間より後になっている場合、エラーメッセージが表示される
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 出勤時間を退勤時間より後に設定する
     * 4. 保存処理をする
     * 「出勤時間もしくは退勤時間が不適切な値です」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_start_time_is_after_end_time()
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
        $this->get('/attendance/detail/' . $attendance->id);

        // 3. 出勤時間を退勤時間より後に設定する
        // 4. 保存処理をする
        $response = $this->from('/attendance/detail/' . $attendance->id)
            ->post('/attendance/detail/request/' . $attendance->id, [
                'start_time' => '18:00',
                'end_time' => '09:00',
                'reason' => '時間を修正します',
            ]);

        // 「出勤時間もしくは退勤時間が不適切な値です」というバリデーションメッセージが表示される
        $errors = session('errors');
        $this->assertTrue($errors->has('start_time'));
        $this->assertStringContainsString('出勤時間もしくは退勤時間が不適切な値です', $errors->first('start_time'));
    }

    /**
     * 休憩開始時間が退勤時間より後になっている場合、エラーメッセージが表示される
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 休憩開始時間を退勤時間より後に設定する
     * 4. 保存処理をする
     * 「休憩時間が不適切な値です」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_rest_start_is_after_end_time()
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
        $this->get('/attendance/detail/' . $attendance->id);

        // 3. 休憩開始時間を退勤時間より後に設定する
        // 4. 保存処理をする
        $response = $this->from('/attendance/detail/' . $attendance->id)
            ->post('/attendance/detail/request/' . $attendance->id, [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'rest' => [
                    ['start' => '19:00', 'end' => '20:00'],
                ],
                'reason' => '休憩時間を修正します',
            ]);

        // 「休憩時間が不適切な値です」というバリデーションメッセージが表示される
        $errors = session('errors');
        $this->assertTrue($errors->has('rest.0.start'));
        $this->assertStringContainsString('休憩時間が不適切な値です', $errors->first('rest.0.start'));
    }

    /**
     * 休憩終了時間が退勤時間より後になっている場合、エラーメッセージが表示される
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 休憩終了時間を退勤時間より後に設定する
     * 4. 保存処理をする
     * 「休憩時間もしくは退勤時間が不適切な値です」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_rest_end_is_after_end_time()
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
        $this->get('/attendance/detail/' . $attendance->id);

        // 3. 休憩終了時間を退勤時間より後に設定する
        // 4. 保存処理をする
        $response = $this->from('/attendance/detail/' . $attendance->id)
            ->post('/attendance/detail/request/' . $attendance->id, [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'rest' => [
                    ['start' => '12:00', 'end' => '19:00'],
                ],
                'reason' => '休憩時間を修正します',
            ]);

        // 「休憩時間もしくは退勤時間が不適切な値です」というバリデーションメッセージが表示される
        $errors = session('errors');
        $this->assertTrue($errors->has('rest.0.end'));
        $this->assertStringContainsString('休憩時間もしくは退勤時間が不適切な値です', $errors->first('rest.0.end'));
    }

    /**
     * 備考欄が未入力の場合のエラーメッセージが表示される
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細ページを開く
     * 3. 備考欄を未入力のまま保存処理をする
     * 「備考を記入してください」というバリデーションメッセージが表示される
     */
    public function test_error_displayed_when_reason_is_empty()
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
        $this->get('/attendance/detail/' . $attendance->id);

        // 3. 備考欄を未入力のまま保存処理をする
        $response = $this->from('/attendance/detail/' . $attendance->id)
            ->post('/attendance/detail/request/' . $attendance->id, [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'reason' => '',
            ]);

        // 「備考を記入してください」というバリデーションメッセージが表示される
        $errors = session('errors');
        $this->assertTrue($errors->has('reason'));
        $this->assertStringContainsString('備考を記入してください', $errors->first('reason'));
    }

    /**
     * 修正申請処理が実行される
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細を修正し保存処理をする
     * 3. 管理者ユーザーで承認画面と申請一覧画面を確認する
     * 修正申請が実行され、管理者の承認画面と申請一覧画面に表示される
     */
    public function test_correction_request_is_created_and_displayed_to_admin()
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

        // 2. 勤怠詳細を修正し保存処理をする
        $this->post('/attendance/detail/request/' . $attendance->id, [
            'start_time' => '09:30',
            'end_time' => '18:30',
            'reason' => '遅延のため',
        ]);

        // 3. 管理者ユーザーで承認画面と申請一覧画面を確認する
        $admin = $this->loginAsAdmin();


        // 修正申請が実行され、管理者の承認画面と申請一覧画面に表示される
        $response = $this->get('/stamp_correction_request/list');
        $response->assertSee($user->name, false);
        $response->assertSee('遅延のため', false);
        $response->assertSee('承認待ち', false);
    }

    /**
     * 「承認待ち」にログインユーザーが行った申請が全て表示されていること
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細を修正し保存処理をする
     * 3. 申請一覧画面を確認する
     * 申請一覧に自分の申請が全て表示されている
     */
    public function test_pending_corrections_show_all_user_requests()
    {
        // 1. 勤怠情報が登録されたユーザーにログインをする
        $user = $this->loginAsUser();

        $today = now();

        // 複数の勤怠データを作成
        $attendance1 = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->copy()->subDays(2)->format('Y-m-d'),
            'start_time' => $today->copy()->subDays(2)->setTime(9, 0, 0),
            'end_time' => $today->copy()->subDays(2)->setTime(18, 0, 0),
        ]);

        $attendance2 = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->copy()->subDays(1)->format('Y-m-d'),
            'start_time' => $today->copy()->subDays(1)->setTime(9, 0, 0),
            'end_time' => $today->copy()->subDays(1)->setTime(18, 0, 0),
        ]);

        // 2. 勤怠詳細を修正し保存処理をする
        $this->post('/attendance/detail/request/' . $attendance1->id, [
            'start_time' => '09:30',
            'end_time' => '18:30',
            'reason' => '遅延のため1',
        ]);

        $this->post('/attendance/detail/request/' . $attendance2->id, [
            'start_time' => '10:00',
            'end_time' => '19:00',
            'reason' => '遅延のため2',
        ]);

        // 3. 申請一覧画面を確認する
        $response = $this->get('/stamp_correction_request/list');

        // 申請一覧に自分の申請が全て表示されている
        $response->assertSee('遅延のため1', false);
        $response->assertSee('遅延のため2', false);
        $response->assertSee('承認待ち', false);
    }

    /**
     * 「承認済み」に管理者が承認した修正申請が全て表示されている
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細を修正し保存処理をする
     * 3. 申請一覧画面を開く
     * 4. 管理者が承認した修正申請が全て表示されていることを確認
     * 承認済みに管理者が承認した申請が全て表示されている
     */
    public function test_approved_corrections_show_all_approved_requests()
    {
        // 1. 勤怠情報が登録されたユーザーにログインをする
        $user = $this->loginAsUser();

        $today = now();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->format('Y-m-d'),
            'start_time' => $today->copy()->setTime(9, 0, 0),
            'end_time' => $today->copy()->setTime(18, 0, 0),
        ]);

        // 2. 勤怠詳細を修正し保存処理をする
        $this->post('/attendance/detail/request/' . $attendance->id, [
            'start_time' => '09:30',
            'end_time' => '18:30',
            'reason' => '遅延のため',
        ]);

        // 管理者が承認処理（is_approvedをtrueに更新）
        $correction = \App\Models\CorrectionAttendance::where('attendance_id', $attendance->id)->first();
        $correction->update(['is_approved' => true]);

        // 3. 申請一覧画面を開く（承認済みタブ）
        $response = $this->get('/stamp_correction_request/list?tab=approved');

        // 4. 管理者が承認した修正申請が全て表示されていることを確認
        $response->assertStatus(200);
        $response->assertSee('承認済み', false);
        $response->assertSee('遅延のため', false);
    }

    /**
     * 各申請の「詳細」を押下すると勤怠詳細画面に遷移する
     * 1. 勤怠情報が登録されたユーザーにログインをする
     * 2. 勤怠詳細を修正し保存処理をする
     * 3. 申請一覧画面を開く
     * 4. 「詳細」ボタンを押す
     * 勤怠詳細画面に遷移する
     */
    public function test_detail_button_redirects_to_attendance_detail_page()
    {
        // 1. 勤怠情報が登録されたユーザーにログインをする
        $user = $this->loginAsUser();

        $today = now();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => $today->format('Y-m-d'),
            'start_time' => $today->copy()->setTime(9, 0, 0),
            'end_time' => $today->copy()->setTime(18, 0, 0),
        ]);

        // 2. 勤怠詳細を修正し保存処理をする
        $this->post('/attendance/detail/request/' . $attendance->id, [
            'start_time' => '09:30',
            'end_time' => '18:30',
            'reason' => '遅延のため',
        ]);

        // 3. 申請一覧画面を開く
        $this->get('/stamp_correction_request/list');

        // 4. 「詳細」ボタンを押す
        $response = $this->from('/stamp_correction_request/list')
            ->get('/attendance/detail/' . $attendance->id);

        // 勤怠詳細画面に遷移する
        $response->assertSee('勤怠詳細', false);
        $response->assertSee($user->name, false);
    }
}
