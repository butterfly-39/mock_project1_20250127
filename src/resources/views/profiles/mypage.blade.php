@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="mypage-form__group">
    <div class="mypage-form__image-container">
        <input class="mypage-form__select-btn" type="file" name="image" id="image" accept="image/*">
        <label class="mypage-form__select-label" for="image">ユーザー名</label>
        <a href="/mypage/profile" class="mypage-form__select-label">プロフィールを編集</a>
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

