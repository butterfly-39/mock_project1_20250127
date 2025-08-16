@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
<div class="chat-layout">
    <!-- 左サイドバー -->
    <div class="chat-sidebar">
        <h3 class="chat-sidebar__title">その他の取引</h3>
        <div class="chat-sidebar__transactions">
            <!-- 他の取引一覧 -->
        </div>
    </div>

    <!-- メインコンテンツ -->
    <div class="chat-main">
        <!-- 上部ヘッダー -->
        <div class="chat-header">
            <div class="chat-header__logo">CT COACHTECH</div>
            <button class="chat-header__complete-btn">取引を完了する</button>
        </div>

        <!-- 商品情報 -->
        <div class="chat-product-info">
            <div class="product-user">
                <div class="product-user__avatar"></div>
                <p class="product-user__name">「{{ $item->user->name }}」さんとの取引画面</p>
            </div>
            <div class="product-details">
                <div class="product-image">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                    @else
                        <div class="product-image__placeholder">商品画像</div>
                    @endif
                </div>
                <div class="product-info">
                    <h3 class="product-name">{{ $item->name }}</h3>
                    <p class="product-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>
        </div>

        <!-- チャット履歴 -->
        <div class="chat-messages">
            @foreach($messages as $message)
                <div class="message {{ $message->user_id === auth()->id() ? 'message--own' : 'message--other' }}">
                    @if($message->user_id !== auth()->id())
                        <div class="message__avatar"></div>
                    @endif
                    <div class="message__content">
                        <p class="message__username">{{ $message->user->name }}</p>
                        <div class="message__bubble">
                            <p class="message__text">{{ $message->message }}</p>
                            @if($message->image)
                                <img src="{{ asset('storage/' . $message->image) }}" alt="メッセージ画像" class="message__image">
                            @endif
                        </div>
                        @if($message->user_id === auth()->id())
                            <div class="message__actions">
                                <a href="#" class="message__edit">編集</a>
                                <a href="#" class="message__delete">削除</a>
                            </div>
                        @endif
                    </div>
                    @if($message->user_id === auth()->id())
                        <div class="message__avatar"></div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- メッセージ入力 -->
        <div class="chat-input">
            <form class="chat-form" action="{{ route('messages.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                
                <div class="chat-form__input-group">
                    <textarea class="chat-form__input" name="message" placeholder="取引メッセージを記入してください" rows="3">{{ old('message') }}</textarea>
                    <div class="chat-form__buttons">
                        <label class="chat-form__image-btn">
                            <input type="file" name="image" accept="image/*" style="display: none;">
                            画像を追加
                        </label>
                        <button type="submit" class="chat-form__send-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
