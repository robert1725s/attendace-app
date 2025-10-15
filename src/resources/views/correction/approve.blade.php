@extends('layouts.app')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/correction/approve.css') }}">
@endsection

@section('content')
    <div class="approve">
        <div class="approve__container">
            <h1 class="approve__title">勤怠詳細</h1>

            <div class="approve__card">
                <div class="approve__row">
                    <div class="approve__label">名前</div>
                    <div class="approve__value">西　怜奈</div>
                </div>

                <div class="approve__row">
                    <div class="approve__label">日付</div>
                    <div class="approve__value">
                        <span class="approve__date-year">2023年</span>
                        <span class="approve__date-month">6月1日</span>
                    </div>
                </div>

                <div class="approve__row">
                    <div class="approve__label">出勤・退勤</div>
                    <div class="approve__value">
                        <span class="approve__time">09:00</span>
                        <span class="approve__separator">～</span>
                        <span class="approve__time">18:00</span>
                    </div>
                </div>

                <div class="approve__row">
                    <div class="approve__label">休憩</div>
                    <div class="approve__value">
                        <span class="approve__time">12:00</span>
                        <span class="approve__separator">～</span>
                        <span class="approve__time">13:00</span>
                    </div>
                </div>

                <div class="approve__row">
                    <div class="approve__label">休憩2</div>
                    <div class="approve__value"></div>
                </div>

                <div class="approve__row">
                    <div class="approve__label">備考</div>
                    <div class="approve__value">電車遅延のため</div>
                </div>
            </div>

            <div class="approve__actions">
                <button class="approve__button">承認</button>
            </div>
        </div>
    </div>
@endsection
