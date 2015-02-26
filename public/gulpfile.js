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
    del = require("del"),
    source = require('vinyl-source-stream'),
    buffer = require('vinyl-buffer'),
    browserify = require("browserify"),
    sourcemaps = require('gulp-sourcemaps');

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
        dest: "./assets/images"
    },
    fonts: {
        src: "./fonts",
        dest: "./assets/fonts"
    }
};

var vendor_scripts = [
    paths.bower + "/jquery/dist/jquery.js",
    paths.bower + "/modernizr/modernizr.js",
    paths.bower + "/jquery-dropdown/jquery.dropdown.js",
    paths.bower + "/jquery-modal/jquery.modal.js",
    paths.bower + "/Stepper/jquery.fs.stepper.js",
    paths.bower + "/hideShowPassword/hideShowPassword.js",
    paths.bower + "/PowerTip/src/core.js",
    paths.bower + "/PowerTip/src/csscoordinates.js",
    paths.bower + "/PowerTip/src/displaycontroller.js",
    paths.bower + "/PowerTip/src/placementcalculator.js",
    paths.bower + "/PowerTip/src/tooltipcontroller.js",
    paths.bower + "/PowerTip/src/utility.js",
    paths.bower + "/dropit/dropit.js",
    paths.bower + "/dropzone/dist/dropzone.js"
];

var scripts = [
    paths.js.src + "/modal.js",
    paths.js.src + "/other.js"
];

gulp.task("default", ["images", "vendor_scripts", "scripts", "styles", "fonts"]);

gulp.task("clean", function(cb) {
    del(
        [
            paths.js.dest + "/**",
            paths.css.dest + "/**",
            paths.images.dest + "/**",
            paths.fonts.dest + "/**"
        ]
        , cb);
});

gulp.task("watch", ["default"], function() {
    gulp.watch("./bower_components/**/*.js", ["vendor_scripts"]);
    gulp.watch(paths.js.src + "/**/*.js", ["scripts"]);
    gulp.watch(paths.images.src + "/**/*", ["images"]);
    gulp.watch(paths.css.src + "/**/*.scss", ["styles"]);
    gulp.watch(paths.fonts.src + "/**/*", ["fonts"]);
});

gulp.task("images", function() {
    return gulp.src(paths.images.src + "/*")
        .pipe(changed(paths.images.dest))
        .pipe(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true }))
        .pipe(gulp.dest(paths.images.dest));
});

gulp.task("vendor_scripts", function() {
    return gulp.src(vendor_scripts)
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(concat("vendor.js"))
        .pipe(gulp.dest(paths.js.dest + "/"))
        .pipe(stripDebug())
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(rename({
            suffix: ".min"
        }))
        .pipe(gulp.dest(paths.js.dest + "/"));
});

gulp.task("scripts", function() {
	console.log(paths.js.dest);
    return gulp.src(scripts)
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(concat("main.js"))
        .pipe(gulp.dest(paths.js.dest + "/"))
        .pipe(stripDebug())
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(rename({
            suffix: ".min"
        }))
        .pipe(gulp.dest(paths.js.dest + "/"));
});

gulp.task("styles", function() {
    return gulp.src([paths.bower + "/normalize-css/normalize.css", paths.bower + "/fontawesome/scss/font-awesome.scss", paths.css.src + "/main.scss"])
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

gulp.task("fonts", function() {
    return gulp.src(paths.bower + "/fontawesome/fonts/**.*").pipe(gulp.dest(paths.fonts.dest));
});