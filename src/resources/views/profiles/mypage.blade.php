@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-form">
    <div class="mypage-form__group">
        <div class="mypage-form__image-container">
            <input class="mypage-form__select-btn" type="file" name="image" id="image" accept="image/*">
            @if($user->image)
                <img src="{{ asset('storage/' . $user->image) }}" alt="プロフィール画像" class="mypage-form__image">
            @else
                <i class="fas fa-user mypage-form__no-image"></i>
            @endif
            <label class="mypage-form__label" for="image">ユーザー名</label>
            <a class="mypage-form__label-btn" href="/mypage/profile">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage-form__group">
        <a href="/mypage?tab=sell" class="mypage-form__heading">出品した商品</a>
        <a href="/mypage?tab=buy" class="mypage-form-group__heading">購入した商品</a>
    </div>
</div>

<div class="divider"></div>

<div class="mypage-list">
    <div class="mypage-card">
        <img src="/images/sample.jpg" alt="商品画像" class="mypage-card__image">
        <p class="mypage-card__name">商品名</p>
    </div>
</div>
@endsection

