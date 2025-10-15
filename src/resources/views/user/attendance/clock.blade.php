@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/attendance/clock.css') }}">
@endsection

@section('header-nav')
    <nav class="clock__nav">
        <a href="/attendance" class="clock__nav-link">勤怠</a>
        <a href="/attendance/list" class="clock__nav-link">勤怠一覧</a>
        <a href="/application" class="clock__nav-link">申請</a>
        <form action="/logout" method="POST" class="clock__nav-form">
            @csrf
            <button type="submit" class="clock__nav-button">ログアウト</button>
        </form>
    </nav>
@endsection

@section('content')
    <div class="clock">
        <div class="clock__container">
            <!-- ステータスバッジ -->
            <div class="clock__status">勤務外</div>

            <!-- 日付表示 -->
            <div class="clock__date">2023年6月1日(木)</div>

            <!-- 時刻表示 -->
            <div class="clock__time">08:00</div>

            <!-- 出勤ボタン -->
            <form action="/attendance/clock-in" method="POST" class="clock__form">
                @csrf
                <button type="submit" class="clock__button">出勤</button>
            </form>
        </div>
    </div>
@endsection
