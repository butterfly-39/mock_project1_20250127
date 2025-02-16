<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['id' => 1, 'category' => 'ファッション'],
            ['id' => 2, 'category' => '家電'],
            ['id' => 3, 'category' => 'インテリア'],
            ['id' => 4, 'category' => 'レディース'],
            ['id' => 5, 'category' => 'メンズ'],
            ['id' => 6, 'category' => 'コスメ'],
            ['id' => 7, 'category' => '本'],
            ['id' => 8, 'category' => 'ゲーム'],
            ['id' => 9, 'category' => 'スポーツ'],
            ['id' => 10, 'category' => 'キッチン'],
            ['id' => 11, 'category' => 'ハンドメイド'],
            ['id' => 12, 'category' => 'アクセサリー'],
            ['id' => 13, 'category' => 'おもちゃ'],
            ['id' => 14, 'category' => 'ベビー・キッズ'],
        ];

        DB::table('categories')->insert($params);
    }
}
