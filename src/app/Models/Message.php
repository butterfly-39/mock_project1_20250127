<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * 一括代入可能な属性
     */
    protected $fillable = [
        'item_id',
        'user_id',
        'message',
        'image',
        'is_edited',
        'edited_at',
        'is_deleted',
        'deleted_at'
    ];

    /**
     * 属性のキャスト
     */
    protected $casts = [
        'is_edited' => 'boolean',
        'is_deleted' => 'boolean',
        'edited_at' => 'datetime',
        'deleted_at' => 'datetime',
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
     * ユーザーとのリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 編集済みメッセージのスコープ
     */
    public function scopeEdited($query)
    {
        return $query->where('is_edited', true);
    }

    /**
     * 削除されていないメッセージのスコープ
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }

    /**
     * 特定の商品のメッセージを取得するスコープ
     */
    public function scopeForItem($query, $itemId)
    {
        return $query->where('item_id', $itemId);
    }

    /**
     * 特定のユーザーが送信したメッセージを取得するスコープ
     */
    public function scopeFromUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * メッセージを編集する
     */
    public function edit($newMessage)
    {
        $this->update([
            'message' => $newMessage,
            'is_edited' => true,
            'edited_at' => now()
        ]);
    }

    /**
     * メッセージを論理削除する
     */
    public function softDelete()
    {
        $this->update([
            'is_deleted' => true,
            'deleted_at' => now()
        ]);
    }

    /**
     * メッセージが編集済みかチェック
     */
    public function isEdited()
    {
        return $this->is_edited;
    }

    /**
     * メッセージが削除済みかチェック
     */
    public function isDeleted()
    {
        return $this->is_deleted;
    }

    /**
     * 編集日時を取得
     */
    public function getEditedAt()
    {
        return $this->edited_at;
    }

    /**
     * 削除日時を取得
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }
}
 