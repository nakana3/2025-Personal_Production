# 🚨 Node.jsのイメージを使用
FROM node:18-alpine

# 作業ディレクトリの設定
WORKDIR /usr/src/app

# package.json と package-lock.json をコピーして依存関係をインストール
# これを最初に行うことで、依存関係が変更されない限り、ビルドが高速化されます
COPY package*.json ./
RUN npm install

# アプリケーションのコードをコピー
COPY . .

# アプリケーションの起動コマンド (package.jsonのstartスクリプトを参照)
CMD [ "npm", "run", "start:dev" ]