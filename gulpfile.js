var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');

    mix.scripts([
        'app.js',
        'material.js',
        'ripples.js',
        'admin.js',
        ], 'public/js/app.js');

    mix.scripts([
        'vendor/jquery.min.js',
        'vendor/bootstrap.min.js',
        'vendor/moment.js',
        'vendor/moment-with-locales.js',
        'vendor/highcharts.js',
        'vendor/highcharts-more.js',
        'vendor/exporting.js',
        ], 'public/js/vendor/collection.js');
});
