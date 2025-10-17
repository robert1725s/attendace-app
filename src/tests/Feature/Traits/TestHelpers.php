<?php

namespace Tests\Feature\Traits;

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
            'email' => 'test@example.com',
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
            'email' => 'test@example.com',
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
}
