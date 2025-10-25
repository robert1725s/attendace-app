<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectionAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'start_time',
        'end_time',
        'reason',
        'is_approved',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_approved' => 'boolean',
    ];

    /**
     * 元の勤怠データ
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * 修正申請の休憩データ
     */
    public function correctionRests()
    {
        return $this->hasMany(CorrectionRest::class);
    }
}
