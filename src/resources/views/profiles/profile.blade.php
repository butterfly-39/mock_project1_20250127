@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/profile.css') }}">
@endsection

@section('content')
<div class="profile-form">
    <h2 class="profile-form__heading">プロフィール</h2>
    <div class="profile-form__inner">
        <form class="profile-form__form" action="/profile" method="post">
        @csrf
            <div class="profile-form__group">
                <label class="profile-form__label" for="name">ユーザー名</label>
                <input class="profile-form__input" type="text" name="name" id="name">
                <p class="profile-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="postal_code">郵便番号</label>
                <input class="profile-form__input" type="text" name="postal_code" id="postal_code">
                <p class="profile-form__error-message">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="address">住所</label>
                <input class="profile-form__input" type="text" name="address" id="address">
                <p class="profile-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                <input class="profile-form__input" type="text" name="building" id="building">
                <p class="profile-form__error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__btn-group">
                <input class="profile-form__update-btn btn" type="submit" value="更新する">
            </div>
        </form>
    </div>
</div>
@endsection

