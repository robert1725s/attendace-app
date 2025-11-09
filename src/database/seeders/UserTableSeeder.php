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
        // 認証済みユーザー1
        User::create([
            'name' => '認証した男',
            'email' => 'verified@coachtech.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        // 未認証ユーザー
        User::create([
            'name' => '未認証です子',
            'email' => 'unverified@coachtech.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => null,
        ]);

        // 管理者ユーザー
        User::create([
            'name' => '管理者',
            'email' => 'admin@coachtech.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'is_admin' => true,
        ]);

        // 認証済みユーザー2
        User::create([
            'name' => 'テストユーザ2',
            'email' => 'test2@coachtech.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        // 認証済みユーザー3
        User::create([
            'name' => 'テストユーザ3',
            'email' => 'test3@coachtech.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);

        // 認証済みユーザー4
        User::create([
            'name' => 'テストユーザ4',
            'email' => 'test4@coachtech.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]);
    }
}
