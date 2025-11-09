<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 認証済み一般ユーザー（ID: 1, 4, 5, 6）と未認証ユーザー（ID: 2）に対して処理
        $userIds = [1, 2, 4, 5, 6];

        foreach ($userIds as $userId) {
            // 昨日から過去3ヶ月分（90日）
            for ($i = 1; $i <= 90; $i++) {
                $workDate = Carbon::yesterday()->subDays($i - 1);

                // 土日はスキップ
                if ($workDate->isWeekend()) {
                    continue;
                }

                // 開始時間: 8:00-10:00のランダム
                $startHour = rand(8, 9);
                $startMinute = rand(0, 59);
                $startTime = $workDate->copy()->setTime($startHour, $startMinute, 0);

                // 休憩回数: 1-2回
                $restCount = rand(1, 2);
                $totalRestMinutes = 0;
                $rests = [];

                // 1回目の休憩は必ず1時間
                $rests[] = 60;
                $totalRestMinutes += 60;

                // 2回目の休憩がある場合は1時間以内
                if ($restCount === 2) {
                    $secondRestDuration = rand(30, 59); // 30-59分
                    $rests[] = $secondRestDuration;
                    $totalRestMinutes += $secondRestDuration;
                }

                // 実働8-10時間になるように終了時間を計算
                // 勤務時間 = 実働時間 + 休憩時間
                $workMinutes = rand(480, 600); // 8時間(480分)～10時間(600分)
                $totalWorkMinutes = $workMinutes + $totalRestMinutes;
                $endTime = $startTime->copy()->addMinutes($totalWorkMinutes);

                // 勤怠レコードを作成
                $attendance = Attendance::create([
                    'user_id' => $userId,
                    'work_date' => $workDate->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);

                // 休憩レコードを作成
                $currentRestStart = $startTime->copy()->addMinutes(rand(60, 180)); // 出勤後1-3時間後に最初の休憩

                foreach ($rests as $restDuration) {
                    $restStartTime = $currentRestStart->copy();
                    $restEndTime = $restStartTime->copy()->addMinutes($restDuration);

                    Rest::create([
                        'attendance_id' => $attendance->id,
                        'start_time' => $restStartTime,
                        'end_time' => $restEndTime,
                    ]);

                    // 次の休憩は前の休憩終了後、30-120分後
                    $currentRestStart = $restEndTime->copy()->addMinutes(rand(30, 120));
                }
            }
        }
    }
}
