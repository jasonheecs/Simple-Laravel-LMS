var elixir = require('laravel-elixir');
var requireDir = require('require-dir');

// load in extra gulp tasks in gulp folder
requireDir('./gulp/tasks', {recurse:true});

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
    mix.task('scsslint', 'resources/assets/sass/**/*.{sass,scss}');
    mix.task('jshint', 'resources/assets/js/*.js');
    mix.sass('app.scss');
    mix.browserify('main.js', 'public/js/app.js');

    if(elixir.config.production) { // only run this tasks when production flag is present
        mix.version(['css/app.css', 'js/app.js']);
    }
    
    mix.browserSync({
        proxy: '192.168.10.10'
    });
});
