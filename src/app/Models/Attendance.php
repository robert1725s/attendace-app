<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'work_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rests()
    {
        return $this->hasMany(Rest::class);
    }

    /**
     * 現在の休憩を取得
     */
    public function currentRest()
    {
        return $this->rests()->whereNull('end_time')->first();
    }

    /**
     * 現在のステータスを取得
     */
    public function getStatusAttribute()
    {
        if ($this->end_time) {
            return '退勤済';
        }

        if ($this->currentRest()) {
            return '休憩中';
        }

        return '出勤中';
    }

    /**
     * 休憩時間の合計を取得（分）
     */
    public function getTotalRestMinutesAttribute()
    {
        return $this->rests()
            ->whereNotNull('end_time')
            ->get()
            ->sum(function ($rest) {
                return $rest->start_time->diffInMinutes($rest->end_time);
            });
    }

    /**
     * 勤務時間の合計を取得（分）
     */
    public function getTotalWorkMinutesAttribute()
    {
        if (!$this->end_time) {
            return 0;
        }

        $totalMinutes = $this->start_time->diffInMinutes($this->end_time);
        return $totalMinutes - $this->total_rest_minutes;
    }

    /**
     * 時間を「H:MM」形式で表示
     */
    public static function formatMinutesToTime($minutes)
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%d:%02d', $hours, $mins);
    }
}
