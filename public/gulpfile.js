var gulp = require("gulp"),
    gutil = require("gulp-util"),
    changed = require("gulp-changed"),
    imagemin = require("gulp-imagemin"),
    concat = require("gulp-concat"),
    uglify = require("gulp-uglify"),
    stripDebug = require("gulp-strip-debug"),
    sass = require("gulp-sass"),
    autoprefixer = require("gulp-autoprefixer"),
    minifycss = require("gulp-minify-css"),
    rename = require("gulp-rename"),
    del = require('del');

var paths = {
    bower: "./bower_components",
    dist: "./assets",
    js: {
        src: "./js",
        dest: "./assets/js"
    },
    css: {
        src: "./css",
        dest: "./assets/css"
    },
    images: {
        src: "./images",
        dest: "./images"
    }
};

gulp.task("default", ["images", "scripts", "styles"]);

gulp.task("clean", function (cb) {
    del(
        [
            paths.assets.js.dest + "/**",
            paths.assets.css.dest + "/**",
            paths.assets.images.dest + "/**"
        ]
        , cb);
});

gulp.task("watch", ["default"], function () {
    gulp.watch(paths.js.src + "/**/*.js", ["scripts"]);
    gulp.watch(paths.images.src + "/**/*", ["images"]);
    gulp.watch(paths.css.src + "/**/*.scss", ["styles"]);
});

gulp.task("images", function () {
    return gulp.src(paths.images.src + "/*")
        .pipe(changed(paths.images.dest))
        .pipe(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true }))
        .pipe(gulp.dest(paths.images.dest));
});

gulp.task("scripts", function () {
    // Compile scripts, will use browserify...
});

gulp.task("styles", function () {
    return gulp.src([paths.bower + "/normalize-css/normalize.css", paths.css.src + "/main.scss"])
        .pipe(sass({
            includePaths: [
                "./app/bower_components"
            ]
        }))
        .pipe(autoprefixer("last 2 version", "safari 5", "ie 8", "ie 9", "opera 12.1", "ios 6", "android 4"))
        .pipe(concat("main.css"))
        .pipe(gulp.dest(paths.css.dest))
        .pipe(rename({suffix: ".min"}))
        .pipe(minifycss())
        .pipe(gulp.dest(paths.css.dest));
});
