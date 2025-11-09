@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/correction/approve.css') }}">
@endsection

@section('content')
    <div class="approve__container">
        <!-- タイトル -->
        <h1 class="approve__title">勤怠詳細</h1>

        <form action="/stamp_correction_request/approve/{{ $correction->id }}" method="POST">
            @csrf

            <!-- 詳細カード -->
            <div class="approve__card">
                <!-- 名前 -->
                <div class="approve__row">
                    <div class="approve__label">名前</div>
                    <div class="approve__value">
                        <span class="approve__name">
                            {{ $user->name }}
                        </span>
                    </div>
                </div>

                <!-- 日付 -->
                <div class="approve__row">
                    <div class="approve__label">日付</div>
                    <div class="approve__value">
                        <span class="approve__date-year">{{ $dateObj->format('Y年') }}</span>
                        <span class="approve__date-detail">{{ $dateObj->format('n月j日') }}</span>
                    </div>
                </div>

                <!-- 出勤・退勤 -->
                <div class="approve__row">
                    <div class="approve__label">出勤・退勤</div>
                    <div class="approve__value">
                        <div class="approve__time-range">
                            <div class="approve__time-box">
                                {{ $displayData?->start_time?->format('H:i') }}
                            </div>

                            <span class="approve__time-separator">〜</span>

                            <div class="approve__time-box">
                                {{ $displayData?->end_time?->format('H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 休憩 -->
                @for ($i = 0; $i < max(2, $rests->count()); $i++)
                    <div class="approve__row">
                        <div class="approve__label">休憩{{ $i === 0 ? '' : $i + 1 }}</div>
                        <div class="approve__value">
                            @if ($rests->get($i))
                                <div class="approve__time-range">
                                    <div class="approve__time-box">
                                        {{ $rests->get($i)->start_time?->format('H:i') }}
                                    </div>

                                    <span class="approve__time-separator">〜</span>

                                    <div class="approve__time-box">
                                        {{ $rests->get($i)->end_time?->format('H:i') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endfor

                <!-- 備考 -->
                <div class="approve__row">
                    <div class="approve__label">備考</div>
                    <div class="approve__value">
                        <div class="approve__note-box">{{ $correction->reason }}</div>
                    </div>
                </div>
            </div>

            <!-- 承認ボタン -->
            <div class="approve__button-wrapper">
                @if ($correction->is_approved)
                    <button type="button" class="approve__button approve__button--disabled" disabled>承認済み</button>
                @else
                    <button type="submit" class="approve__button">承認</button>
                @endif
            </div>
        </form>
    </div>
@endsection
