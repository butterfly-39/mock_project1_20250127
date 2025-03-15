@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/purchase.css') }}">
@endsection

@section('content')
<div class="item-purchase">
    <div class="item-purchase__inner">
        <form action="/purchase/{{ $item->id }}" method="POST" id="purchase-form">
            @csrf
            <div class="item-purchase__content">
                <!-- 左カラム -->
                <div class="item-purchase__left">
                    <div class="item-purchase__top-content">
                        <div class="item-purchase__image">
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
                                <p class="item-show__price">¥{{ number_format($item->price) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="item-show__purchase">
                        <h3>支払い方法</h3>
                        <div class="payment-method">
                            <select name="payment_method" class="payment-method__select">
                                <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>選択してください</option>
                                <option value="convenience" {{ old('payment_method') == 'convenience' ? 'selected' : '' }}>コンビニ払い</option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>クレジットカード払い</option>
                            </select>
                        </div>
                        @error('payment_method')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="divider"></div>

                    <div class="item-show__shipping">
                        <div class="shipping-header">
                            <h3>配送先</h3>
                            <a href="/purchase/address/{{ $item->id }}" class="shipping-address__change">変更する</a>
                        </div>
                        <div class="shipping-address">
                            <div class="shipping-address__content">
                                <p class="shipping-address__zip">〒{{ $profile->postal_code }}</p>
                                <p class="shipping-address__detail">
                                    {{ $profile->prefecture }}{{ $profile->city }}{{ $profile->address }}</p>
                            </div>
                        </div>
                        @error('shipping-address')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="divider"></div>
                    
                </div>

                <!-- 右カラム -->
                <div class="item-purchase__right">
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <input type="hidden" name="shipping-address" value="{{ $profile->id }}">
                    <div class="purchase-summary">
                        <div class="purchase-summary__row">
                            <span class="purchase-summary__label">商品代金</span>
                            <span class="purchase-summary__price">¥{{ number_format($item->price) }}</span>
                        </div>
                        <div class="purchase-summary__row">
                            <span class="purchase-summary__label">支払い方法</span>
                            <span class="purchase-summary__payment" id="selected-payment">未選択</span>
                        </div>
                    </div>

                    <div class="item-show__purchase-btn">
                        <button type="submit" class="purchase-btn btn" {{ !$profile ? 'disabled' : '' }}>購入する</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('.payment-method__select').addEventListener('change', function() {
    const paymentText = {
        'convenience': 'コンビニ払い',
        'credit_card': 'クレジットカード払い'
    };
    document.getElementById('selected-payment').textContent =
        this.value ? paymentText[this.value] : '未選択';
});
</script>
@endsection

