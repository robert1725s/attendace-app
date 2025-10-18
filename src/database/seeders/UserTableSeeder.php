<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 認証済みユーザー
        User::create([
            'name' => '認証した男',
            'email' => 'verified@example.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        // 未認証ユーザー
        User::create([
            'name' => '未認証です子',
            'email' => 'unverified@example.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => null,
        ]);

        // 管理者ユーザー（認証済み）
        User::create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);
    }
}
