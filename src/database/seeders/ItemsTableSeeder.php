<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'condition_id' => 1,
            'name' => '腕時計',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'image' => 'item_images/9Wg109feDkibjZuicpnxzXycKhoL4frG9qfsZSKg.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'condition_id' => 2,
            'name' => 'HDD',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'image' => 'item_images/ui1V1PRx7PLLWYS79Tnleln4oJtpXrfYGcvGSpet.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'condition_id' => 3,
            'name' => '玉ねぎ3束',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'image' => 'item_images/B8GeSGQqcOz7HdBtE5EWnvowRI4AXo6aeHA11jiv.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'condition_id' => 4,
            'name' => '革靴',
            'price' => 4000,
            'description' => 'クラシックなデザインの革靴',
            'image' => 'item_images/yG1b5p1nn36IO92s6JJF2ZBJ0tmvxPiBbHyJRKYz.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'condition_id' => 1,
            'name' => 'ノートPC',
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'image' => 'item_images/qKkXCx2nbQvgfmee0ts88iTdL04uLUyQ5XVKxKPh.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'condition_id' => 2,
            'name' => 'マイク',
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
            'image' => 'item_images/T0s57sp1snbiw33Oix8e2AoMHKVzzy280qo7SRFp.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'condition_id' => 3,
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'description' => 'おしゃれなショルダーバッグ',
            'image' => 'item_images/zLgMuQ9n3VTnoBXi2A6N2rrzlZY3eszoLM5AnO8J.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'condition_id' => 4,
            'name' => 'タンブラー',
            'price' => 500,
            'description' => '使いやすいタンブラー',
            'image' => 'item_images/JPkuEHip07QZGO7fpLZovNxKXxOiCbpsnpSAxVeU.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'condition_id' => 1,
            'name' => 'コーヒーミル',
            'price' => 4000,
            'description' => '手動のコーヒーミル',
            'image' => 'item_images/WGes4Z2JVPJ40aXTgkv2piaNZqNFT8pjiuc0CNHN.jpg',
        ];
        DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'condition_id' => 2,
            'name' => 'メイクセット',
            'price' => 2500,
            'description' => '便利なメイクアップセット',
            'image' => 'item_images/WGtJVdTWF5MYITOAjljgdeyz83CWZybQHTkObKDE.jpg',
        ];
        DB::table('items')->insert($param);
    }
}