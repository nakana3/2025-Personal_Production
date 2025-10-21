<?php
// =========================================================
// DB接続処理 (リトライロジックを追加)
// =========================================================
$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenV('DB_PASS');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// 【🚨追加】接続リトライ設定
$max_retries = 10;
$retry_count = 0;
$connected = false;

while ($retry_count < $max_retries && !$connected) {
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        $connected = true;
        echo "✅ DB接続に成功しました (リトライ回数: {$retry_count})。\n";
    } catch (\PDOException $e) {
        if ($retry_count >= $max_retries - 1) {
            // 最終試行で失敗した場合
            die("❌ DB接続エラー: リトライ上限に達しました - " . $e->getMessage());
        }
        // 接続拒否の場合、少し待ってから再試行
        echo "⚠️ DB接続を拒否されました。500ms待機して再試行します... ({$retry_count}回目)\n";
        usleep(500000); // 500ミリ秒待機
        $retry_count++;
    }
}

if (!$connected) {
    die("❌ 致命的なエラー: DBに接続できませんでした。\n");
}

// =========================================================
// 技術検証 ステップ1: APIからの情報取得とDB格納
// ... (この下は既存のAPI処理が続く)