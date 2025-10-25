<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function showAttendance()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // 今日の勤怠レコードを取得
        $attendance = Attendance::where('user_id', $user->id)
            ->where('work_date', $today)
            ->first();

        return view('user.attendance.stamp', compact('attendance'));
    }

    public function stamp()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        $now = now();

        // 今日の勤怠レコードを取得
        $attendance = Attendance::where('user_id', $user->id)
            ->where('work_date', $today)
            ->first();

        if (!$attendance) {
            // 出勤処理
            Attendance::create([
                'user_id' => $user->id,
                'work_date' => $today,
                'start_time' => $now,
            ]);
        } elseif ($attendance->status === '出勤中' || $attendance->status === '休憩中') {
            // 退勤処理
            $attendance->update([
                'end_time' => $now,
            ]);
        }

        return redirect('/attendance');
    }

    public function rest()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');
        $now = now();

        // 今日の勤怠レコードを取得
        $attendance = Attendance::where('user_id', $user->id)
            ->where('work_date', $today)
            ->first();

        if (!$attendance) {
            return redirect('/attendance');
        }

        if ($attendance->status === '休憩中') {
            // 休憩終了
            $currentRest = $attendance->currentRest();
            $currentRest->update([
                'end_time' => $now,
            ]);
        } elseif ($attendance->status === '出勤中') {
            // 休憩開始
            Rest::create([
                'attendance_id' => $attendance->id,
                'start_time' => $now,
            ]);
        }

        return redirect('/attendance');
    }

    public function showList()
    {
        $user = Auth::user();

        // クエリパラメータから月を取得、なければ現在の月
        $month = request('month', now()->format('Y-m'));
        $currentMonth = \Carbon\Carbon::parse($month . '-01');

        // その月の全日付を取得
        $daysInMonth = $currentMonth->daysInMonth;
        $attendances = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $currentMonth->copy()->day($day);
            $attendance = Attendance::where('user_id', $user->id)
                ->where('work_date', $date->format('Y-m-d'))
                ->first();

            $attendances[] = [
                'date' => $date,
                'attendance' => $attendance,
            ];
        }

        // 前月と翌月
        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

        return view('user.attendance.index', compact('attendances', 'currentMonth', 'prevMonth', 'nextMonth'));
    }

    public function showDetail($id)
    {
        $user = Auth::user();

        if ($id === 'new') {
            // 新規作成の場合
            $date = request('date');
            $attendance = null;
        } else {
            // 既存データの場合
            $attendance = Attendance::findOrFail($id);

            // 自分の勤怠データかチェック
            if ($attendance->user_id !== Auth::id()) {
                abort(403);
            }

            $date = $attendance->work_date->format('Y-m-d');
        }

        return view('user.attendance.detail', compact('attendance', 'date'));
    }
}
