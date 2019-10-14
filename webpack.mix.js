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

// PC 站
mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');

// 2019-01-22
// mix.copyDirectory('resources/assets/js/admin', 'public/js/admin');
// 2019-01-22

mix.copyDirectory('resources/assets/img', 'public/img');
mix.copyDirectory('resources/assets/js/swiper', 'public/js/swiper');
mix.copyDirectory('resources/assets/js/jquery.validate.min.js', 'public/js/jquery.validate.min.js');
mix.copyDirectory('resources/assets/js/layer', 'public/js/layer');
mix.copyDirectory('resources/assets/js/shareJS', 'public/js/shareJS');
mix.copyDirectory('resources/assets/js/lord', 'public/js/lord');
mix.copyDirectory('resources/assets/js/scrollReveal', 'public/js/scrollReveal');
mix.copyDirectory('resources/assets/js/slick', 'public/js/slick');
mix.copyDirectory('resources/assets/js/jqueryCountup', 'public/js/jqueryCountup');

//mix.copyDirectory('resources/assets/js/main.js', 'public/js/');

// Mobile 站
// mix.js('resources/assets/static_m/js/app.js', 'public/static_m/js')
//     .sass('resources/assets/static_m/sass/app.scss', 'public/static_m/css');
//
// mix.copyDirectory('resources/assets/static_m/img', 'public/static_m/img');
// mix.copyDirectory('resources/assets/static_m/js/layer_mobile', 'public/static_m/js/layer_mobile');
// mix.copyDirectory('resources/assets/static_m/js/raty', 'public/static_m/js/raty');
// mix.copyDirectory('resources/assets/static_m/js/jquery.countdown-2.2.0', 'public/static_m/js/jquery.countdown-2.2.0');
// mix.copyDirectory('resources/assets/static_m/js/animate', 'public/static_m/js/animate');
// mix.copyDirectory('resources/assets/static_m/js/dropload', 'public/static_m/js/dropload');
// mix.copyDirectory('resources/assets/static_m/js/clipboard', 'public/static_m/js/clipboard');


// dev
// mix.browserSync({
//     proxy: 'joryashop.test',
//     open: false,
//     scrollProportionally: false,
//     watchTask: true,
//     notify: false,
//     files: [
//         'public/css/app.css',
//         'public/js/*.js',
//         'resources/views/**/*.php'
//     ]
// //  ui: false,
// });

