const mix = require('laravel-mix');
const config = require('./webpack.config');

mix.webpackConfig(config);
mix.ts('resources/assets/js/app.ts', 'public/assets');
mix.sass('resources/assets/css/app.scss', 'public/assets');
