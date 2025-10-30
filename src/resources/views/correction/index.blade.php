@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/correction/index.css') }}">
@endsection

@section('content')
    <div class="index__container">
        <!-- タイトル -->
        <h1 class="index__title">申請一覧</h1>

        <!-- タブナビゲーション -->
        <div class="index__tabs">
            <a href="{{ url('/stamp_correction_request/list') }}"
                class="index__tab {{ $tab === 'pending' ? 'index__tab--active' : '' }}">承認待ち</a>
            <a href="{{ url('/stamp_correction_request/list?tab=approved') }}"
                class="index__tab {{ $tab === 'approved' ? 'index__tab--active' : '' }}">承認済み</a>
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
                    @forelse($corrections as $correction)
                        <tr class="index__table-row">
                            <td class="index__table-cell">{{ $correction->is_approved ? '承認済み' : '承認待ち' }}</td>
                            <td class="index__table-cell">{{ $correction->attendance->user->name }}</td>
                            <td class="index__table-cell">
                                {{ $correction->attendance->work_date->format('Y/m/d') }}</td>
                            <td class="index__table-cell">{{ $correction->reason }}</td>
                            <td class="index__table-cell">{{ $correction->created_at->format('Y/m/d') }}</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/attendance/detail/' . $correction->attendance_id) }}"
                                    class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="index__table-row">
                            <td class="index__table-cell index__table-cell--empty" colspan="6">
                                申請がありません</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
