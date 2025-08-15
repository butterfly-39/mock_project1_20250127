<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            // 評価に関連する情報
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete(); // 購入履歴を参照

            // 評価内容
            $table->integer('rating');

            // タイムスタンプ
            $table->timestamps();

            // インデックス
            $table->index(['item_id', 'rating']);
            $table->index(['order_id', 'created_at']);

            // 制約
            $table->unique(['item_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
