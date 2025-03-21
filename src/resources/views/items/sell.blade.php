@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/sell.css') }}">
@endsection

@section('content')
<div class="sell-form">
    <h2 class="sell-form__heading">商品の出品</h2>
    <div class="sell-form__inner">
        <form class="sell-form__form" action="/sell" method="post" enctype="multipart/form-data">
        @csrf
            <div class="sell-form__group">
                <label class="sell-form__label">商品画像</label>
                <div class="sell-form__image-container">
                    <div class="sell-form__image-preview" id="preview">
                        @if(isset($item->image))
                            <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                        @else
                            <div class="sell-form__no-image">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <input class="sell-form__select-btn" type="file" name="image" id="image" accept="image/*" onchange="previewImage(this);">
                    <label class="sell-form__select-label" for="image">画像を選択する</label>
                </div>
            </div>
            <p class="sell-form__error-message">
                @error('image')
                {{ $message }}
                @enderror
            </p>

            <div class="sell-form__group">
                <h3 class="sell-form__sub-heading">商品の詳細</h3>
                <div class="divider"></div>

                <label class="sell-form__label" for="category">カテゴリー</label>
                <div class="sell-form__category-buttons">
                    @foreach($categories as $category)
                        <div class="sell-form__category-item">
                            <input type="checkbox" name="category[]"
                                id="category_{{ $category->id }}"
                                value="{{ $category->id }}"
                                {{ is_array(old('category')) && in_array($category->id, old('category')) ? 'checked' : '' }}
                                class="sell-form__category-input">
                            <label for="category_{{ $category->id }}" class="sell-form__category-label">
                                {{ $category->category }}
                            </label>
                        </div>
                    @endforeach
                </div>
                <p class="sell-form__error-message">
                    @error('category')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="sell-form__group">
                <label class="sell-form__label" for="condition">商品の状態</label>
                <select class="sell-form__input" name="condition_id" id="condition">
                    <option value="">選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                            {{ $condition->condition }}
                        </option>
                    @endforeach
                </select>
                <p class="sell-form__error-message">
                    @error('condition_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="sell-form__group">
                <h3 class="sell-form__sub-heading">商品名と説明</h3>
                <div class="divider"></div>

                <label class="sell-form__label" for="name">商品名</label>
                <input class="sell-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
                <p class="sell-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="sell-form__group">
                <label class="sell-form__label" for="brand_name">ブランド名</label>
                <input class="sell-form__input" type="text" name="brand_name" id="brand_name" value="{{ old('brand_name') }}">
                <p class="sell-form__error-message">
                    @error('brand_name')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="sell-form__group">
                <label class="sell-form__label" for="description">商品説明</label>
                <textarea class="sell-form__input" name="description" id="description">{{ old('description') }}</textarea>
                <p class="sell-form__error-message">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="sell-form__group">
                <label class="sell-form__label" for="price">販売価格</label>
                <input class="sell-form__input sell-form__input--price" type="text" name="price" id="price" value="{{ old('price') }}">
                <p class="sell-form__error-message">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="sell-form__btn-group">
                <input class="sell-form__update-btn" type="submit" value="出品する" name="update">
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
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                // キャンバスを作成して画像をリサイズ
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                
                // 最大サイズを1200pxに設定
                const MAX_SIZE = 1200;
                if (width > height && width > MAX_SIZE) {
                    height = Math.round((height * MAX_SIZE) / width);
                    width = MAX_SIZE;
                } else if (height > MAX_SIZE) {
                    width = Math.round((width * MAX_SIZE) / height);
                    height = MAX_SIZE;
                }
                
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                // 圧縮した画像をプレビュー表示
                const compressedDataUrl = canvas.toDataURL('image/jpeg', 0.7);
                preview.innerHTML = `<img src="${compressedDataUrl}" alt="商品画像">`;
                
                // 圧縮した画像データをFileに変換
                canvas.toBlob(function(blob) {
                    const compressedFile = new File([blob], file.name, {
                        type: 'image/jpeg',
                        lastModified: new Date().getTime()
                    });
                    
                    // 元のinput[type=file]の値を更新
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    input.files = dataTransfer.files;
                }, 'image/jpeg', 0.7);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection

