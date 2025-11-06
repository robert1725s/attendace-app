@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/staff/attendance.css') }}">
@endsection

@section('content')
    <div class="attendance__container">
        <!-- タイトル -->
        <h1 class="attendance__title">{{ $user->name }}さんの勤怠</h1>

        <!-- 月次ナビゲーション -->
        <div class="attendance__month-nav">
            <a href="{{ url('/admin/attendance/staff/' . $user->id . '?month=' . $prevMonth) }}" class="attendance__month-link">
                <img src="{{ asset('images/arrow.png') }}" alt="前月" class="attendance__month-arrow attendance__month-arrow--prev">
                <span class="attendance__month-text">前月</span>
            </a>
            <div class="attendance__month-current">
                <img src="{{ asset('images/calendar.png') }}" alt="カレンダー" class="attendance__month-icon">
                <span class="attendance__month-label">{{ $currentMonth->format('Y/m') }}</span>
            </div>
            <a href="{{ url('/admin/attendance/staff/' . $user->id . '?month=' . $nextMonth) }}" class="attendance__month-link">
                <span class="attendance__month-text">翌月</span>
                <img src="{{ asset('images/arrow.png') }}" alt="翌月" class="attendance__month-arrow attendance__month-arrow--next">
            </a>
        </div>

        <!-- 勤怠テーブル -->
        <div class="attendance__table-wrapper">
            <table class="attendance__table">
                <thead class="attendance__table-head">
                    <tr class="attendance__table-row">
                        <th class="attendance__table-header">日付</th>
                        <th class="attendance__table-header">出勤</th>
                        <th class="attendance__table-header">退勤</th>
                        <th class="attendance__table-header">休憩</th>
                        <th class="attendance__table-header">合計</th>
                        <th class="attendance__table-header">詳細</th>
                    </tr>
                </thead>
                <tbody class="attendance__table-body">
                    @foreach ($dateAttendances as $dateAttendance)
                        @php
                            $date = $dateAttendance['date'];
                            $attendance = $dateAttendance['attendance'];
                            $dayOfWeek = $date->isoFormat('(ddd)');
                        @endphp
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">{{ $date->format('m/d') }}{{ $dayOfWeek }}</td>
                            <td class="attendance__table-cell">
                                {{ $attendance ? $attendance->start_time->format('H:i') : '' }}
                            </td>
                            <td class="attendance__table-cell">
                                {{ $attendance && $attendance->end_time ? $attendance->end_time->format('H:i') : '' }}
                            </td>
                            <td class="attendance__table-cell">
                                {{ $attendance ? \App\Models\Attendance::formatMinutesToTime($attendance->total_rest_minutes) : '' }}
                            </td>
                            <td class="attendance__table-cell">
                                {{ $attendance ? \App\Models\Attendance::formatMinutesToTime($attendance->total_work_minutes) : '' }}
                            </td>
                            <td class="attendance__table-cell">
                                @if ($attendance)
                                    <a href="{{ url('/admin/attendance/' . $attendance->id) }}" class="attendance__detail-link">詳細</a>
                                @else
                                    <a href="{{ url('/admin/attendance/new?date=' . $date->format('Y-m-d') . '&user_id=' . $user->id) }}" class="attendance__detail-link">詳細</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- CSV出力ボタン -->
        <form class="attendance__csv-form" action="{{ url('/admin/attendance/staff/output/' . $user->id) }}" method="POST">
            @csrf
            <input type="hidden" name="month" value="{{ $currentMonth->format('Y-m') }}">
            <button type="submit" class="attendance__csv-button">CSV出力</button>
        </form>
    </div>
@endsection
