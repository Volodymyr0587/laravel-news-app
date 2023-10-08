git clone https://github.com/Volodymyr0587/laravel-news-app

cd laravel-news-app

composer install

cp .env.example .env

php artisan key:generate

create mysql db news_app

php artisan migrate

npm install

npm run dev

php artisan serve
