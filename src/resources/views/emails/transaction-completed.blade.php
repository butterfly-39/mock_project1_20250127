<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>取引完了のお知らせ</title>
    <link rel="stylesheet" href="{{ asset('css/emails/transaction-completed.css') }}">
</head>
<body>
    <div class="header">
        <h1>🎉 取引完了のお知らせ</h1>
    </div>

    <div class="content">
        <p>{{ $seller->name }}様</p>

        <p>お疲れ様です。<br>
        商品「{{ $item->name }}」の取引が完了いたしました。</p>

        <div class="item-info">
            <h3>取引商品の詳細</h3>
            <p><strong>商品名:</strong> {{ $item->name }}</p>
            <p><strong>価格:</strong> ¥{{ number_format($item->price) }}</p>
            <p><strong>購入者:</strong> {{ $buyer->name }}様</p>
            <p><strong>取引完了日時:</strong> {{ now()->format('Y年m月d日 H:i') }}</p>
        </div>

        <p>購入者様による評価が完了し、取引が正式に完了いたしました。<br>
        お疲れ様でした！</p>

        <p>今後ともよろしくお願いいたします。</p>
    </div>

    <div class="footer">
        <p>※ このメールは自動送信されています。<br>
        返信はできませんのでご了承ください。</p>
        <p>© {{ date('Y') }} マーケットプレイス</p>
    </div>
</body>
</html>