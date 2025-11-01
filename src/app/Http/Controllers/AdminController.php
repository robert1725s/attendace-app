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
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showDetail($id)
    {
        // 勤怠データを取得
        $attendance = Attendance::findOrFail($id);
        $user = $attendance->user;

        $date = $attendance->work_date->format('Y-m-d');

        // 日付オブジェクト
        $dateObj = \Carbon\Carbon::parse($date);

        // 管理者は常に元の勤怠データを編集
        $displayData = $attendance;
        $rests = $attendance->rests;

        return view('admin.attendance.detail', compact('user', 'dateObj', 'displayData', 'rests', 'attendance'));
    }

    /**
     * 管理者が勤怠データを直接更新する
     *
     * @param DetailRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function modify(DetailRequest $request, $id)
    {
        // 勤怠データを取得
        $attendance = Attendance::findOrFail($id);
        $date = $attendance->work_date->format('Y-m-d');

        // 勤怠データを更新
        $attendance->update([
            'start_time' => $date . ' ' . $request->input('start_time'),
            'end_time' => $date . ' ' . $request->input('end_time'),
        ]);

        // 既存の休憩データを削除
        $attendance->rests()->delete();

        // 新しい休憩データを保存
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

        return redirect('/admin/attendance/' . $attendance->id)
            ->with('success', '勤怠データを更新しました。');
    }
}
