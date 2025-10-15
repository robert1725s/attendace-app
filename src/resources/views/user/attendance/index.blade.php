@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/attendance/index.css') }}">
@endsection

@section('header-nav')
    <nav class="index__nav">
        <a href="/attendance" class="index__nav-link">勤怠</a>
        <a href="/attendance/list" class="index__nav-link">勤怠一覧</a>
        <a href="/application" class="index__nav-link">申請</a>
        <form action="/logout" method="POST" class="index__nav-form">
            @csrf
            <button type="submit" class="index__nav-button">ログアウト</button>
        </form>
    </nav>
@endsection

@section('content')
    <div class="index">
        <div class="index__container">
            <!-- タイトル -->
            <h1 class="index__title">勤怠一覧</h1>

            <!-- 月次ナビゲーション -->
            <div class="index__month-nav">
                <a href="{{ url('/attendance/list?month=2023-05') }}" class="index__month-link">
                    <span class="index__month-arrow">←</span>
                    <span class="index__month-text">前月</span>
                </a>
                <div class="index__month-current">
                    <span class="index__month-icon">📅</span>
                    <span class="index__month-label">2023/06</span>
                </div>
                <a href="{{ url('/attendance/list?month=2023-07') }}" class="index__month-link">
                    <span class="index__month-text">翌月</span>
                    <span class="index__month-arrow">→</span>
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
                        @for($day = 1; $day <= 30; $day++)
                            @php
                                $date = \Carbon\Carbon::create(2023, 6, $day);
                                $dayOfWeek = $date->isoFormat('(ddd)');
                                $isHoliday = $date->dayOfWeek === 0 || $date->dayOfWeek === 6;
                            @endphp
                            <tr class="index__table-row">
                                <td class="index__table-cell">{{ sprintf('06/%02d', $day) }}{{ $dayOfWeek }}</td>
                                @if($isHoliday && in_array($day, [4, 7, 17, 25]))
                                    <td class="index__table-cell"></td>
                                    <td class="index__table-cell"></td>
                                    <td class="index__table-cell"></td>
                                    <td class="index__table-cell"></td>
                                @else
                                    <td class="index__table-cell">09:00</td>
                                    <td class="index__table-cell">18:00</td>
                                    <td class="index__table-cell">1:00</td>
                                    <td class="index__table-cell">8:00</td>
                                @endif
                                <td class="index__table-cell">
                                    <a href="{{ url('/attendance/detail/' . $day) }}" class="index__detail-link">詳細</a>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
