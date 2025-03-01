@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-form">
    <div class="mypage-form__group">
        <div class="mypage-form__image-container">
            <input class="mypage-form__select-btn" type="file" name="image" id="image" accept="image/*">
            @if($user->profile && $user->profile->image)
                <img src="{{ asset('storage/' . $user->profile->image) }}" alt="プロフィール画像" class="mypage-form__image" style="width: 4rem; height: 4rem;">
            @else
                <i class="fas fa-user mypage-form__no-image" style="font-size: 4rem;"></i>
            @endif
            <label class="mypage-form__label" for="image">ユーザー名</label>
            <a class="mypage-form__label-btn" href="/mypage/profile">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage-form__group">
        <a href="/mypage?tab=sell" class="mypage-form__heading {{ request()->get('tab') === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="/mypage?tab=buy" class="mypage-form__heading {{ request()->get('tab') === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>
</div>

<div class="divider"></div>

<div class="mypage-list">
    @foreach($items as $item)
        <div class="mypage-card">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" class="mypage-card__image">
            @else
                <img src="/images/sample.jpg" alt="商品画像" class="mypage-card__image">
            @endif
            <p class="mypage-card__name">{{ $item->name }}</p>
        </div>
    @endforeach

    @if($items->isEmpty())
        <p class="mypage-list__empty">
            {{ request()->get('tab') === 'buy' ? '購入した商品はありません' : '出品した商品はありません' }}
        </p>
    @endif
</div>
@endsection

