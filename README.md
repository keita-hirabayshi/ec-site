## udemy Laravel講座

## ダウンロード方法

git clone
git clone http://github.com/aokitashipro/
laravel_umarche.git

git clone ブランチを指定してダウンロードする場合
git clone -b ブランチ名 https://github.com/
aokitashipro/laravel_umarche.git

もしうはzipファイルでダウンロードしてください

## インストール方法
cd laravel_umarche
composer install または composer update
npm install
npm run dev
.env.example をコピーして .env ファイルを作成

.envファイルの中の下記をご利用の環境に合わせて変更してください。

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_umarche
DB_USERNAME=umarche
DB_PASSWORD=password123
XAMPP/MAMPまたは他の開発環境でDBを起動した後に


php artisan migrate:fresh --seed

と実行してください。(データベーステーブルとダミーデータが追加されればOK)

最後に php artisan key:generate と入力してキーを生成後、

php artisan serve で簡易サーバーを立ち上げ、表示確認してください。

##　インストール後の実施後

画像のダミーデータは
public/imageフォルダ内に
sample1.jpg 〜 sample6.jpg として
保存しています。

sail artisan storage:link で
storageフォルダにリンク後、

storage/app/public/productsフォルダ内に
保存すると表示されます。
(productsフォルダがない場合は作成してください。)

ショップの画像も表示する場合は、
storage/app/public/shopsフォルダを作成し
画像を保存してください。

## section7の補足

決済テストとしてstripeを利用しています。
必要な場合は .env にstripeの情報を追記してください。
(講座内で解説しています)

## section08の補足

メールのテストとしてmailtrapを利用しています。
必要な場合は .env にstripeの情報を追記してください。
(講座内で解説しています)

メール処理には時間がかかるので、キューを使用してます。

必要な場合は sail artisan queue:workで
ワークを立ち上げて動作確認するようにしてください。