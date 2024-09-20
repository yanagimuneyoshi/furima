# coachtechフリマ

### 概要
このプロジェクトは、coachtechブランドのアイテムを出品するための独自のフリマアプリです。

### 環境構築
- **開発言語**: PHP
- **フレームワーク**: Laravel
- **データベース**: MySQL
- **バージョン管理**: Docker, GitHub


### Dockerのセットアップ
1. git clone <リポジトリのURL>
2. docker-compose up -d --build


### Laravel環境構築

1. docker-compose exec php bash
2. composer install
3. .env.exampleファイルから.envを作成し、環境変数を変更
4. php artisan key:generate
5. php artisan migrate


### AWS構築
  - **ストレージ**: S3を利用して商品の画像を保存します。
  - **バックエンド**: EC2を利用してアプリケーションをホストします。
  - **データベース**: RDSを利用してMySQLデータベースをホスティングします。


### 本番環境へのデプロイ手順

1. **準備**:
   - 本番環境のサーバーにSSHで接続します。
   - 必要な環境変数を設定します。

2. **コードの取得**:
  - GitHubから最新のコードをクローンまたはプルします。
  ```bash
  git pull origin main
  ```

3. **依存関係のインストール**:

  - Composerを使用して依存関係をインストールします。
  ```bash
  composer install --no-dev
  ```

4. **マイグレーションの実行**:

  - データベースのマイグレーションを実行します。
  ```bash
  php artisan migrate --force
  ```

5. **キャッシュのクリア**:

  - アプリケーションのキャッシュをクリアし、設定をキャッシュします。
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

6. **サーバーの再起動**:

 - 必要に応じて、WebサーバーやPHP-FPMを再起動します。
  ```bash
  sudo systemctl restart nginx
  sudo systemctl restart php7.4-fpm
  ```


## テスト
  - テストコードはPHPUnitを使用して作成されています。テストを実行するには、以下のコマンドを使用します。
    vendor/bin/phpunit

## URL
   * 環境開発:http://localhost/

   * 本番環境:http://44.200.200.136/



## 目標
  - 初年度でユーザー数1000人を達成することを目指します。

## ターゲットユーザー
  - 10～30代の社会人を対象としています。

## リリース
  - 4ヶ月後を予定しています。

