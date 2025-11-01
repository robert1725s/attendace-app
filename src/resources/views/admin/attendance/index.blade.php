@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance/index.css') }}">
@endsection

@section('content')
    <div class="index__container">
        <!-- タイトル -->
        <h1 class="index__title">{{ $targetDate->format('Y年n月j日') }}の勤怠</h1>

        <!-- 日付ナビゲーション -->
        <div class="index__date-nav">
            <a href="{{ url('/admin/attendances/list?date=' . $prevDate) }}" class="index__date-link">
                <img src="{{ asset('images/arrow.png') }}" alt="前日" class="index__date-arrow index__date-arrow--prev">
                <span class="index__date-text">前日</span>
            </a>
            <div class="index__date-current">
                <img src="{{ asset('images/calendar.png') }}" alt="カレンダー" class="index__date-icon">
                <span class="index__date-label">{{ $targetDate->format('Y/m/d') }}</span>
            </div>
            <a href="{{ url('/admin/attendances/list?date=' . $nextDate) }}" class="index__date-link">
                <span class="index__date-text">翌日</span>
                <img src="{{ asset('images/arrow.png') }}" alt="翌日" class="index__date-arrow index__date-arrow--next">
            </a>
        </div>

        <!-- 勤怠テーブル -->
        <div class="index__table-wrapper">
            <table class="index__table">
                <thead class="index__table-head">
                    <tr class="index__table-row">
                        <th class="index__table-header">名前</th>
                        <th class="index__table-header">出勤</th>
                        <th class="index__table-header">退勤</th>
                        <th class="index__table-header">休憩</th>
                        <th class="index__table-header">合計</th>
                        <th class="index__table-header">詳細</th>
                    </tr>
                </thead>
                <tbody class="index__table-body">
                    @foreach($userAttendances as $userAttendance)
                        <tr class="index__table-row">
                            <td class="index__table-cell">{{ $userAttendance['user']->name }}</td>
                            <td class="index__table-cell">
                                {{ $userAttendance['attendance'] ? $userAttendance['attendance']->start_time->format('H:i') : '' }}
                            </td>
                            <td class="index__table-cell">
                                {{ $userAttendance['attendance'] && $userAttendance['attendance']->end_time ? $userAttendance['attendance']->end_time->format('H:i') : '' }}
                            </td>
                            <td class="index__table-cell">
                                {{ $userAttendance['attendance'] ? \App\Models\Attendance::formatMinutesToTime($userAttendance['attendance']->total_rest_minutes) : '' }}
                            </td>
                            <td class="index__table-cell">
                                {{ $userAttendance['attendance'] ? \App\Models\Attendance::formatMinutesToTime($userAttendance['attendance']->total_work_minutes) : '' }}
                            </td>
                            <td class="index__table-cell">
                                @if($userAttendance['attendance'])
                                    <a href="{{ url('/admin/attendance/' . $userAttendance['attendance']->id) }}" class="index__detail-link">詳細</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
