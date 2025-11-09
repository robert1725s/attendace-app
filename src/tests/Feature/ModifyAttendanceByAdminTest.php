<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\CorrectionAttendance;
use App\Models\CorrectionRest;
use App\Models\Rest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class ModifyAttendanceByAdminTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 承認待ちの修正申請が全て表示されている
     * 1. 管理者ユーザーにログインをする
     * 2. 修正申請一覧ページを開き、承認待ちのタブを開く
     * 全ユーザーの未承認の修正申請が表示される
     */
    public function test_all_pending_correction_requests_are_displayed()
    {
        // 1. 管理者ユーザーにログインをする
        $this->loginAsAdmin();

        // 一般ユーザーを2人作成
        $user1 = $this->createVerifiedUser(['name' => 'ユーザー1']);
        $user2 = $this->createVerifiedUser(['name' => 'ユーザー2']);

        // 各ユーザーの勤怠データを作成
        $attendance1 = Attendance::create([
            'user_id' => $user1->id,
            'work_date' => now()->format('Y-m-d'),
            'start_time' => now()->setTime(9, 0, 0),
            'end_time' => now()->setTime(18, 0, 0),
        ]);

        $attendance2 = Attendance::create([
            'user_id' => $user2->id,
            'work_date' => now()->format('Y-m-d'),
            'start_time' => now()->setTime(9, 0, 0),
            'end_time' => now()->setTime(18, 0, 0),
        ]);

        // 未承認の修正申請を作成
        CorrectionAttendance::create([
            'attendance_id' => $attendance1->id,
            'start_time' => now()->setTime(9, 30, 0),
            'end_time' => now()->setTime(18, 30, 0),
            'reason' => 'ユーザー1の修正理由',
            'is_approved' => false,
        ]);

        CorrectionAttendance::create([
            'attendance_id' => $attendance2->id,
            'start_time' => now()->setTime(10, 0, 0),
            'end_time' => now()->setTime(19, 0, 0),
            'reason' => 'ユーザー2の修正理由',
            'is_approved' => false,
        ]);

        // 2. 修正申請一覧ページを開き、承認待ちのタブを開く
        $response = $this->get('/stamp_correction_request/list');

        // 全ユーザーの未承認の修正申請が表示される
        $response->assertSee('ユーザー1', false);
        $response->assertSee('ユーザー1の修正理由', false);
        $response->assertSee('ユーザー2', false);
        $response->assertSee('ユーザー2の修正理由', false);
    }

    /**
     * 承認済みの修正申請が全て表示されている
     * 1. 管理者ユーザーにログインをする
     * 2. 修正申請一覧ページを開き、承認済みのタブを開く
     * 全ユーザーの承認済みの修正申請が表示される
     */
    public function test_all_approved_correction_requests_are_displayed()
    {
        // 1. 管理者ユーザーにログインをする
        $this->loginAsAdmin();

        // 一般ユーザーを2人作成
        $user1 = $this->createVerifiedUser(['name' => 'ユーザー1']);
        $user2 = $this->createVerifiedUser(['name' => 'ユーザー2']);

        // 各ユーザーの勤怠データを作成
        $attendance1 = Attendance::create([
            'user_id' => $user1->id,
            'work_date' => now()->format('Y-m-d'),
            'start_time' => now()->setTime(9, 0, 0),
            'end_time' => now()->setTime(18, 0, 0),
        ]);

        $attendance2 = Attendance::create([
            'user_id' => $user2->id,
            'work_date' => now()->format('Y-m-d'),
            'start_time' => now()->setTime(9, 0, 0),
            'end_time' => now()->setTime(18, 0, 0),
        ]);

        // 承認済みの修正申請を作成
        CorrectionAttendance::create([
            'attendance_id' => $attendance1->id,
            'start_time' => now()->setTime(9, 30, 0),
            'end_time' => now()->setTime(18, 30, 0),
            'reason' => 'ユーザー1の承認済み理由',
            'is_approved' => true,
        ]);

        CorrectionAttendance::create([
            'attendance_id' => $attendance2->id,
            'start_time' => now()->setTime(10, 0, 0),
            'end_time' => now()->setTime(19, 0, 0),
            'reason' => 'ユーザー2の承認済み理由',
            'is_approved' => true,
        ]);

        // 2. 修正申請一覧ページを開き、承認済みのタブを開く
        $response = $this->get('/stamp_correction_request/list?tab=approved');

        // 全ユーザーの承認済みの修正申請が表示される
        $response->assertSee('ユーザー1', false);
        $response->assertSee('ユーザー1の承認済み理由', false);
        $response->assertSee('ユーザー2', false);
        $response->assertSee('ユーザー2の承認済み理由', false);
    }

    /**
     * 修正申請の詳細内容が正しく表示されている
     * 1. 管理者ユーザーにログインをする
     * 2. 修正申請の詳細画面を開く
     * 申請内容が正しく表示されている
     */
    public function test_correction_request_details_are_displayed_correctly()
    {
        // 1. 管理者ユーザーにログインをする
        $this->loginAsAdmin();

        // 一般ユーザーを作成
        $user = $this->createVerifiedUser(['name' => 'テストユーザー']);

        // 勤怠データを作成
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->format('Y-m-d'),
            'start_time' => now()->setTime(9, 0, 0),
            'end_time' => now()->setTime(18, 0, 0),
        ]);

        // 修正申請を作成
        $correction = CorrectionAttendance::create([
            'attendance_id' => $attendance->id,
            'start_time' => now()->setTime(9, 30, 0),
            'end_time' => now()->setTime(18, 30, 0),
            'reason' => 'テスト修正理由',
            'is_approved' => false,
        ]);

        // 休憩データを作成
        CorrectionRest::create([
            'correction_attendance_id' => $correction->id,
            'start_time' => now()->setTime(12, 0, 0),
            'end_time' => now()->setTime(13, 0, 0),
        ]);

        // 2. 修正申請の詳細画面を開く
        $response = $this->get('/stamp_correction_request/approve/' . $correction->id);

        // 申請内容が正しく表示されている
        $response->assertSee('テストユーザー', false);
        $response->assertSee('09:30', false);
        $response->assertSee('18:30', false);
        $response->assertSee('12:00', false);
        $response->assertSee('13:00', false);
        $response->assertSee('テスト修正理由', false);
    }

    /**
     * 修正申請の承認処理が正しく行われる
     * 1. 管理者ユーザーにログインをする
     * 2. 修正申請の詳細画面で「承認」ボタンを押す
     * 修正申請が承認され、勤怠情報が更新される
     */
    public function test_correction_request_approval_process_works_correctly()
    {
        // 1. 管理者ユーザーにログインをする
        $this->loginAsAdmin();

        // 一般ユーザーを作成
        $user = $this->createVerifiedUser(['name' => 'テストユーザー']);

        // 勤怠データを作成
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->format('Y-m-d'),
            'start_time' => now()->setTime(9, 0, 0),
            'end_time' => now()->setTime(18, 0, 0),
        ]);

        // 元の休憩データを作成
        Rest::create([
            'attendance_id' => $attendance->id,
            'start_time' => now()->setTime(12, 0, 0),
            'end_time' => now()->setTime(13, 0, 0),
        ]);

        // 修正申請を作成
        $correction = CorrectionAttendance::create([
            'attendance_id' => $attendance->id,
            'start_time' => now()->setTime(9, 30, 0),
            'end_time' => now()->setTime(18, 30, 0),
            'reason' => 'テスト修正理由',
            'is_approved' => false,
        ]);

        // 修正申請の休憩データを作成
        CorrectionRest::create([
            'correction_attendance_id' => $correction->id,
            'start_time' => now()->setTime(12, 30, 0),
            'end_time' => now()->setTime(13, 30, 0),
        ]);

        // 2. 修正申請の詳細画面で「承認」ボタンを押す
        $response = $this->post('/stamp_correction_request/approve/' . $correction->id);

        // 修正申請が承認される
        $this->assertDatabaseHas('correction_attendances', [
            'id' => $correction->id,
            'is_approved' => true,
        ]);

        // 勤怠情報が更新される
        $attendance->refresh();
        $this->assertEquals('09:30:00', $attendance->start_time->format('H:i:s'));
        $this->assertEquals('18:30:00', $attendance->end_time->format('H:i:s'));

        // 休憩データが更新される
        $rest = $attendance->rests()->first();
        $this->assertEquals('12:30:00', $rest->start_time->format('H:i:s'));
        $this->assertEquals('13:30:00', $rest->end_time->format('H:i:s'));
    }
}
