const mix = require('laravel-mix');

mix.ts('resources/assets/js/app.ts', 'public/assets');
mix.sass('resources/assets/css/app.scss', 'public/assets');
