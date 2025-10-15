@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/attendance/index.css') }}">
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
            <h1 class="index__title">2023年6月1日の勤怠</h1>

            <!-- 日付ナビゲーション -->
            <div class="index__date-nav">
                <a href="{{ url('/admin/attendance?date=2023-05-31') }}" class="index__date-link">
                    <span class="index__date-arrow">←</span>
                    <span class="index__date-text">前日</span>
                </a>
                <div class="index__date-current">
                    <span class="index__date-icon">📅</span>
                    <span class="index__date-label">2023/06/01</span>
                </div>
                <a href="{{ url('/admin/attendance?date=2023-06-02') }}" class="index__date-link">
                    <span class="index__date-text">翌日</span>
                    <span class="index__date-arrow">→</span>
                </a>
            </div>

            <!-- 勤怠テーブル -->
            <div class="index__table-wrapper">
                <table class="index__table">
                    <thead class="index__table-head">
                        <tr class="index__table-row">
                            <th class="index__table-header">名前</th>
                            <th class="index__table-header">出勤</th>
                            <th class="index__table-header">退勤</th>
                            <th class="index__table-header">休憩</th>
                            <th class="index__table-header">合計</th>
                            <th class="index__table-header">詳細</th>
                        </tr>
                    </thead>
                    <tbody class="index__table-body">
                        <tr class="index__table-row">
                            <td class="index__table-cell">山田 太郎</td>
                            <td class="index__table-cell">09:00</td>
                            <td class="index__table-cell">18:00</td>
                            <td class="index__table-cell">1:00</td>
                            <td class="index__table-cell">8:00</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/attendance/detail/1') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">西 怜奈</td>
                            <td class="index__table-cell">09:00</td>
                            <td class="index__table-cell">18:00</td>
                            <td class="index__table-cell">1:00</td>
                            <td class="index__table-cell">8:00</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/attendance/detail/2') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">増田 一世</td>
                            <td class="index__table-cell">09:00</td>
                            <td class="index__table-cell">18:00</td>
                            <td class="index__table-cell">1:00</td>
                            <td class="index__table-cell">8:00</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/attendance/detail/3') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">山本 敬吾</td>
                            <td class="index__table-cell">09:00</td>
                            <td class="index__table-cell">18:00</td>
                            <td class="index__table-cell">1:00</td>
                            <td class="index__table-cell">8:00</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/attendance/detail/4') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">秋田 朋美</td>
                            <td class="index__table-cell">09:00</td>
                            <td class="index__table-cell">18:00</td>
                            <td class="index__table-cell">1:00</td>
                            <td class="index__table-cell">8:00</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/attendance/detail/5') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                        <tr class="index__table-row">
                            <td class="index__table-cell">中西 教夫</td>
                            <td class="index__table-cell">09:00</td>
                            <td class="index__table-cell">18:00</td>
                            <td class="index__table-cell">1:00</td>
                            <td class="index__table-cell">8:00</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/attendance/detail/6') }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
