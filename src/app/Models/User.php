<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ユーザーのプロフィール情報を取得
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * 出品した商品のリレーション
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'user_id');
    }

    /**
     * 購入した商品のリレーション
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * お気に入りに登録した商品のリレーション
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    /**
     * 評価された評価の平均を取得
     */
    public function getAverageRating()
    {
        $ratings = Rating::where('rated_id', $this->id)->get();
        
        if ($ratings->isEmpty()) {
            return null;
        }
        
        $average = $ratings->avg('rating');
        return round($average); // 四捨五入
    }

    /**
     * 評価された評価の件数を取得
     */
    public function getRatingCount()
    {
        return Rating::where('rated_id', $this->id)->count();
    }

    /**
     * 評価された評価の詳細を取得
     */
    public function getRatings()
    {
        return Rating::where('rated_id', $this->id)
            ->with(['rater', 'item'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
