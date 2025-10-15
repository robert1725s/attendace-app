@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/correction/index.css') }}">
@endsection

@section('header-nav')
    <nav class="index__nav">
        <a href="/attendance" class="index__nav-link">勤怠</a>
        <a href="/attendance/list" class="index__nav-link">勤怠一覧</a>
        <a href="/application" class="index__nav-link">申請</a>
        <form action="/logout" method="POST" class="index__nav-form">
            @csrf
            <button type="submit" class="index__nav-button">ログアウト</button>
        </form>
    </nav>
@endsection

@section('content')
    <div class="index">
        <div class="index__container">
            <!-- タイトル -->
            <h1 class="index__title">申請一覧</h1>

            <!-- タブナビゲーション -->
            <div class="index__tabs">
                <a href="{{ url('/application?status=pending') }}" class="index__tab index__tab--active">承認待ち</a>
                <a href="{{ url('/application?status=approved') }}" class="index__tab">承認済み</a>
            </div>

            <!-- 申請テーブル -->
            <div class="index__table-wrapper">
                <table class="index__table">
                    <thead class="index__table-head">
                        <tr class="index__table-row">
                            <th class="index__table-header">状態</th>
                            <th class="index__table-header">名前</th>
                            <th class="index__table-header">対象日時</th>
                            <th class="index__table-header">申請理由</th>
                            <th class="index__table-header">申請日時</th>
                            <th class="index__table-header">詳細</th>
                        </tr>
                    </thead>
                    <tbody class="index__table-body">
                        @for($i = 1; $i <= 9; $i++)
                            <tr class="index__table-row">
                                <td class="index__table-cell">承認待ち</td>
                                <td class="index__table-cell">西怜奈</td>
                                <td class="index__table-cell">2023/06/01</td>
                                <td class="index__table-cell">遅延のため</td>
                                <td class="index__table-cell">2023/06/02</td>
                                <td class="index__table-cell">
                                    <a href="{{ url('/application/detail/' . $i) }}" class="index__detail-link">詳細</a>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
