@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/staff/index.css') }}">
@endsection

@section('header-nav')
    <nav class="index__nav">
        <a href="/admin/attendance" class="index__nav-link">勤怠一覧</a>
        <a href="/admin/staff" class="index__nav-link">スタッフ一覧</a>
        <a href="/admin/requests" class="index__nav-link">申請一覧</a>
        <form action="/admin/logout" method="POST" class="index__nav-form">
            @csrf
            <button type="submit" class="index__nav-button">ログアウト</button>
        </form>
    </nav>
@endsection

@section('content')
    <div class="index">
        <div class="index__container">
            <!-- タイトル -->
            <h1 class="index__title">スタッフ一覧</h1>

            <!-- スタッフテーブル -->
            <div class="index__table-wrapper">
                <table class="index__table">
                    <thead class="index__table-head">
                        <tr class="index__table-row">
                            <th class="index__table-header">名前</th>
                            <th class="index__table-header">メールアドレス</th>
                            <th class="index__table-header">月次勤怠</th>
                        </tr>
                    </thead>
                    <tbody class="index__table-body">
                        <tr class="index__table-row">
                            <td class="index__table-cell">西 怜奈</td>
                            <td class="index__table-cell">reina.n@coachtech.com</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/staff/monthly/1') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">山田 太郎</td>
                            <td class="index__table-cell">taro.y@coachtech.com</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/staff/monthly/2') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">増田 一世</td>
                            <td class="index__table-cell">issei.m@coachtech.com</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/staff/monthly/3') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">山本 敬吾</td>
                            <td class="index__table-cell">keikichi.y@coachtech.com</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/staff/monthly/4') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">秋田 朋美</td>
                            <td class="index__table-cell">tomomi.a@coachtech.com</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/staff/monthly/5') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">中西 教夫</td>
                            <td class="index__table-cell">norio.n@coachtech.com</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/staff/monthly/6') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
