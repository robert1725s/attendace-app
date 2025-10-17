@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/attendance/stamp.css') }}">
@endsection

@section('content')
    <div class="stamp">
        <div class="stamp__container">
            <!-- ステータスバッジ -->
            <div class="stamp__status">勤務外</div>

            <!-- 日付表示 -->
            <div class="stamp__date">2023年6月1日(木)</div>

            <!-- 時刻表示 -->
            <div class="stamp__time">08:00</div>

            <!-- 出勤ボタン -->
            <form action="/attendance/clock-in" method="POST" class="stamp__form">
                @csrf
                <button type="submit" class="stamp__button">出勤</button>
            </form>
        </div>
    </div>
@endsection
