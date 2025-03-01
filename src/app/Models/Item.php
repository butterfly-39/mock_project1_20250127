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
}
