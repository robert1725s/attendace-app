<?php

namespace App\Http\Controllers;

use App\Models\CorrectionAttendance;
use Illuminate\Support\Facades\Auth;

class CorrectionController extends Controller
{
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
