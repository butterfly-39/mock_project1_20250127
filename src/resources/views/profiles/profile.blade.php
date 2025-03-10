@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/profile.css') }}">
@endsection

@section('content')
<div class="profile-form">
    <h2 class="profile-form__heading">プロフィール設定</h2>
    <div class="profile-form__inner">
        <form class="profile-form__form" action="/mypage/profile" method="post" enctype="multipart/form-data">
        @csrf
            <div class="profile-form__group">
                <div class="profile-form__image-container">
                    <div class="profile-form__image-preview" id="preview">
                        @if(isset($user->profile->image))
                            <img src="{{ asset('storage/' . $user->profile->image) }}" alt="プロフィール画像">
                        @else
                            <div class="profile-form__no-image">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <input class="profile-form__select-btn" type="file" name="image" id="image" accept="image/*" onchange="previewImage(this);">
                    <label class="profile-form__select-label" for="image">画像を選択する</label>
                </div>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="name">ユーザー名</label>
                <input class="profile-form__input" type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
                <p class="profile-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="postal_code">郵便番号</label>
                <input class="profile-form__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->profile->postal_code) }}">
                <p class="profile-form__error-message">
                    @error('postal_code')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="address">住所</label>
                <input class="profile-form__input" type="text" name="address" id="address" value="{{ old('address', $user->profile->address) }}">
                <p class="profile-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                <input class="profile-form__input" type="text" name="building" id="building" value="{{ old('building', $user->profile->building) }}">
            </div>

            <div class="profile-form__btn-group">
                <input class="profile-form__update-btn" type="submit" value="更新する" name="update">
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="プレビュー画像">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

