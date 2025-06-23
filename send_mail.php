<?php
session_start();
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// CSRFトークンチェック
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    exit('不正なリクエストです（CSRF）');
}
unset($_SESSION['csrf_token']); // 使い捨て

// 入力値取得＆サニタイズ
function sanitize($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

$company = sanitize($_POST['company'] ?? '');
$name    = sanitize($_POST['name'] ?? '');
$tel     = sanitize($_POST['tel'] ?? '');
$email   = sanitize($_POST['email'] ?? '');
$message = sanitize($_POST['message'] ?? '');

// 必須項目チェック
if (empty($company) || empty($name) || empty($email) || empty($message)) {
    exit('必須項目が入力されていません。');
}

// 電話番号バリデーション（数字とハイフンのみ許可）
if (!empty($tel) && !preg_match('/^[0-9\-]+$/', $tel)) {
    exit('電話番号の形式が正しくありません。');
}

// メールヘッダーインジェクション対策
if (preg_match('/[\r\n]/', $email)) {
    exit('不正な入力が検出されました。');
}

// メールアドレス形式チェック
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('メールアドレスの形式が正しくありません。');
}

// メール送信先
$to = 'escape.1021@outlook.com';

// 件名・本文
$subject = '仕事のお問い合わせがありました';
$body = "会社名: $company\nお名前: $name\n電話番号: $tel\nメールアドレス: $email\nお問い合わせ内容:\n$message";

// 送信元
$headers = "From: $email";

// メール送信
if(mb_send_mail($to, $subject, $body, $headers)){
    echo "送信が完了しました。";
} else {
    echo "送信に失敗しました。";
}
?>