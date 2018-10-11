let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
   
   
mix.copyDirectory('resources/assets/img', 'public/img');
mix.copyDirectory('resources/assets/js/swiper', 'public/js/swiper');
//mix.copyDirectory('resources/assets/js/main.js', 'public/js/');


mix.browserSync({
    proxy: 'joryashop.test',
    open: false,
    scrollProportionally: false,
    watchTask: true,
    notify: false,
    files: [
            'public/css/app.css', 
		    'public/js/*.js', 
		    'resources/views/**/*.php'
		   ]
//  ui: false,
});

