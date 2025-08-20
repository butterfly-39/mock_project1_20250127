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
        'rater_id',
        'rated_id',
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
     * 評価者とのリレーション
     */
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    /**
     * 被評価者とのリレーション
     */
    public function rated()
    {
        return $this->belongsTo(User::class, 'rated_id');
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
     * 特定の評価者の評価を取得するスコープ
     */
    public function scopeByRater($query, $raterId)
    {
        return $query->where('rater_id', $raterId);
    }

    /**
     * 特定の被評価者の評価を取得するスコープ
     */
    public function scopeByRated($query, $ratedId)
    {
        return $query->where('rated_id', $ratedId);
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

    /**
     * 特定の取引での評価状況をチェック
     */
    public function scopeForTransaction($query, $itemId, $orderId)
    {
        return $query->where('item_id', $itemId)
                    ->where('order_id', $orderId);
    }

    /**
     * 特定のユーザーが特定の取引で評価済みかチェック
     */
    public function scopeUserHasRated($query, $itemId, $orderId, $raterId)
    {
        return $query->where('item_id', $itemId)
                    ->where('order_id', $orderId)
                    ->where('rater_id', $raterId);
    }
}
 