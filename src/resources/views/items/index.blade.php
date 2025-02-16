@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="items-form">
    <a href="/?tab=mylist" class="items-form__heading">おすすめ</a>
    <a href="/?tab=mylist" class="items-form__heading">マイリスト</a>
</div>

<div class="divider"></div>

<div class="items-list">
    <div class="item-card">
        <img src="/images/sample.jpg" alt="商品画像" class="item-card__image">
        <p class="item-card__name">商品名</p>
    </div>
</div>
@endsection

