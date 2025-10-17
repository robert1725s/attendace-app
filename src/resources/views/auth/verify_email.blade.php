@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify_email.css') }}">
@endsection

@section('content')
    @if (session('status') == 'verification-link-sent')
        <div class="verify-email__success-message">
            認証メールが再送されました
        </div>
    @endif
    <div class="verify-email__container">

        <!-- メッセージ -->
        <p class="verify-email__message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <div class="verify-email__actions">
            <!-- Mailhogへのリンク -->
            <a href="http://localhost:8025/" class="verify-email__button">認証はこちらから</a>
            <form method="POST" action="{{ route('verification.send') }}" class="verify-email__form">
                @csrf
                <!-- 再送ボタン -->
                <button type="submit" class="verify-email__link">認証メールを再送する</button>
            </form>
        </div>
    </div>
@endsection
