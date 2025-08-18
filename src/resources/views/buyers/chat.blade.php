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
        <div class="chat-header">
            <div class="product-user">
                <div class="product-user__avatar">
                    @if($item->user->profile && $item->user->profile->image)
                        <img src="{{ asset('storage/' . $item->user->profile->image) }}" alt="プロフィール画像">
                    @else
                        <div class="product-user__avatar-placeholder">No Image</div>
                    @endif
                </div>
                <p class="product-user__name">「{{ $item->user->name }}」さんとの取引画面</p>
            </div>
            <button class="chat-header__complete-btn">取引を完了する</button>
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
                                <button type="button" class="message__edit" onclick="editMessage({{ $message->id }}, '{{ addslashes($message->message) }}')">編集</button>
                                <form action="{{ route('messages.destroy', $message) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="message__delete" onclick="return confirm('このメッセージを削除しますか？')">削除</button>
                                </form>
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
    </div>
</div>
@endsection

@section('scripts')
<script>
function editMessage(messageId, currentMessage) {
    // 編集用のフォームを表示
    const textarea = document.querySelector('.chat-form__input');
    textarea.value = currentMessage;
    textarea.focus();
    
    // フォームのactionを編集用に変更
    const form = document.querySelector('.chat-form');
    form.action = '{{ route("messages.update", "") }}/' + messageId;
    
    // 既存の_methodフィールドを削除
    const existingMethod = form.querySelector('input[name="_method"]');
    if (existingMethod) {
        existingMethod.remove();
    }
    
    // メソッドをPUTに変更
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PUT';
    form.appendChild(methodInput);
    
    // 送信ボタンのテキストを変更
    const submitBtn = document.querySelector('.chat-form__send-btn');
    submitBtn.innerHTML = '更新';
    
    // 編集モードフラグを設定
    form.dataset.editMode = 'true';
    form.dataset.editMessageId = messageId;
}

// フォーム送信時の処理
document.querySelector('.chat-form').addEventListener('submit', function(e) {
    if (this.dataset.editMode === 'true') {
        // 編集モードの場合
        this.action = '{{ route("messages.update", "") }}/' + this.dataset.editMessageId;
        
        // メソッドをPUTに変更
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        this.appendChild(methodInput);
    }
});
</script>
@endsection
