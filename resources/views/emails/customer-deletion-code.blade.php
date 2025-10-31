<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>得意先削除確認コード</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .code-box {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 5px;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #856404;
        }
        .warning {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            color: #721c24;
        }
        .customer-info {
            background-color: #e7f3ff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>得意先削除確認コード</h2>
        <p>小坂管理システム</p>
    </div>

    <p>得意先データの削除リクエストを受け付けました。</p>

    <div class="customer-info">
        <h3>削除対象の得意先情報</h3>
        <p><strong>得意先コード:</strong> {{ $customer->CustomerCode }}</p>
        @if($customer->BranchCode)
        <p><strong>支店コード:</strong> {{ $customer->BranchCode }}</p>
        @endif
        <p><strong>得意先名:</strong> {{ $customer->CustomerOfficialName1 }}</p>
    </div>

    <div class="code-box">
        <p style="margin: 0 0 10px 0; font-size: 14px;">削除確認コード</p>
        <div class="code">{{ $deletionToken->token }}</div>
        <p style="margin: 10px 0 0 0; font-size: 12px;">有効期限: {{ $deletionToken->expires_at->format('Y年m月d日 H:i') }}</p>
    </div>

    <div class="warning">
        <h3 style="margin-top: 0;">重要な注意事項</h3>
        <ul style="margin: 10px 0; padding-left: 20px;">
            <li>このコードは<strong>5分間のみ有効</strong>です</li>
            <li>コードは一度のみ使用可能です</li>
            <li>削除を実行すると、データは完全に削除され復元できません</li>
            <li>このメールに心当たりがない場合は、システム管理者にお問い合わせください</li>
        </ul>
    </div>

    <p>削除を実行する場合は、システムの削除確認画面で上記のコードを入力してください。</p>

    <div class="footer">
        <p>このメールは小坂管理システムから自動送信されています。</p>
        <p>発行日時: {{ now()->format('Y年m月d日 H:i:s') }}</p>
    </div>
</body>
</html>
