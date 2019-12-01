// import modules
const gulp          = require('gulp');
const sass          = require('gulp-sass');
const rename        = require('gulp-rename');
const autoPreFixer  = require('gulp-autoprefixer');
const sourcemaps    = require('gulp-sourcemaps');
const browserify    = require('browserify');
const babelify      = require('babelify');
const source        = require('vinyl-source-stream');
const buffer        = require('vinyl-buffer');
const uglify        = require('gulp-uglify');
const watch         = require('gulp-watch');
const image         = require('gulp-image');
const concat        = require('gulp-concat');
const gulpif        = require('gulp-if');
const notify        = require("gulp-notify");

/* ================ admin assets ================ */
/* Styles path */
const admin_styleSrc        = './src/admin/scss/wp_users_handler-admin.scss';
const admin_styleLibSrc     = 'src/admin/scss/lib/*';
const admin_styleDist       = './admin/assets/css';
const admin_styleLibDist    = 'admin/assets/css/lib';
const admin_styleWatch      = './src/admin/scss/**/*.scss';

/* Scripts path */
const admin_scriptSrc       = 'src/admin/js/';
const admin_scriptLibSrc    = 'src/admin/js/lib/*';
const admin_scriptFiles     = ['wp_users_handler-admin.js'];
const admin_scriptDist      = 'admin/assets/js';
const admin_scriptLibDist   = 'admin/assets/js/lib';
const admin_scriptWatch     = './src/admin/js/**/*.js';
const admin_concat          = [
    admin_scriptSrc + 'wp_users_handler-admin.js'
];

/* Images path */
const admin_imgSrc      = './src/admin/img/**/*';
const admin_imgDist     = './admin/assets/img';


/* ================ public assets ================ */
/* Styles path */
const public_styleSrc       = './src/public/scss/wp_users_handler-public.scss';
const public_styleLibSrc    = 'src/public/scss/lib/*';
const public_styleDist      = './public/assets/css';
const public_styleLibDist   = 'public/assets/css/lib';
const public_styleWatch     = './src/public/scss/**/*.scss';

/* Scripts path */
const public_scriptSrc      = 'src/public/js/';
const public_scriptLibSrc   = 'src/public/js/lib/*';
const public_scriptFiles    = ['all.js'];
const public_scriptDist     = 'public/assets/js';
const public_scriptLibDist  = 'public/assets/js/lib';
const public_scriptWatch    = './src/public/js/**/*.js';
const public_concat         = [
    public_scriptSrc + 'functions.js',
    public_scriptSrc + 'wp_users_handler-public.js'
];

/*  Images path */
const public_imgSrc     = './src/public/img/**/*';
const public_imgDist    = './public/assets/img';


gulp.task('adminStyles', function (cb) {
    gulp.src(admin_styleSrc)
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoPreFixer())
        .pipe(rename({basename: "main", suffix: ".min"}))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(admin_styleDist));
    cb();
});

gulp.task('publicStyles', function (cb) {
    gulp.src(public_styleSrc)
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(autoPreFixer())
        .pipe(rename({basename: "main", suffix: ".min"}))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(public_styleDist));
    cb();
});

gulp.task('adminScripts', function (cb) {
    admin_scriptFiles.map(function (entry) {
        return browserify({
            entries: [admin_scriptSrc + entry]
        })
            .transform(babelify, {presets: ["@babel/preset-env"]})
            .bundle()
            .pipe(source(entry))
            .pipe(rename({basename: "main", suffix: ".min"}))
            .pipe(buffer())
            .pipe(sourcemaps.init())
            .pipe(uglify())
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(admin_scriptDist));
    });
    cb();
});

gulp.task('publicScripts', function (cb) {
    public_scriptFiles.map(function (entry) {
        return browserify({
            entries: [public_scriptSrc + entry]
        })
            .transform(babelify, {presets: ["@babel/preset-env"]})
            .bundle()
            .pipe(source(entry))
            .pipe(rename({basename: "main", suffix: ".min"}))
            .pipe(buffer())
            .pipe(sourcemaps.init({loadMaps: true}))
            .pipe(uglify())
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest(public_scriptDist));
    });
    cb();
});

gulp.task('adminImages', function (cb) {
    gulp.src(admin_imgSrc)
        .pipe(image())
        .pipe(gulp.dest(admin_imgDist));
    cb();
});

gulp.task('publicImages', function (cb) {
    gulp.src(public_imgSrc)
        .pipe(image())
        .pipe(gulp.dest(public_imgDist));
    cb();
});

gulp.task('lib', function (cb) {
    gulp.src(admin_scriptLibSrc)
        .pipe(gulp.dest(admin_scriptLibDist));

    gulp.src(public_scriptLibSrc)
        .pipe(gulp.dest(public_scriptLibDist));

    gulp.src(admin_styleLibSrc)
        .pipe(gulp.dest(admin_styleLibDist));

    cb();

    gulp.src(public_styleLibSrc)
        .pipe(gulp.dest(public_styleLibDist));

    cb();
});

gulp.task('watch', function () {
    watch([admin_styleWatch], gulp.series('adminStyles'));
    watch([public_styleWatch], gulp.series('publicStyles'));
    watch([admin_scriptWatch], gulp.series('adminScripts'));
    watch([public_scriptWatch], gulp.series('publicScripts'));
    // watch([admin_imgSrc], gulp.series('adminImages'));
    // watch([public_imgSrc], gulp.series('publicImages'));
});

gulp.task('default', gulp.parallel('adminStyles', 'publicStyles', 'adminScripts', 'publicScripts', 'adminImages', 'publicImages', 'lib', 'watch'));


