var elixir = require('laravel-elixir'),
    gulp = require('gulp'),
    compass = require('gulp-compass'),
    plumber = require('gulp-plumber'),
    _ = require('underscore');

var config = elixir.config;

/**
 * Prep the Gulp src and output paths.
 *
 * @param  {string|Array} src
 * @param  {string|null}  output
 * @return {GulpPaths}
 */
var prepGulpPaths = function (src, output) {
    return new elixir.GulpPaths()
        .src(src, config.get('assets.css.sass.folder'))
        .output(output || config.get('public.css.outputFolder'), 'app.css');
};

config.compass = {
    imgDir: 'img'
};

elixir.extend('compass', function (src, output, imgDir) {

    var paths = prepGulpPaths(src, output);

    new elixir.Task('compass', function () {
        return gulp.src([paths.src.path])
            .pipe(plumber({
                errorHandler: function (error) {
                    console.log(error.message);
                    this.emit('end');
                }
            }))
            .pipe(compass({
                css: paths.output.baseDir,
                sass: paths.src.baseDir,
                image: imgDir || config.get('public.compass.imgDir'),
                sourcemap: true
            }))
            .on('error', function (e) {
                new elixir.Notification().error(e, 'Compass Compilation Failed');

                this.emit('end');
            })
            .pipe(new elixir.Notification('Compass Compiled!'))
    })
        .watch(paths.src.baseDir + '/**/*.+(sass|scss)')
    ;
});

elixir(function (mix) {
    mix.compass('app.scss');
    mix.scripts(
        ['./bower_components/jquery/dist/jquery.js', 'app.js'],
        'public/js/app.js'
    );
});
