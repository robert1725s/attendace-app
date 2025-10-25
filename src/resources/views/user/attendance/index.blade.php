@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/attendance/index.css') }}">
@endsection

@section('content')
    <div class="index__container">
        <!-- タイトル -->
        <h1 class="index__title">勤怠一覧</h1>

        <!-- 月次ナビゲーション -->
        <div class="index__month-nav">
            <a href="{{ url('/attendance/list?month=' . $prevMonth) }}" class="index__month-link">
                <img src="{{ asset('images/arrow.png') }}" alt="前月" class="index__month-arrow index__month-arrow--prev">
                <span class="index__month-text">前月</span>
            </a>
            <div class="index__month-current">
                <img src="{{ asset('images/calendar.png') }}" alt="カレンダー" class="index__month-icon">
                <span class="index__month-label">{{ $currentMonth->format('Y/m') }}</span>
            </div>
            <a href="{{ url('/attendance/list?month=' . $nextMonth) }}" class="index__month-link">
                <span class="index__month-text">翌月</span>
                <img src="{{ asset('images/arrow.png') }}" alt="翌月" class="index__month-arrow index__month-arrow--next">
            </a>
        </div>

        <!-- 勤怠テーブル -->
        <div class="index__table-wrapper">
            <table class="index__table">
                <thead class="index__table-head">
                    <tr class="index__table-row">
                        <th class="index__table-header">日付</th>
                        <th class="index__table-header">出勤</th>
                        <th class="index__table-header">退勤</th>
                        <th class="index__table-header">休憩</th>
                        <th class="index__table-header">合計</th>
                        <th class="index__table-header">詳細</th>
                    </tr>
                </thead>
                <tbody class="index__table-body">
                    @foreach ($attendances as $item)
                        @php
                            $date = $item['date'];
                            $attendance = $item['attendance'];
                            $dayOfWeek = $date->isoFormat('(ddd)');
                        @endphp
                        <tr class="index__table-row">
                            <td class="index__table-cell">{{ $date->format('m/d') }}{{ $dayOfWeek }}</td>
                            <td class="index__table-cell">
                                {{ $attendance ? $attendance->start_time->format('H:i') : '' }}
                            </td>
                            <td class="index__table-cell">
                                {{ $attendance && $attendance->end_time ? $attendance->end_time->format('H:i') : '' }}
                            </td>
                            <td class="index__table-cell">
                                {{ $attendance ? \App\Models\Attendance::formatMinutesToTime($attendance->total_rest_minutes) : '' }}
                            </td>
                            <td class="index__table-cell">
                                {{ $attendance ? \App\Models\Attendance::formatMinutesToTime($attendance->total_work_minutes) : '' }}
                            </td>
                            <td class="index__table-cell">
                                @if ($attendance)
                                    <a href="{{ url('/attendance/detail/' . $attendance->id) }}" class="index__detail-link">詳細</a>
                                @else
                                    <a href="{{ url('/attendance/detail/new?date=' . $date->format('Y-m-d')) }}" class="index__detail-link">詳細</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
