var app_url = 'http://dev.env/smartbots';

var
    elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    htmlmin = require('gulp-htmlmin'),
    gulpif = require('gulp-if');

var vendor_dir = 'resources/assets/vendor/',
    libs_dir = 'public/libs/',
    js_dir = 'public/js/',
    css_dir = 'public/css/';

var less = { // LESS file to compile => css
        'components.less' : 'components.css',
        'core.less' : 'core.css',
        'libs.less' : 'libs.css',
        'responsive.less' : 'responsive.css'
    },

    js = { // JS file vendored => libs
        'jquery/dist/jquery.js' : 'jquery/jquery.js', // 2
        'jquery-legacy/dist/jquery.js' : 'jquery-legacy/jquery.js', // 1
        'jquery-modern/dist/jquery.js' : 'jquery-modern/jquery.js', // 3
        'bootstrap/dist/js/bootstrap.js' : 'bootstrap/js/bootstrap.js',
        'jquery.scrollTo/jquery.scrollTo.js' : 'jquery.scrollTo/jquery.scrollTo.js',
        'jquery.nicescroll/jquery.nicescroll.min.js' : 'jquery.nicescroll/jquery.nicescroll.js',
        'slimscroll/jquery.slimscroll.js' : 'slimscroll/jquery.slimscroll.js',
        'fastclick/lib/fastclick.js' : 'fastclick/fastclick.js',
        'blockUI/jquery.blockUI.js' : 'blockUI/jquery.blockUI.js',
        'Waves/dist/waves.js' : 'Waves/waves.js',
        'wow/dist/wow.js' : 'wow/wow.js',
        'sweetalert/dist/sweetalert.min.js' : 'sweetalert/sweetalert.js',
        'multiselect/js/jquery.multi-select.js' : 'multiselect/js/jquery.multi-select.js',
        'typeahead.js/dist/typeahead.bundle.js' : 'typeahead.js/typeahead.bundle.js',
        'handlebars/handlebars.js' : 'handlebars/handlebars.js',
        'moment/moment.js' : 'moment/moment.js',
        'moment/locale/vi.js' : 'moment/vi.js',
        'quicksearch/jquery.quicksearch.js' : 'quicksearch/jquery.quicksearch.js',
        'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js' : 'bootstrap-datetimepicker/bootstrap-datetimepicker.js',
        'bootstrap-select/dist/js/bootstrap-select.js' : 'bootstrap-select/bootstrap-select.js',
        'raven-js/dist/raven.js' : 'raven-js/raven.js',
        'amcharts3/amcharts/amchart.js' : 'amcharts/amcharts.js',
        'amcharts3/amcharts/amchart.js' : 'amcharts/serial.js',
        'socket.io-client/socket.io.js' : 'socket.io/socket.io.js',
        'intl-tel-input/build/js/intlTelInput.js' : 'intl-tel-input/js/intlTelInput.js'
    },

    css = { // CSS file vendored => libs
        'bootstrap/dist/css/bootstrap.css' : 'bootstrap/css/bootstrap.css',
        'font-awesome/css/font-awesome.css' : 'font-awesome/css/font-awesome.css',
        'themify-icons/css/themify-icons.css' : 'themify-icons/css/themify-icons.css',
        'Waves/dist/waves.css' : 'Waves/waves.css',
        'sweetalert/dist/sweetalert.css' : 'sweetalert/sweetalert.css',
        'animate.css/animate.css' : 'animate.css/animate.css',
        'multiselect/css/multi-select.css' : 'multiselect/css/multi-select.css',
        'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css' : 'bootstrap-datetimepicker/bootstrap-datetimepicker.css',
        'bootstrap-select/dist/css/bootstrap-select.css' : 'bootstrap-select/bootstrap-select.css',
        'intl-tel-input/build/css/intlTelInput.css' : 'intl-tel-input/css/intlTelInput.css'
    },

    assets = { // Assets file & folder vendored => libs
        'bootstrap/dist/fonts' : 'bootstrap/fonts',
        'font-awesome/fonts' : 'font-awesome/fonts',
        'themify-icons/fonts' : 'themify-icons/fonts',
        'multiselect/img' : 'multiselect/img',
        'intl-tel-input/build/img' : 'intl-tel-input/img'
    },

    jsx = { // JS (not vendored) files => js
        'jquery.core.js' : 'jquery.core.js',
        'jquery.app.js' : 'jquery.app.js',
        'jquery.custom.js' : 'jquery.custom.js'
    };

elixir.extend('compress', function() {
    new elixir.Task('compress', function() {
        return gulp.src('./storage/framework/views/*')
            .pipe(htmlmin({
                collapseWhitespace:    true,
                removeAttributeQuotes: true,
                removeComments:        true,
                minifyJS:              true,
            }))
            .pipe(gulp.dest('./storage/framework/views/'));
    })
    .watch('./storage/framework/views/*');
});

elixir(function(mix) {

    // mix.compress();

    // for(var key in less) {
    //     mix.less(key, css_dir+less[key], vendor_dir);
    // }

    // for(var key in js) {
    //     mix.scripts(key, libs_dir+js[key], vendor_dir);
    // }

    for(var key in jsx) {
        mix.scripts(key, js_dir+jsx[key]);
    }

    // for(var key in css) {
    //     mix.styles(key, libs_dir+css[key], vendor_dir);
    // }

    // for (var key in assets) {
    //     mix.copy(vendor_dir+key, libs_dir+assets[key]);
    // }

    mix.browserSync({
        // online: false,
        notify: false,
        open: false,
        proxy: app_url,
        ghostMode: {
            clicks: true,
            forms: true,
            scroll: true
        }
    });
});
