<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * 一括代入可能な属性
     */
    protected $fillable = [
        'item_id',
        'order_id',
        'rating'
    ];

    /**
     * 属性のキャスト
     */
    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * 商品とのリレーション
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * 購入履歴とのリレーション
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 特定の商品の評価を取得するスコープ
     */
    public function scopeForItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    /**
     * 特定の購入履歴の評価を取得するスコープ
     */
    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /**
     * 評価が存在するかチェック
     */
    public function exists()
    {
        return $this->exists;
    }

    /**
     * 評価点数を取得
     */
    public function getRating()
    {
        return $this->rating;
    }


    /**
     * 評価を作成日時順でソート
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * 評価点数でソート
     */
    public function scopeByRating($query, $direction = 'desc')
    {
        return $query->orderBy('rating', $direction);
    }
}
 