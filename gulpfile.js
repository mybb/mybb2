"use strict";

const gulp = require("gulp"),
    del = require("del"),
    elixir = require('laravel-elixir'),
    changed = require("gulp-changed"),
    imagemin = require("gulp-imagemin"),
    pngquant = require('imagemin-pngquant');

const paths = {
    bower: "./bower_components",
    dist: "./public/assets",
    js: {
        src: "./public/js",
        dest: "./public/assets/js"
    },
    css: {
        src: "./public/css",
        dest: "./public/assets/css"
    },
    images: {
        src: "./public/images",
        dest: "./public/assets/images"
    },
    fonts: {
        src: "./public/fonts",
        dest: "./public/assets/fonts"
    }
};

const vendor_scripts = [
    paths.bower + "/jquery/dist/jquery.js",
    paths.bower + "/modernizr/modernizr.js",
    paths.bower + "/jquery-dropdown/jquery.dropdown.js",
    paths.bower + "/jquery-modal/jquery.modal.js",
    paths.bower + "/jquery-cookie/jquery.cookie.js",
    paths.bower + "/Stepper/jquery.fs.stepper.js",
    paths.bower + "/hideShowPassword/hideShowPassword.js",
    paths.bower + "/PowerTip/src/core.js",
    paths.bower + "/PowerTip/src/csscoordinates.js",
    paths.bower + "/PowerTip/src/displaycontroller.js",
    paths.bower + "/PowerTip/src/placementcalculator.js",
    paths.bower + "/PowerTip/src/tooltipcontroller.js",
    paths.bower + "/PowerTip/src/utility.js",
    paths.bower + "/dropit/dropit.js",
    paths.bower + "/dropzone/dist/dropzone.js",
    paths.bower + "/datetimepicker/jquery.datetimepicker.js",
    paths.bower + "/autosize/dist/autosize.js",
    paths.bower + "/jcrop/js/jquery.color.js",
    paths.bower + "/jcrop/js/jquery.Jcrop.js",
    paths.bower + "/lang-js/src/lang.js"
];

const scripts = [
    paths.js.src + "/cookie.js",
    paths.js.src + "/spinner.js",
    paths.js.src + "/modal.js",
    paths.js.src + "/post.js",
    paths.js.src + "/poll.js",
    paths.js.src + "/quote.js",
    paths.js.src + "/avatar.js",
    paths.js.src + "/other.js",
    paths.js.src + "/moderation.js"
];

const css = [
    paths.bower + "/normalize.css/normalize.css",
    paths.bower + "/fontawesome/scss/font-awesome.scss",
    paths.bower + "/dropit/dropit.css",
    paths.bower + "/jquery-dropdown/jquery.dropdown.min.css",
    paths.bower + "/datetimepicker/jquery.datetimepicker.css",
    paths.css.src + "/main.scss"
];

const admin_css = [
    paths.bower + "/normalize.css/normalize.css",
    paths.bower + "/fontawesome/scss/font-awesome.scss",
    paths.bower + "/dropit/dropit.css",
    paths.bower + "/jquery-dropdown/jquery.dropdown.min.css",
    paths.bower + "/datetimepicker/jquery.datetimepicker.css",
    paths.css.src + "/admin.scss"
];

gulp.task("clean", (cb) => {
    del([
        paths.js.dest + "/**",
        paths.css.dest + "/**",
        paths.images.dest + "/**",
        paths.fonts.dest + "/**"
    ], cb);
});

gulp.task("images", (cb) => {
    return gulp.src(paths.images.src + "/*")
        .pipe(changed(paths.images.dest))
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [
                {removeViewBox: false},
                {cleanupIDs: false}
            ],
            use: [pngquant()]
        }))
        .pipe(gulp.dest(paths.images.dest));
});

gulp.task("fonts", (cb) => {
    return gulp.src(paths.bower + "/fontawesome/fonts/**.*")
        .pipe(gulp.dest(paths.fonts.dest));
});

elixir((mix) => {
    mix.browserSync();

    mix.task("clean")
        .sass(css, paths.css.dest) // Main CSS
        .sass(admin_css, paths.css.dest) // Admin CSS
        .browserify(vendor_scripts, paths.js.dest + "/vendor.js") // Vendor JS (jQuery, etc.)
        .browserify(scripts, paths.js.dest + "/main.js"); // Main app JS
});

// const
//     concat = require("gulp-concat"),
//     uglify = require("gulp-uglify"),
//     stripDebug = require("gulp-strip-debug"),
//     sass = require("gulp-sass"),
//     autoprefixer = require("gulp-autoprefixer"),
//     nano = require('gulp-cssnano'),
//     rename = require("gulp-rename"),
//     source = require('vinyl-source-stream'),
//     buffer = require('vinyl-buffer'),
//     browserify = require("browserify"),
//     sourcemaps = require('gulp-sourcemaps');
//
// gulp.task("default", ["images", "vendor_scripts", "scripts", "styles", "admin_styles", "rtl_styles", "admin_rtl_styles", "fonts"]);
//
// gulp.task("watch", ["default"], function () {
//     gulp.watch("./bower_components/**/*.js", ["vendor_scripts"]);
//     gulp.watch(paths.js.src + "/**/*.js", ["scripts"]);
//     gulp.watch(paths.images.src + "/**/*", ["images"]);
//     gulp.watch(paths.css.src + "/**/*.scss", ["styles"]);
//     gulp.watch(paths.css.src + "/**/*.scss", ["rtl_styles"]);
//     gulp.watch(paths.css.src + "/**/*.scss", ["admin_styles"]);
//     gulp.watch(paths.css.src + "/**/*.scss", ["admin_rtl_styles"]);
//     gulp.watch(paths.fonts.src + "/**/*", ["fonts"]);
// });



// gulp.task("rtl_styles", function () {
//     return gulp.src([paths.bower + "/normalize.css/normalize.css", paths.bower + "/fontawesome/scss/font-awesome.scss", paths.css.src + "/rtl.scss"])
//         .pipe(sourcemaps.init())
//         .pipe(sass({
//             includePaths: [
//                 "./app/bower_components"
//             ]
//         }))
//         .pipe(autoprefixer("last 2 version", "safari 5", "ie 8", "ie 9", "opera 12.1", "ios 6", "android 4"))
//         .pipe(concat("rtl.css"))
//         .pipe(sourcemaps.write('.'))
//         .pipe(gulp.dest(paths.css.dest))
//         .pipe(rename({suffix: ".min"}))
//         .pipe(nano())
//         .pipe(sourcemaps.write('.'))
//         .pipe(gulp.dest(paths.css.dest));
// });
//
// gulp.task("admin_rtl_styles", function () {
//     return gulp.src([paths.bower + "/normalize.css/normalize.css", paths.bower + "/fontawesome/scss/font-awesome.scss", paths.css.src + "/admin.rtl.scss"])
//         .pipe(sourcemaps.init())
//         .pipe(sass({
//             includePaths: [
//                 "./app/bower_components"
//             ]
//         }))
//         .pipe(autoprefixer("last 2 version", "safari 5", "ie 8", "ie 9", "opera 12.1", "ios 6", "android 4"))
//         .pipe(concat("admin.rtl.css"))
//         .pipe(sourcemaps.write('.'))
//         .pipe(gulp.dest(paths.css.dest))
//         .pipe(rename({suffix: ".min"}))
//         .pipe(nano())
//         .pipe(sourcemaps.write('.'))
//         .pipe(gulp.dest(paths.css.dest));
// });
