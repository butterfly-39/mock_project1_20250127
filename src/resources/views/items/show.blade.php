@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
<div class="item-show">
    <form action="/purchase/{{ $item->id }}" method="post">
        @csrf
        <button type="submit" class="item-show__purchase-btn">
            購入手続きへ
        </button>
    </form>
    <div class="item-show__inner">
        <div class="item-show__image">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" data-item-id="{{ $item->id }}">
            @else
                <img src="{{ asset('images/no-image.png') }}" alt="画像なし" data-item-id="{{ $item->id }}">
            @endif
        </div>
        <h2 class="item-show__name">{{ $item->name }}</h2>
        <p class="item-show__brand">{{ $item->brand_name }}</p>
        <p class="item-show__price">¥{{ $item->price }}（税込）</p>
    </div>
</div>
@endsection