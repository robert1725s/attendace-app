<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetailRequest;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * 管理者用勤怠一覧画面を表示
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showAttendanceList(Request $request)
    {
        // dateパラメータを取得、デフォルトは今日
        $date = $request->query('date', now()->format('Y-m-d'));
        $targetDate = \Carbon\Carbon::parse($date);

        // 前日と翌日の日付を計算
        $prevDate = $targetDate->copy()->subDay()->format('Y-m-d');
        $nextDate = $targetDate->copy()->addDay()->format('Y-m-d');

        // 一般ユーザーを取得
        $users = User::where('is_admin', false)->get();

        // 各ユーザーの指定日の勤怠データを取得
        $userAttendances = $users->map(function ($user) use ($targetDate) {
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('work_date', $targetDate->format('Y-m-d'))
                ->with('rests')
                ->first();

            return [
                'user' => $user,
                'attendance' => $attendance,
            ];
        });

        return view('admin.attendance.index', compact('userAttendances', 'targetDate', 'prevDate', 'nextDate'));
    }

    /**
     * 管理者用勤怠詳細画面を表示
     *
     * @param int|string $id
     * @return \Illuminate\View\View
     */
    public function showDetail($id)
    {
        if ($id === 'new') {
            // 新規の場合
            $date = request('date');
            $userId = request('user_id');
            $user = User::findOrFail($userId);

            // 新規勤怠データを作成（まだ保存しない）
            $attendance = new Attendance([
                'user_id' => $userId,
                'work_date' => $date,
            ]);
            $rests = collect([]);
        } else {
            // 既存の勤怠データを取得
            $attendance = Attendance::findOrFail($id);
            $user = $attendance->user;
            $date = $attendance->work_date->format('Y-m-d');
            $rests = $attendance->rests;
        }

        // 共通処理
        $dateObj = \Carbon\Carbon::parse($date);
        $displayData = $attendance;

        return view('admin.attendance.detail', compact('user', 'dateObj', 'displayData', 'rests', 'attendance'));
    }

    /**
     * 管理者が勤怠データを直接更新する
     *
     * @param DetailRequest $request
     * @param int|string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modify(DetailRequest $request, $id)
    {
        if ($id === 'new') {
            // 新規勤怠データを作成
            $date = request('date');
            $userId = request('user_id');
            $attendance = Attendance::create([
                'user_id' => $userId,
                'work_date' => $date,
                'start_time' => $date . ' ' . $request->input('start_time'),
                'end_time' => $date . ' ' . $request->input('end_time'),
            ]);
            $message = '勤怠データを作成しました。';
        } else {
            // 既存の勤怠データを更新
            $attendance = Attendance::findOrFail($id);
            $date = $attendance->work_date->format('Y-m-d');
            $attendance->update([
                'start_time' => $date . ' ' . $request->input('start_time'),
                'end_time' => $date . ' ' . $request->input('end_time'),
            ]);
            // 既存の休憩データを削除
            $attendance->rests()->delete();
            $message = '勤怠データを更新しました。';
        }

        // 休憩データを保存
        $rests = $request->input('rest', []);
        foreach ($rests as $rest) {
            if (!empty($rest['start']) && !empty($rest['end'])) {
                \App\Models\Rest::create([
                    'attendance_id' => $attendance->id,
                    'start_time' => $date . ' ' . $rest['start'],
                    'end_time' => $date . ' ' . $rest['end'],
                ]);
            }
        }

        return redirect('/admin/attendance/' . $attendance->id)->with('success', $message);
    }

    /**
     * 管理者用スタッフ一覧画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function showStaffList()
    {
        // 一般ユーザーを全て取得
        $staffs = User::where('is_admin', false)->get();

        return view('admin.staff.index', compact('staffs'));
    }

    /**
     * 管理者用スタッフ別勤怠一覧画面を表示
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showStaffAttendance($id)
    {
        // ユーザーを取得
        $user = User::findOrFail($id);

        // クエリパラメータから月を取得、なければ現在の月
        $month = request('month', now()->format('Y-m'));
        $currentMonth = \Carbon\Carbon::parse($month . '-01');

        // その月の全日付を取得
        $daysInMonth = $currentMonth->daysInMonth;
        $dateAttendances = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = $currentMonth->copy()->day($day);
            $attendance = Attendance::where('user_id', $user->id)
                ->where('work_date', $date->format('Y-m-d'))
                ->first();

            // start_timeがnullなら、attendanceがないものとして扱う
            if ($attendance && $attendance->start_time === null) {
                $attendance = null;
            }

            $dateAttendances[] = [
                'date' => $date,
                'attendance' => $attendance,
            ];
        }

        // 前月と翌月
        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');

        return view('admin.staff.attendance', compact('user', 'dateAttendances', 'currentMonth', 'prevMonth', 'nextMonth'));
    }

    /**
     * 管理者用スタッフ勤怠をCSV出力
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function outputAttendance($id)
    {
        $user = User::findOrFail($id);
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

            $dateAttendances[] = [
                'date' => $date,
                'attendance' => $attendance,
            ];
        }

        // CSVファイル名
        $filename = $user->name . '_' . $currentMonth->format('Y年m月') . '_勤怠一覧.csv';

        // CSVレスポンスを生成
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($dateAttendances) {
            $handle = fopen('php://output', 'w');

            // BOM付きUTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ヘッダー行
            fputcsv($handle, ['日付', '出勤', '退勤', '休憩', '合計']);

            // データ行
            foreach ($dateAttendances as $dateAttendance) {
                $date = $dateAttendance['date'];
                $attendance = $dateAttendance['attendance'];
                $dayOfWeek = $date->isoFormat('(ddd)');

                fputcsv($handle, [
                    $date->format('m/d') . $dayOfWeek,
                    $attendance ? $attendance->start_time->format('H:i') : '',
                    $attendance && $attendance->end_time ? $attendance->end_time->format('H:i') : '',
                    $attendance ? \App\Models\Attendance::formatMinutesToTime($attendance->total_rest_minutes) : '',
                    $attendance ? \App\Models\Attendance::formatMinutesToTime($attendance->total_work_minutes) : '',
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
