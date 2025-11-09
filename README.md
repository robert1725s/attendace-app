# 勤怠管理アプリ

## 📋 目次

- [環境構築](#-環境構築)
- [テスト](#-テスト)
- [ER図](#-er図)
- [使用技術](#-使用技術)
- [URL一覧](#-url一覧)

## 🚀 環境構築

### セットアップ手順

```bash
# リポジトリのクローン
git clone git@github.com:robert1725s/attendace-app.git
cd attendance-app

# Docker環境の構築
docker-compose up -d --build

# PHPコンテナに入る
docker-compose exec php bash

# Composerパッケージのインストール
composer install

# 環境変数ファイルの作成
cp .env.example .env

# アプリケーションキーの生成
php artisan key:generate

# データベースのマイグレーション
php artisan migrate

# シーダーの実行
php artisan db:seed
```

### 初期ユーザー

#### 管理者

- メール: `admin@coachtech.com`
- パスワード: `12345678`

#### 一般ユーザー
メール承認済みユーザー
- メール: `verified@coachtech.com`
- パスワード: `12345678`

メール未承認ユーザー
- メール: `unverified@coachtech.com`
- パスワード: `12345678`

## 🧪 テスト

#### テスト実行手順

```bash
# PHPコンテナに入る
docker-compose exec php bash

# 環境変数ファイルの作成
cp .env.testing.example .env.testing

# アプリケーションキーの生成
php artisan key:generate --env=testing

# 全テストを実行
vendor/bin/phpunit

# 特定のテストクラスを実行
vendor/bin/phpunit --filter=ModifyAttendanceByAdminTest

# 特定のテストメソッドを実行
vendor/bin/phpunit --filter=test_all_pending_correction_requests_are_displayed
```

## 📊 ER図

<img width="1092" height="792" alt="Image" src="https://github.com/user-attachments/assets/03244065-b027-461c-840b-734ae2b2fd3d" />

## 🛠 使用技術

### バックエンド

- **PHP** 8.1
- **Laravel** 8.8
- **MySQL** 8.0

### フロントエンド

- **HTML / CSS**
- **JavaScript**

### インフラ・開発環境

- **Docker**
- **nginx** 1.21.1
- **phpMyAdmin**
- **MailHog**（開発環境用メールサーバー）

## 🔗 URL一覧

### 開発環境

| サービス         | URL                   | 説明                   |
| ---------------- | --------------------- | ---------------------- |
| アプリケーション | http://localhost      | メインアプリケーション |
| phpMyAdmin       | http://localhost:8080 | データベース管理       |
| MailHog          | http://localhost:8025 | メール確認（開発環境） |
