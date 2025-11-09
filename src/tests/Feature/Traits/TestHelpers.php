<?php

namespace Tests\Feature\Traits;

use App\Models\Attendance;
use App\Models\Rest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

trait TestHelpers
{
    /**
     * メール未認証のユーザーを作成
     */
    protected function createUnverifiedUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
        ], $attributes));
    }

    /**
     * メール認証済みのユーザーを作成
     */
    protected function createVerifiedUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ], $attributes));
    }

    /**
     * 管理者ユーザーを作成
     */
    protected function createAdminUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'is_admin' => true,
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ], $attributes));
    }

    /**
     * テストユーザーを作成してログイン
     */
    protected function loginAsUser(array $attributes = []): User
    {
        $user = $this->createVerifiedUser($attributes);
        $this->actingAs($user);
        return $user;
    }

    /**
     * 管理者ユーザーを作成してログイン
     */
    protected function loginAsAdmin(array $attributes = []): User
    {
        $user = $this->createAdminUser($attributes);
        $this->actingAs($user);
        return $user;
    }

    /**
     * 出勤中のユーザーを作成
     */
    protected function createWorkingUser(array $attributes = []): User
    {
        $user = $this->createVerifiedUser($attributes);

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->format('Y-m-d'),
            'start_time' => now()->setTime(9, 0, 0),
            'end_time' => null,
        ]);

        return $user;
    }

    /**
     * 休憩中のユーザーを作成
     */
    protected function createOnRestUser(array $attributes = []): User
    {
        $user = $this->createVerifiedUser($attributes);

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->format('Y-m-d'),
            'start_time' => now()->setTime(9, 0, 0),
            'end_time' => null,
        ]);

        Rest::create([
            'attendance_id' => $attendance->id,
            'start_time' => now()->setTime(12, 0, 0),
            'end_time' => null,
        ]);

        return $user;
    }

    /**
     * 退勤済のユーザーを作成
     */
    protected function createFinishedWorkUser(array $attributes = []): User
    {
        $user = $this->createVerifiedUser($attributes);

        Attendance::create([
            'user_id' => $user->id,
            'work_date' => now()->format('Y-m-d'),
            'start_time' => now()->setTime(9, 0, 0),
            'end_time' => now()->setTime(18, 0, 0),
        ]);

        return $user;
    }
}
