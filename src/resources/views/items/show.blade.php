@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
<div class="item-show">
    <div class="item-show__inner">
        <div class="item-show__content">
            <div class="item-show__image">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                @else
                    <img src="{{ asset('images/no-image.png') }}" alt="画像なし">
                @endif
            </div>
            <div class="item-show__detail">
                <h2 class="item-show__name">{{ $item->name }}</h2>
                <p class="item-show__brand">{{ $item->brand_name }}</p>
                <div class="item-show__price-container">
                    <p class="item-show__price">¥{{ number_format($item->price) }} <span class="tax-included">(税込)</span></p>
                </div>
                <div class="item-show__stats">
                    <div class="stat-item">
                        <form action="{{ $item->isFavoritedBy(Auth::user()) 
                            ? '/item/'.$item->id.'/favorites/delete' 
                            : '/item/'.$item->id.'/favorites' }}" 
                            method="POST">
                            @csrf
                            @if($item->isFavoritedBy(Auth::user()))
                                @method('DELETE')
                            @endif
                            <button type="submit" class="favorite-btn">
                                @if($item->isFavoritedBy(Auth::user()))
                                    <i class="fas fa-heart" style="color: red;">⭐️</i>
                                @else
                                    <i class="fas fa-heart">☆</i>
                                @endif
                            </button>
                        </form>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-comment">💬</i>
                    </div>
                </div>
                <form action="/purchase/{{ $item->id }}" method="post">
                    @csrf
                    <button type="submit" class="item-show__purchase-btn">購入手続きへ</button>
                </form>
                
                <div class="item-show__description">
                    <h3>商品説明</h3>
                    <p>{{ $item->description }}</p>
                </div>
                
                <div class="item-show__info">
                    <h3>商品の情報</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">カテゴリー</span>
                            <span class="tag">{{ $item->itemCategory->category->category }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">商品の状態</span>
                            <span class="tag">{{ $item->condition->condition }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="item-show__comments">
            <h3>コメント</h3>
            <div class="comment-list">
                @if(isset($comment) && $comment)
                    <div class="comment-item">
                        <div class="comment-user">
                            <img src="{{ asset('images/default-avatar.png') }}" alt="ユーザーアバター">
                            <span>{{ $comment->user_id }}</span>
                        </div>
                        <p class="comment-text">{{ $comment->comment }}</p>
                    </div>
                @else
                    <p class="no-comments">コメントはまだありません</p>
                @endif
            </div>

            <div class="comment-form">
                <h3>商品へのコメント</h3>
                <textarea name="comment" placeholder="コメントを入力してください"></textarea>
                <button type="submit" class="comment-submit-btn">コメントを送信する</button>
            </div>
        </div>
    </div>
</div>
@endsection
