@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-form">
        <h2 class="login-form__heading">ログイン</h2>
    </div>
        <div class="login-form__inner">
        <form class="login-form__form" action="/login" method="post">
            @csrf
            <div class="login-form__group">
                <label class="login-form__label" for="name">ユーザー名/メールアドレス</label>
                <input class="login-form__input" type="text" name="name" id="name">
                <p class="login-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="login-form__group">
                <label class="login-form__label" for="password">パスワード</label>
                <input class="login-form__input" type="password" name="password" id="password">
                <p class="login-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="login-form__btn-group">
                <input class="login-form__login-btn" type="submit" value="ログイン" name="login">
                <input class="login-form__register-btn" type="submit" value="会員登録はこちら" name="register">
            </div>
        </form>
    </div>
@endsection
