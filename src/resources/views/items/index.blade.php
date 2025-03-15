@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="items-form">
    <a href="/" class="items-form__heading {{ request()->get('tab') === 'recommended' || !request()->has('tab') ? 'active' : '' }}">おすすめ</a>
    <a href="/?tab=mylist" class="items-form__heading {{ request()->get('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>

<div class="divider"></div>

<div class="items-list">
    @foreach($items as $item)
        <div class="item-card">
            @if($item->status === 'sold' || $item->orders->isNotEmpty())
                <div class="item-card__sold">SOLD</div>
            @endif
            <a href="/item/{{ $item->id }}">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像" class="item-card__image">
            @else
                <img src="/images/sample.jpg" alt="商品画像" class="item-card__image">
            @endif
            </a>
            <p class="item-card__name">{{ $item->name }}</p>
        </div>
    @endforeach
</div>
@endsection

