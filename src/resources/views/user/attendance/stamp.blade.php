@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/attendance/stamp.css') }}">
@endsection

@section('content')
    <div class="stamp__container">
        <!-- ステータス -->
        <div class="stamp__status">
            {{ $attendance ? $attendance->status : '勤務外' }}
        </div>

        <!-- 日付表示 -->
        <div class="stamp__date">{{ now()->locale('ja')->translatedFormat('Y年n月j日(D)') }}</div>

        <!-- 時刻表示 -->
        <div class="stamp__time">{{ now()->format('H:i') }}</div>

        @if (!$attendance || $attendance->status === '勤務外')
            <!-- 出勤ボタン -->
            <form action="/attendance/stamp" method="POST" class="stamp__form">
                @csrf
                <button type="submit" class="stamp__button">出勤</button>
            </form>
        @elseif($attendance->status === '退勤済')
            <!-- 退勤済メッセージ -->
            <p class="stamp__message">お疲れ様でした。</p>
        @elseif($attendance->status === '休憩中')
            <!-- 休憩戻ボタン -->
            <form action="/attendance/rest" method="POST" class="stamp__form">
                @csrf
                <button type="submit" class="stamp__button stamp__button--rest">休憩戻</button>
            </form>
        @elseif($attendance->status === '出勤中')
            <!-- 退勤ボタンと休憩入ボタン -->
            <div class="stamp__buttons">
                <form action="/attendance/stamp" method="POST" class="stamp__form">
                    @csrf
                    <button type="submit" class="stamp__button stamp__button--primary">退勤</button>
                </form>
                <form action="/attendance/rest" method="POST" class="stamp__form">
                    @csrf
                    <button type="submit" class="stamp__button stamp__button--rest">休憩入</button>
                </form>
            </div>
        @endif
    </div>

    <script>
        // 時刻のリアルタイム更新
        function updateDateTime() {
            const now = new Date();

            // 日付の更新
            const year = now.getFullYear();
            const month = now.getMonth() + 1;
            const day = now.getDate();
            const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
            const weekday = weekdays[now.getDay()];

            document.querySelector('.stamp__date').textContent =
                `${year}年${month}月${day}日(${weekday})`;

            // 時刻の更新
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');

            document.querySelector('.stamp__time').textContent =
                `${hours}:${minutes}`;
        }

        // 初回実行
        updateDateTime();

        // 1秒ごとに更新
        setInterval(updateDateTime, 1000);
    </script>
@endsection
