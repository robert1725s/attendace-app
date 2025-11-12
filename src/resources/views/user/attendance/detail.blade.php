@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/attendance/detail.css') }}">
@endsection

@section('content')
    <div class="detail__container">
        <!-- タイトル -->
        <h1 class="detail__title">勤怠詳細</h1>

        <!-- 成功メッセージ -->
        @if (session('success'))
            <div class="detail__success">
                {{ session('success') }}
            </div>
        @endif

        <form action="/attendance/detail/request/{{ $attendance?->id ?? 'new' }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $dateObj->format('Y-m-d') }}">

            <!-- 詳細カード -->
            <div class="detail__card">
                <!-- 名前 -->
                <div class="detail__row">
                    <div class="detail__label">名前</div>
                    <div class="detail__value">
                        <span class="detail__name">
                            {{ $user->name }}
                        </span>
                    </div>
                </div>

                <!-- 日付 -->
                <div class="detail__row">
                    <div class="detail__label">日付</div>
                    <div class="detail__value">
                        <span class="detail__date-year">{{ $dateObj->format('Y年') }}</span>
                        <span class="detail__date-detail">{{ $dateObj->format('n月j日') }}</span>
                    </div>
                </div>

                <!-- 出勤・退勤 -->
                <div class="detail__row">
                    <div class="detail__label">出勤・退勤</div>
                    <div class="detail__value">
                        <div class="detail__time-range">
                            @if ($correctionAttendance)
                                <div class="detail__time-box">
                                    {{ $displayData?->start_time?->format('H:i') }}
                                </div>
                            @else
                                <input type="time"
                                    name="start_time"
                                    value="{{ old('start_time', $displayData?->start_time?->format('H:i')) }}"
                                    class="detail__time-input {{ old('start_time', $displayData?->start_time) ? 'has-value' : 'is-empty' }}">
                            @endif

                            <span class="detail__time-separator">〜</span>

                            @if ($correctionAttendance)
                                <div class="detail__time-box">
                                    {{ $displayData?->end_time?->format('H:i') }}
                                </div>
                            @else
                                <input type="time"
                                    name="end_time"
                                    value="{{ old('end_time', $displayData?->end_time?->format('H:i')) }}"
                                    class="detail__time-input {{ old('end_time', $displayData?->end_time) ? 'has-value' : 'is-empty' }}">
                            @endif
                        </div>
                        @error('start_time')
                            <div class="detail__error">{{ $message }}</div>
                        @enderror
                        @error('end_time')
                            <div class="detail__error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 休憩 -->
                @if ($correctionAttendance)
                    @foreach ($rests as $index => $rest)
                        <div class="detail__row">
                            <div class="detail__label">休憩{{ $index === 0 ? '' : $index + 1 }}</div>
                            <div class="detail__value">
                                <div class="detail__time-range">
                                    <div class="detail__time-box">
                                        {{ $rest->start_time?->format('H:i') }}
                                    </div>

                                    <span class="detail__time-separator">〜</span>

                                    <div class="detail__time-box">
                                        {{ $rest->end_time?->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @for ($i = 0; $i < $rests->count() + 1; $i++)
                        <div class="detail__row">
                            <div class="detail__label">休憩{{ $i === 0 ? '' : $i + 1 }}</div>
                            <div class="detail__value">
                                <div class="detail__time-range">
                                    <input type="time"
                                        name="rest[{{ $i }}][start]"
                                        value="{{ old("rest.{$i}.start", $rests->get($i)?->start_time?->format('H:i')) }}"
                                        class="detail__time-input {{ old("rest.{$i}.start", $rests->get($i)?->start_time) ? 'has-value' : 'is-empty' }}">

                                    <span class="detail__time-separator">〜</span>

                                    <input type="time"
                                        name="rest[{{ $i }}][end]"
                                        value="{{ old("rest.{$i}.end", $rests->get($i)?->end_time?->format('H:i')) }}"
                                        class="detail__time-input {{ old("rest.{$i}.end", $rests->get($i)?->end_time) ? 'has-value' : 'is-empty' }}">
                                </div>
                                @error("rest.{$i}.start")
                                    <div class="detail__error">{{ $message }}</div>
                                @enderror
                                @error("rest.{$i}.end")
                                    <div class="detail__error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endfor
                @endif

                <!-- 備考 -->
                <div class="detail__row">
                    <div class="detail__label">備考</div>
                    <div class="detail__value">
                        @if ($correctionAttendance)
                            <div class="detail__note-box">{{ $correctionAttendance->reason }}</div>
                        @else
                            <textarea name="reason" class="detail__note-input">{{ old('reason', $correctionAttendance?->reason) }}</textarea>
                        @endif
                        @error('reason')
                            <div class="detail__error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- 修正ボタン or 承認待ちメッセージ -->
            <div class="detail__button-wrapper">
                @if ($correctionAttendance)
                    <p class="detail__pending-message">*承認待ちのため修正はできません。</p>
                @else
                    <button type="submit" class="detail__button">修正</button>
                @endif
            </div>
        </form>
    </div>

    <script>
        // 時刻入力フィールドのクラスを動的に更新
        document.addEventListener('DOMContentLoaded', function() {
            const timeInputs = document.querySelectorAll('.detail__time-input');

            timeInputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value) {
                        this.classList.remove('is-empty');
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                        this.classList.add('is-empty');
                    }
                });
            });
        });
    </script>
@endsection
