var gulp = require('gulp');
var concat = require('gulp-concat');
var sourcemaps = require('gulp-sourcemaps');
var minifyCSS = require('gulp-minify-css');
var uglify = require('gulp-uglify');
var autoprefixer = require('gulp-autoprefixer');
var Fiber = require('fibers');
var sass = require('gulp-sass-no-nodesass');
var ngAnnotate = require('gulp-ng-annotate');
sass.compiler = require('sass');

function angular(){
  return gulp.src([
    'public/assets/application/**/module.js',
    'public/assets/application/models/**/*.js',
    'public/assets/application/controllers/**/*.js',
    'public/assets/application/**/*.js'
  ], {base: 'public/assets/application'})
    .pipe(sourcemaps.init({sourceRoot: 'public/assets/js'}))
    .pipe(concat('app.js'))
    .pipe(ngAnnotate())
    .pipe(uglify())
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('public/assets/js'));
}


function vendor() {

    return gulp.src([
        'public/assets/vendor/jquery/dist/jquery.js',
        'public/assets/vendor/bootstrap-sass/assets/javascripts/bootstrap.js',
        'public/assets/vendor/masonry/dist/masonry.pkgd.js',
        'public/assets/vendor/angular/angular.js',
        'public/assets/vendor/angular-resource/angular-resource.js',
        'public/assets/vendor/angular-route/angular-route.js',
        'public/assets/vendor/angular-masonry/angular-masonry.js'
    ], {base: 'public/assets'})
        .pipe(sourcemaps.init({sourceRoot: 'public/assets/js'}))
        .pipe(concat('libraries.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('public/assets/js'));


}

function css() {
    return gulp.src('public/assets/sass/**/*.scss', {base: 'public/assets/sass'})
        .pipe(sourcemaps.init({sourceRoot: 'public/assets/css'}))
        .pipe(sass({fiber: Fiber}).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(minifyCSS({rebase: false}))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('public/assets/css'));
}

function watch() {
  gulp.watch('public/assets/sass/**/*.scss', css);
  gulp.watch('public/assets/application/**/*.js', angular);
}

var build = gulp.series(css, vendor, angular);
var serve = gulp.series(watch, build);

/*
 * You can use CommonJS `exports` module notation to declare tasks
 */
exports.css = css;
exports.vendor = vendor;
exports.angular = angular;
exports.watch = watch;
exports.build = build;
/*
 * Define default task that can be called by just running `gulp` from cli
 */
exports.default = serve;
