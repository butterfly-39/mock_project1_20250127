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
        // 出品者ユーザー1を作成
        $seller1Id = DB::table('users')->insertGetId([
            'name' => '出品者1',
            'email' => 'test1@test.com',
            'password' => Hash::make('password'),
        ]);

        // 出品者ユーザー2を作成
        $seller2Id = DB::table('users')->insertGetId([
            'name' => '出品者2',
            'email' => 'test2@test.com',
            'password' => Hash::make('password'),
        ]);

        // 購入者ユーザー3を作成
        $buyerId = DB::table('users')->insertGetId([
            'name' => '購入者3',
            'email' => 'test3@test.com',
            'password' => Hash::make('password'),
        ]);

        // 出品者ユーザー1のプロフィール作成
        DB::table('profiles')->insert([
            'user_id' => $seller1Id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区代々木1-1-1',
            'building' => 'テストビル101',
            'image' => null,
        ]);

        // 出品者ユーザー2のプロフィール作成
        DB::table('profiles')->insert([
            'user_id' => $seller2Id,
            'postal_code' => '123-4568',
            'address' => '東京都渋谷区代々木1-1-2',
            'building' => 'テストビル102',
            'image' => null,
        ]);

        // 購入者ユーザー3のプロフィール作成
        DB::table('profiles')->insert([
            'user_id' => $buyerId,
            'postal_code' => '123-4569',
            'address' => '東京都渋谷区代々木1-1-3',
            'building' => 'テストビル103',
            'image' => null,
        ]);
    }
}
