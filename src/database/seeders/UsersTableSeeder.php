<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザーを作成
        $userId = DB::table('users')->insertGetId([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ]);

        // プロフィールを作成
        DB::table('profiles')->insert([
            'user_id' => $userId,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区代々木1-1-1',
            'building' => 'テストビル101',
            'image' => null,
        ]);
    }
}
