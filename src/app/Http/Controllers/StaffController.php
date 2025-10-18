<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
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
}
