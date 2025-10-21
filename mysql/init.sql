CREATE DATABASE IF NOT EXISTS keep_info_db;
USE keep_info_db;

-- 1. Events テーブル
CREATE TABLE IF NOT EXISTS Events (
    event_id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(512) NOT NULL UNIQUE,
    source VARCHAR(50) NOT NULL,
    price INT NOT NULL DEFAULT 0,
    start_date DATETIME NOT NULL,
    end_date DATETIME,
    location VARCHAR(255),
    description TEXT,
    tech_keywords JSON,
    difficulty_tag VARCHAR(50),
    total_interest_count INT NOT NULL DEFAULT 0,
    is_expired BOOLEAN NOT NULL DEFAULT FALSE,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);

-- 2. TagDictionary テーブル
CREATE TABLE IF NOT EXISTS TagDictionary (
    id INT AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(100) NOT NULL UNIQUE,
    tag_name VARCHAR(100) NOT NULL,
    category VARCHAR(50)
);

-- 検証用初期データ (Python, Reactの関連キーワードを登録)
INSERT INTO TagDictionary (keyword, tag_name, category) VALUES
('Python3', 'Python', 'Backend'),
('パイソン', 'Python', 'Backend'),
('React.js', 'React', 'Frontend'),
('NextJS', 'Next.js', 'Frontend');