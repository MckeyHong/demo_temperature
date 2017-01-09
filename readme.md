# Demo


```
// 將專案 clone 至開發環境
git clone git@github.com:MckeyHong/demo_temperature.git

// 進到專案目錄下
cd demo_temperature

// 將 .env.example 範例設定檔複製一份產生 .env 設定檔，並進行設定檔資料設定
cp .env.example .env

// 使用 composer 安裝套件
composer install

// 產生 key
php artisan key:generate

// 產生資料表&初始資料(先確認是否有建置 temperature 資料庫)
php artisan migrate
php artisan db:seed

// 安裝樣版
php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\ServiceProvider" --tag=assets

// 本機運作
php -S localhost:1988 -t public

```