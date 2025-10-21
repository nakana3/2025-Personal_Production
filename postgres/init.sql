-- テーブル構造を変更する場合や手動でテーブルを削除した際に既存データを削除するため
DROP TABLE IF EXISTS "UserEvents";
DROP TABLE IF EXISTS "UserProfiles";
DROP TABLE IF EXISTS "Events";
DROP TABLE IF EXISTS "TagDictionary";

-- 1. Events テーブル
CREATE TABLE "Events" (
    event_id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(512) NOT NULL UNIQUE,
    source VARCHAR(50) NOT NULL,
    price INTEGER NOT NULL DEFAULT 0,
    -- TIMESTAMP WITH TIME ZONE: PostgreSQLの日時型
    start_date TIMESTAMP WITH TIME ZONE NOT NULL, 
    end_date TIMESTAMP WITH TIME ZONE,
    location VARCHAR(255),
    description TEXT,
    -- JSONB型: 高速なJSON検索用
    tech_keywords JSONB, 
    difficulty_tag VARCHAR(50),
    total_interest_count INTEGER NOT NULL DEFAULT 0,
    is_expired BOOLEAN NOT NULL DEFAULT FALSE,
    -- NOW(): PostgreSQLの日時関数
    created_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW()
);

-- 2. TagDictionary テーブル
CREATE TABLE "TagDictionary" (
    -- SERIAL: PostgreSQLでの自動採番（INT AUTO_INCREMENTに相当）
    id SERIAL PRIMARY KEY,
    keyword VARCHAR(100) NOT NULL UNIQUE,
    tag_name VARCHAR(100) NOT NULL,
    category VARCHAR(50)
);

-- ユーザー関連テーブルの定義（簡略版）
CREATE TABLE "UserProfiles" (
    user_id VARCHAR(255) PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL,
    user_keywords_map JSONB, 
    user_level VARCHAR(50)
);


-- 検証用初期データ (Python, Reactの関連キーワードを登録)
INSERT INTO "TagDictionary" (keyword, tag_name, category) VALUES
('Python3', 'Python', 'Backend'),
('パイソン', 'Python', 'Backend'),
('React.js', 'React', 'Frontend'),
('NextJS', 'Next.js', 'Frontend');