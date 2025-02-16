<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $params = [
            ['id' => 1, 'condition' => '良好'],
            ['id' => 2, 'condition' => '目立った傷や汚れなし'],
            ['id' => 3, 'condition' => 'やや傷や汚れあり'],
            ['id' => 4, 'condition' => '状態が悪い'],
        ];

        DB::table('conditions')->insert($params);
    }
}

