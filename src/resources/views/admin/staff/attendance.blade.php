@extends('layouts.app')

@section('title', '勤怠管理')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff/attendance.css') }}">
@endsection

@section('content')
    <div class="attendance">
        <div class="attendance__container">
            <h1 class="attendance__title">西陵奈々さんの勤怠</h1>

            <div class="attendance__month-navigation">
                <button class="attendance__month-button attendance__month-button--prev">
                    &lt; 前月
                </button>
                <div class="attendance__month-display">
                    <span class="attendance__calendar-icon">📅</span>
                    <span class="attendance__month-text">2023/06</span>
                </div>
                <button class="attendance__month-button attendance__month-button--next">
                    翌月 &gt;
                </button>
            </div>

            <div class="attendance__table-wrapper">
                <table class="attendance__table">
                    <thead class="attendance__table-head">
                        <tr class="attendance__table-row">
                            <th class="attendance__table-header">日付</th>
                            <th class="attendance__table-header">出勤</th>
                            <th class="attendance__table-header">退勤</th>
                            <th class="attendance__table-header">休憩</th>
                            <th class="attendance__table-header">合計</th>
                            <th class="attendance__table-header">詳細</th>
                        </tr>
                    </thead>
                    <tbody class="attendance__table-body">
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/01(木)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/02(金)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/03(土)</td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/04(日)</td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/05(月)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/06(火)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/07(水)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/08(木)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/09(金)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/10(土)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/11(日)</td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/12(月)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/13(火)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/14(水)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/15(木)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/16(金)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/17(土)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/18(日)</td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/19(月)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/20(火)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/21(水)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/22(木)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/23(金)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/24(土)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/25(日)</td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell"></td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/26(月)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/27(火)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/28(水)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/29(木)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                        <tr class="attendance__table-row">
                            <td class="attendance__table-cell">06/30(金)</td>
                            <td class="attendance__table-cell">09:00</td>
                            <td class="attendance__table-cell">18:00</td>
                            <td class="attendance__table-cell">1:00</td>
                            <td class="attendance__table-cell">8:00</td>
                            <td class="attendance__table-cell">詳細</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="attendance__actions">
                <button class="attendance__csv-button">CSV出力</button>
            </div>
        </div>
    </div>
@endsection
