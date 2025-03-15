@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/address.css') }}">
@endsection

@section('content')
<div class="address-form">
    <h2 class="address-form__heading">住所の変更</h2>
    <div class="address-form__inner">
        <form class="address-form__form" action="/purchase/address/{{ $item->id }}" method="post">
        @csrf
            <div class="address-form__group">
                <label class="address-form__label" for="postal_code">郵便局番号</label>
                <input class="address-form__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code')}}">
                <p class="address-form__error-message">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="address-form__group">
                <label class="address-form__label" for="address">住所</label>
                <input class="address-form__input" type="text" name="address" id="address" value="{{ old('address')}}">
                <p class="address-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="address-form__group">
                <label class="address-form__label" for="building">建物名</label>
                <input class="address-form__input" type="text" name="building" id="building" value="{{ old('building')}}">
            </div>

            <div class="register-form__btn-group">
                <input class="address-form__update-btn btn" type="submit" value="更新する">
            </div>
        </form>
    </div>
</div>
@endsection

