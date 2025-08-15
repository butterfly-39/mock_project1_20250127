<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'order_postal_code',
        'order_address',
        'order_building',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * 取引状態の定数
     */
    const STATUS_PENDING = 'pending';        // 取引中
    const STATUS_COMPLETED = 'completed';    // 取引完了

    /**
     * 取引状態の変更
     */
    public function markAsCompleted()
    {
        $this->update(['status' => self::STATUS_COMPLETED]);

        // 商品の状態も更新
        $this->item->markAsSold();
    }

    /**
     * 取引状態の確認
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * 取引中の商品を取得するスコープ
     */
    public function scopeTrading($query)
    {
        return $query->where('status', self::STATUS_TRADING);
    }

    /**
     * 出品中の商品を取得するスコープ
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * 売却済みの商品を取得するスコープ
     */
    public function scopeSold($query)
    {
        return $query->where('status', self::STATUS_SOLD);
    }
}
