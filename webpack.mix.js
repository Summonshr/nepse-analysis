let mix = require('laravel-mix');
require('laravel-mix-tailwind');
mix.js('resources/assets/js/app.js','public/js')
   .less('resources/assets/less/app.less', 'public/css')
   .tailwind()
   .browserSync('http://nepse-scraper.test');