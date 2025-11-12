<?php

namespace App\Http\Controllers;

use App\Models\CorrectionAttendance;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CorrectionController extends Controller
{
    public function showCorrection()
    {
        $user = Auth::user();
        $tab = request('tab', 'pending'); // デフォルトは未承認

        // 承認状態を判定
        $isApproved = $tab === 'approved';

        if ($user->is_admin) {
            // 管理者：一般ユーザーの申請を取得
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

    public function showApprove($id)
    {
        // 修正申請を取得
        $correction = CorrectionAttendance::with(['attendance.user', 'correctionRests'])
            ->findOrFail($id);

        $user = $correction->attendance->user;
        $dateObj = $correction->attendance->work_date;
        $displayData = $correction;
        $rests = $correction->correctionRests;

        return view('correction.approve', compact('user', 'dateObj', 'displayData', 'rests', 'correction'));
    }

    public function approve($id)
    {
        DB::transaction(function () use ($id) {
            // 修正申請を取得
            $correction = CorrectionAttendance::with(['attendance', 'correctionRests'])
                ->findOrFail($id);

            // 修正申請を承認済みにする
            $correction->update(['is_approved' => true]);

            // 元の勤怠データを更新
            $attendance = $correction->attendance;
            $attendance->update([
                'start_time' => $correction->start_time,
                'end_time' => $correction->end_time,
            ]);

            // 元の休憩データを削除
            $attendance->rests()->delete();

            // 修正申請の休憩データを元の勤怠に追加
            foreach ($correction->correctionRests as $correctionRest) {
                Rest::create([
                    'attendance_id' => $attendance->id,
                    'start_time' => $correctionRest->start_time,
                    'end_time' => $correctionRest->end_time,
                ]);
            }
        });

        return redirect('/stamp_correction_request/approve/' . $id)->with('success', '修正申請を承認しました。');
    }
}
