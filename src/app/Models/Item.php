<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image',
        'condition_id',
        'name',
        'brand_name',
        'description',
        'price',
        'status'
    ];

    /**
     * カテゴリーとの多対多リレーション
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_categories', 'item_id', 'category_id')
            ->withTimestamps();
    }

    /**
     * 商品状態とのリレーション
     */
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    /**
     * ユーザーとのリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * アイテムカテゴリーとのリレーション
     */
    public function itemCategories()
    {
        return $this->hasMany(ItemCategory::class);
    }

    /**
     * お気に入りとのリレーション
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * お気に入りされているかどうかを判定するメソッド
     */
    public function isFavoritedBy($user)
    {
        if (!$user) return false;
        return $this->favorites->contains('user_id', $user->id);
    }

    /**
     * 注文とのリレーション
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * コメントとのリレーション
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 取引状態の定数
     */
    const STATUS_AVAILABLE = 'available';    // 出品中
    const STATUS_TRADING = 'trading';        // 取引中
    const STATUS_SOLD = 'sold';              // 売却済み

    /**
     * 取引状態の変更
     */
    public function markAsTrading()
    {
        $this->update(['status' => self::STATUS_TRADING]);
    }

    public function markAsSold()
    {
        $this->update(['status' => self::STATUS_SOLD]);
    }

    public function markAsAvailable()
    {
        $this->update(['status' => self::STATUS_AVAILABLE]);
    }

    /**
     * 取引状態の確認
     */
    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isTrading()
    {
        return $this->status === self::STATUS_TRADING;
    }

    public function isSold()
    {
        return $this->status === self::STATUS_SOLD;
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

    /**
     * 未読メッセージがあるかチェック
     */
    public function hasUnreadMessages()
    {
        // 自分が出品者の場合：購入者からの未読メッセージ
        // 自分が購入者の場合：出品者からの未読メッセージ
        return $this->messages()
            ->where('user_id', '!=', auth()->id())  // ← 自分以外のユーザーからのメッセージ
            ->where('is_read', false)
            ->exists();
    }

    /**
     * 未読メッセージ件数を取得
     */
    public function getUnreadMessageCount()
    {
        // 自分以外のユーザーからの未読メッセージ件数
        return $this->messages()
            ->where('user_id', '!=', auth()->id())  // ← 自分以外のユーザーからのメッセージ
            ->where('is_read', false)
            ->count();
    }

    /**
     * メッセージとのリレーション
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
