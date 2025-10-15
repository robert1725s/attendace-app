@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/admin_login.css') }}">
@endsection

@section('content')
    <div class="login">
        <h1 class="login__title">管理者ログイン</h1>
        <form class="login__form" action="/admin/login" method="POST">
            @csrf
            <!-- メールアドレス -->
            <div class="login__form-group">
                <label class="login__label">メールアドレス</label>
                <input type="email" name="email" class="login__input"value="{{ old('email') }}">
                @error('email')
                    <div class="login__error">{{ $message }}</div>
                @enderror
            </div>
            <!-- パスワード -->
            <div class="login__form-group">
                <label class="login__label">パスワード</label>
                <input type="password" name="password"class="login__input">
                @error('password')
                    <div class="login__error">{{ $message }}</div>
                @enderror
            </div>
            <!-- ログインボタン -->
            <button type="submit" class="login__button">
                管理者ログインする
            </button>
        </form>
    </div>
@endsection
