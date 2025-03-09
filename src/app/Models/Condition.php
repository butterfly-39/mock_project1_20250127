<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = [
        'condition',
    ];

    /**
     * この状態の商品を取得
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
