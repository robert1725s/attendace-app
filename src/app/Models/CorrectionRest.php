<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectionRest extends Model
{
    use HasFactory;

    protected $fillable = [
        'correction_attendance_id',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * 修正申請の勤怠データ
     */
    public function correctionAttendance()
    {
        return $this->belongsTo(CorrectionAttendance::class);
    }
}
