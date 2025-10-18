<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Traits\TestHelpers;
use Tests\TestCase;

class StampTest extends TestCase
{
    use RefreshDatabase;
    use TestHelpers;

    /**
     * 現在の日時情報がUIと同じ形式で出力されている
     * 1. 勤怠打刻画面を開く
     * 2. 画面に表示されている日時情報を確認する
     * 画面上に表示されている日時が現在の日時と一致する
     */
    public function test_current_datetime_is_displayed_on_stamp_page()
    {
        // 1. 認証済みユーザーでログイン
        $user = $this->createVerifiedUser();
        $this->actingAs($user);

        // 2. 勤怠打刻画面を開く
        $response = $this->get('/attendance');

        // 画面が正常に表示される
        $response->assertStatus(200);

        // 現在の日付が表示されている（例: 2025年10月17日(木)）
        $currentDate = now()->locale('ja')->translatedFormat('Y年n月j日(D)');
        $response->assertSee($currentDate, false);

        // 現在の時刻が表示されている（例: 14:30）
        $currentTime = now()->format('H:i');
        $response->assertSee($currentTime, false);
    }
}
