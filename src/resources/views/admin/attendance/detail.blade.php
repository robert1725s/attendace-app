@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance/detail.css') }}">
@endsection

@section('header-nav')
    <nav class="detail__nav">
        <a href="/admin/attendance" class="detail__nav-link">勤怠一覧</a>
        <a href="/admin/staff" class="detail__nav-link">スタッフ一覧</a>
        <a href="/admin/requests" class="detail__nav-link">申請一覧</a>
        <form action="/admin/logout" method="POST" class="detail__nav-form">
            @csrf
            <button type="submit" class="detail__nav-button">ログアウト</button>
        </form>
    </nav>
@endsection

@section('content')
    <div class="detail">
        <div class="detail__container">
            <!-- タイトル -->
            <h1 class="detail__title">勤怠詳細</h1>

            <!-- 詳細カード -->
            <div class="detail__card">
                <!-- 名前 -->
                <div class="detail__row">
                    <div class="detail__label">名前</div>
                    <div class="detail__value">西　怜奈</div>
                </div>

                <!-- 日付 -->
                <div class="detail__row">
                    <div class="detail__label">日付</div>
                    <div class="detail__value">
                        <span class="detail__date-year">2023年</span>
                        <span class="detail__date-detail">6月1日</span>
                    </div>
                </div>

                <!-- 出勤・退勤 -->
                <div class="detail__row">
                    <div class="detail__label">出勤・退勤</div>
                    <div class="detail__value">
                        <div class="detail__time-range">
                            <span class="detail__time-box">09:00</span>
                            <span class="detail__time-separator">〜</span>
                            <span class="detail__time-box">20:00</span>
                        </div>
                    </div>
                </div>

                <!-- 休憩 -->
                <div class="detail__row">
                    <div class="detail__label">休憩</div>
                    <div class="detail__value">
                        <div class="detail__time-range">
                            <span class="detail__time-box">12:00</span>
                            <span class="detail__time-separator">〜</span>
                            <span class="detail__time-box">13:00</span>
                        </div>
                    </div>
                </div>

                <!-- 休憩2 -->
                <div class="detail__row">
                    <div class="detail__label">休憩2</div>
                    <div class="detail__value">
                        <div class="detail__time-range">
                            <span class="detail__time-box detail__time-box--empty"></span>
                            <span class="detail__time-separator">〜</span>
                            <span class="detail__time-box detail__time-box--empty"></span>
                        </div>
                    </div>
                </div>

                <!-- 備考 -->
                <div class="detail__row detail__row--note">
                    <div class="detail__label">備考</div>
                    <div class="detail__value">
                        <div class="detail__note-box"></div>
                    </div>
                </div>
            </div>

            <!-- 修正ボタン -->
            <div class="detail__button-wrapper">
                <a href="/admin/attendance/edit/1" class="detail__button">修正</a>
            </div>
        </div>
    </div>
@endsection
