<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>取引完了のお知らせ</title>
    <style>
        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'Hiragino Sans', Meiryo, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .item-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin-top: 30px;
        }
    </style>
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