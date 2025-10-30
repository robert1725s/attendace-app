<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetailRequest;
use App\Models\Attendance;
use App\Models\CorrectionAttendance;
use App\Models\CorrectionRest;
use App\Models\Rest;
use Illuminate\Http\Request;
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

            // start_timeがnullなら、attendanceがないものとして扱う
            if ($attendance && $attendance->start_time === null) {
                $attendance = null;
            }

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
        $correctionAttendance = null;

        if ($id === 'new') {
            // 新規作成の場合
            $date = request('date');

            // 空のattendanceを検索
            $attendance = Attendance::where('user_id', $user->id)
                ->where('work_date', $date)
                ->whereNull('start_time')
                ->whereNull('end_time')
                ->first();

            $correctionAttendance = null;
            if ($attendance) {
                // 未承認のcorrection_attendanceをチェック
                $correctionAttendance = CorrectionAttendance::where('attendance_id', $attendance->id)
                    ->where('is_approved', false)
                    ->first();
            }
        } else {
            // 既存データの場合
            $attendance = Attendance::findOrFail($id);

            $date = $attendance->work_date->format('Y-m-d');

            // 未承認のcorrection_attendanceをチェック
            $correctionAttendance = CorrectionAttendance::where('attendance_id', $attendance->id)
                ->where('is_approved', false)
                ->first();
        }

        // 表示するデータを決定
        $displayData = $correctionAttendance ?? $attendance;

        // 日付オブジェクト
        $dateObj = \Carbon\Carbon::parse($date);

        // 休憩データを取得
        if ($correctionAttendance) {
            $rests = $correctionAttendance->correctionRests;
        } elseif ($attendance) {
            $rests = $attendance->rests;
        } else {
            $rests = collect();
        }

        return view('user.attendance.detail', compact('user', 'dateObj', 'displayData', 'rests', 'correctionAttendance', 'attendance'));
    }

    public function modify(DetailRequest $request, $id)
    {
        $user = Auth::user();

        // 既存データか新規作成かを判定
        if ($id === 'new') {
            $date = $request->input('date');

            // 申請と紐づけるために空のattendanceを作成
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'work_date' => $date,
                'start_time' => null,
                'end_time' => null,
            ]);


            $attendanceId = $attendance->id;
        } else {
            $attendance = Attendance::findOrFail($id);

            $attendanceId = $attendance->id;
            $date = $attendance->work_date->format('Y-m-d');
        }

        // correction_attendanceを作成
        $correctionAttendance = CorrectionAttendance::create([
            'attendance_id' => $attendanceId,
            'start_time' => $date . ' ' . $request->input('start_time'),
            'end_time' => $date . ' ' . $request->input('end_time'),
            'reason' => $request->input('reason'),
            'is_approved' => false,
        ]);

        // 休憩データの保存
        $rests = $request->input('rest', []);
        foreach ($rests as $rest) {
            if (!empty($rest['start']) && !empty($rest['end'])) {
                CorrectionRest::create([
                    'correction_attendance_id' => $correctionAttendance->id,
                    'start_time' => $date . ' ' . $rest['start'],
                    'end_time' => $date . ' ' . $rest['end'],
                ]);
            }
        }

        return redirect('/attendance/detail/' . $attendanceId)
            ->with('success', '修正申請を送信しました。');
    }

    public function showCorrection()
    {
        $user = Auth::user();
        $tab = request('tab', 'pending'); // デフォルトは未承認

        // 承認状態を判定
        $isApproved = $tab === 'approved';

        if ($user->is_admin) {
            // 管理者：全ての管理者でないユーザーの申請を取得
            $corrections = CorrectionAttendance::with(['attendance.user'])
                ->whereHas('attendance.user', function ($query) {
                    $query->where('is_admin', false);
                })
                ->where('is_approved', $isApproved)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // 一般ユーザー：自分の申請のみ取得
            $corrections = CorrectionAttendance::with(['attendance'])
                ->whereHas('attendance', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->where('is_approved', $isApproved)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('correction.index', compact('corrections', 'tab'));
    }
}
