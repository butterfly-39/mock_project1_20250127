@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endsection

@section('content')
<div class="chat-layout seller-chat">
    <!-- 左サイドバー -->
    <div class="chat-sidebar">
        <h3 class="chat-sidebar__title">その他の取引</h3>
        <div class="chat-sidebar__transactions">
            @foreach($otherTradingItems as $otherItem)
                <div class="chat-sidebar__transaction-item">
                    <a href="{{ route('sellers.chat', ['item_id' => $otherItem->id]) }}" class="chat-sidebar__transaction-link">
                        {{ $otherItem->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- メインコンテンツ -->
    <div class="chat-main">
        <div class="chat-header seller-chat-header">
            <div class="product-user">
                <div class="product-user__avatar">
                    @if($buyer->profile && $buyer->profile->image)
                        <img src="{{ asset('storage/' . $buyer->profile->image) }}" alt="プロフィール画像">
                    @else
                        <div class="product-user__avatar-placeholder">No Image</div>
                    @endif
                </div>
                <p class="product-user__name">「{{ $buyer->name }}」さんとの取引画面</p>
            </div>
            <!-- 出品者には取引完了ボタンを表示しない -->
        </div>

        <!-- 商品情報 -->
        <div class="chat-product-info">
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
                    <div class="message__user-info">
                        @if($message->user_id !== auth()->id())
                            <div class="message__avatar">
                                @if($message->user->profile && $message->user->profile->image)
                                    <img src="{{ asset('storage/' . $message->user->profile->image) }}" alt="プロフィール画像">
                                @else
                                    <div class="message__avatar-placeholder">No Image</div>
                                @endif
                            </div>
                        @endif
                        <p class="message__username">{{ $message->user->name }}</p>
                        @if($message->user_id === auth()->id())  <!-- ← 自分のプロフィール画像をここに移動 -->
                            <div class="message__avatar">
                                @if($message->user->profile && $message->user->profile->image)
                                    <img src="{{ asset('storage/' . $message->user->profile->image) }}" alt="プロフィール画像">
                                @else
                                    <div class="message__avatar-placeholder">No Image</div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="message__content">
                        <div class="message__bubble">
                            <p class="message__text">{{ $message->message }}</p>
                            @if($message->image)
                                <img src="{{ asset('storage/' . $message->image) }}" alt="メッセージ画像" class="message__image">
                            @endif
                        </div>
                        @if($message->user_id === auth()->id())
                            <div class="message__actions">
                                <button type="button" class="message__edit" onclick="editMessage({{ $message->id }}, '{{ addslashes($message->message) }}')">編集</button>
                                <form action="{{ route('messages.destroy', $message) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="message__delete" onclick="return confirm('このメッセージを削除しますか？')">削除</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- メッセージ入力 -->
        <div class="chat-input">
            <form class="chat-form" action="{{ route('messages.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                @if ($errors->any())
                    <div class="chat-form__errors">
                        @error('message')
                            <p class="chat-form__error">{{ $message }}</p>
                        @enderror
                        @error('image')
                            <p class="chat-form__error">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

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

        @if($hasRated && !$sellerHasRated)
            <!-- 評価モーダル -->
            <div id="ratingModal" class="modal" style="display: block;">
                <div class="modal-content rating-modal">
                    <div class="modal-body">
                        <h3 class="rating-title">取引が完了しました。</h3>
                        <p class="rating-question">今回の取引相手はどうでしたか？</p>

                        <form action="{{ route('ratings.store') }}" method="POST" class="rating-form">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">

                            <div class="rating-stars">
                                <input type="radio" name="rating" value="5" id="star5" required>
                                <label for="star5" class="star">★</label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4" class="star">★</label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3" class="star">★</label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2" class="star">★</label>
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1" class="star">★</label>
                            </div>

                            <div class="rating-submit">
                                <button type="submit" class="btn btn-primary">送信する</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
// 入力情報保持機能
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('.chat-form__input');
    const storageKey = 'chat_draft_seller_{{ $item->id }}_{{ $buyer->id }}';

    const savedContent = localStorage.getItem(storageKey);
    if (savedContent) {
        textarea.value = savedContent;
    }

    textarea.addEventListener('input', function() {
        localStorage.setItem(storageKey, this.value);
    });

    document.querySelector('.chat-form').addEventListener('submit', function() {
        localStorage.removeItem(storageKey);
    });

    window.addEventListener('beforeunload', function() {
        if (textarea.value.trim()) {
            localStorage.setItem(storageKey, textarea.value);
        }
    });
});

function editMessage(messageId, currentMessage) {
    const textarea = document.querySelector('.chat-form__input');
    textarea.value = currentMessage;
    textarea.focus();

    const form = document.querySelector('.chat-form');
    form.action = '{{ route("messages.update", "") }}/' + messageId;

    const existingMethod = form.querySelector('input[name="_method"]');
    if (existingMethod) {
        existingMethod.remove();
    }

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PUT';
    form.appendChild(methodInput);

    form.dataset.editMode = 'true';
    form.dataset.editMessageId = messageId;
}

document.querySelector('.chat-form').addEventListener('submit', function(e) {
    if (this.dataset.editMode === 'true') {
        this.action = '{{ route("messages.update", "") }}/' + this.dataset.editMessageId;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        this.appendChild(methodInput);
    }
});

window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
</script>
@endsection
