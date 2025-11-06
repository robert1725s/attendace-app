@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/staff/index.css') }}">
@endsection

@section('content')
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
                    @forelse($staffs as $staff)
                        <tr class="index__table-row">
                            <td class="index__table-cell">{{ $staff->name }}</td>
                            <td class="index__table-cell">{{ $staff->email }}</td>
                            <td class="index__table-cell">
                                <a href="{{ url('/admin/attendance/staff/' . $staff->id) }}" class="index__detail-link">詳細</a>
                            </td>
                        </tr>
                    @empty
                        <tr class="index__table-row">
                            <td class="index__table-cell index__table-cell--empty" colspan="3">
                                スタッフがいません</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
