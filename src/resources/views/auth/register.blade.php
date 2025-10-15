@extends('layouts.app')

@section('title', '会員登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register">
        <div class="register__container">
            <h1 class="register__title">会員登録</h1>

            <form class="register__form" method="POST" action="">
                @csrf

                <div class="register__form-group">
                    <label class="register__label">名前</label>
                    <input type="text" name="name" class="register__input" value="{{ old('name') }}" required>
                </div>

                <div class="register__form-group">
                    <label class="register__label">メールアドレス</label>
                    <input type="email" name="email" class="register__input" value="{{ old('email') }}" required>
                </div>

                <div class="register__form-group">
                    <label class="register__label">パスワード</label>
                    <input type="password" name="password" class="register__input" required>
                </div>

                <div class="register__form-group">
                    <label class="register__label">パスワード確認</label>
                    <input type="password" name="password_confirmation" class="register__input" required>
                </div>

                <button type="submit" class="register__button">登録する</button>
            </form>

            <div class="register__footer">
                <a href="/login" class="register__login-link">ログインはこちら</a>
            </div>
        </div>
    </div>
@endsection
