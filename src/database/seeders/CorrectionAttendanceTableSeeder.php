<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\CorrectionAttendance;
use App\Models\CorrectionRest;
use Illuminate\Database\Seeder;

class CorrectionAttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 認証済み一般ユーザーのID
        $verifiedUserIds = [1, 4, 5, 6];

        foreach ($verifiedUserIds as $userId) {
            // ユーザーの勤怠データを取得（ランダムに2件）
            $attendances = Attendance::where('user_id', $userId)
                ->inRandomOrder()
                ->limit(2)
                ->get();

            if ($attendances->count() < 2) {
                continue;
            }

            // 1件目: 未承認の修正申請
            $this->createCorrectionWithRests($attendances[0], false);

            // 2件目: 承認済みの修正申請
            $this->createCorrectionWithRests($attendances[1], true);
        }
    }

    /**
     * 修正申請と休憩データを作成
     */
    private function createCorrectionWithRests(Attendance $attendance, bool $isApproved)
    {
        // 元の出勤・退勤時刻を少し変更
        $originalStartTime = $attendance->start_time;
        $originalEndTime = $attendance->end_time;

        // 出勤時刻を-30分～+30分変更
        $newStartTime = $originalStartTime->copy()->addMinutes(rand(-30, 30));

        // 退勤時刻を-30分～+30分変更
        $newEndTime = $originalEndTime->copy()->addMinutes(rand(-30, 30));

        // 修正申請を作成
        $correction = CorrectionAttendance::create([
            'attendance_id' => $attendance->id,
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
            'reason' => '修正をお願いします。',
            'is_approved' => $isApproved,
        ]);

        // 休憩データを作成（1～2件）
        $restCount = rand(1, 2);
        $currentRestStart = $newStartTime->copy()->addMinutes(rand(60, 180));

        for ($i = 0; $i < $restCount; $i++) {
            $restDuration = $i === 0 ? 60 : rand(30, 59); // 1回目は1時間、2回目は30-59分

            $restStartTime = $currentRestStart->copy();
            $restEndTime = $restStartTime->copy()->addMinutes($restDuration);

            CorrectionRest::create([
                'correction_attendance_id' => $correction->id,
                'start_time' => $restStartTime,
                'end_time' => $restEndTime,
            ]);

            // 次の休憩は前の休憩終了後、30-120分後
            $currentRestStart = $restEndTime->copy()->addMinutes(rand(30, 120));
        }
    }
}
