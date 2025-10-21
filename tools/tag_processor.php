<?php
// =========================================================
// DB接続処理 (event_collector.php と同様)
// =========================================================
$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "✅ DB接続に成功しました。\n";
} catch (\PDOException $e) {
    die("❌ DB接続エラー: " . $e->getMessage());
}

// =========================================================
// 技術検証 ステップ2: 辞書ベースのタグ付け
// = =======================================================

// 1. TagDictionary から全てのキーワードを取得
$tags_stmt = $pdo->query("SELECT keyword, tag_name FROM TagDictionary");
$dictionary = [];
while ($row = $tags_stmt->fetch()) {
    // 検索キーワード => 標準タグ名 の形式で辞書を作成
    $dictionary[$row['keyword']] = $row['tag_name'];
}
echo "✅ 辞書に " . count($dictionary) . " 個のキーワードをロードしました。\n";

// 2. Events テーブルからタグ付け対象のイベントを取得
// 今回は、全てのイベントを取得します
$events_stmt = $pdo->query("SELECT event_id, title FROM Events");
$events = $events_stmt->fetchAll();
echo "➡️ タグ付け対象のイベント " . count($events) . " 件を処理します。\n";

// 3. イベントごとにタグを付与するロジック
$update_stmt = $pdo->prepare("
    UPDATE Events SET tech_keywords = :keywords_json, updated_at = NOW() WHERE event_id = :event_id
");

foreach ($events as $event) {
    $found_tags = [];
    $text_to_search = $event['title']; // + " " + $event['description']; // 説明文も検索対象にできます

    foreach ($dictionary as $keyword => $tag_name) {
        // 【タグ付けロジックの検証ポイント】
        // strpos() でキーワードが含まれているかチェック
        // mb_stripos() を使って大文字・小文字を区別せず、日本語対応も強化できます。
        if (mb_stripos($text_to_search, $keyword) !== false) {
            // 重複を防ぐため、標準タグ名 (tag_name) で記録
            $found_tags[$tag_name] = true;
        }
    }

    if (!empty($found_tags)) {
        $final_tags = array_keys($found_tags);
        $keywords_json = json_encode($final_tags, JSON_UNESCAPED_UNICODE);

        try {
            $update_stmt->execute([
                ':keywords_json' => $keywords_json,
                ':event_id' => $event['event_id']
            ]);
            echo "   -> タグ付与成功 (ID: {$event['event_id']}): " . implode(', ', $final_tags) . "\n";
        } catch (PDOException $e) {
            echo "   -> タグ付与失敗 (ID: {$event['event_id']}): " . $e->getMessage() . "\n";
        }
    } else {
        echo "   -> タグなし (ID: {$event['event_id']})\n";
    }
}

echo "✅ タグ付け処理が完了しました。\n";
