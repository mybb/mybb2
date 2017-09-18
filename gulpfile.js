"use strict";

const
    gulp = require("gulp"),
    imagemin = require('gulp-imagemin'),
    elixir = require("laravel-elixir"),
    sourcemaps = require('gulp-sourcemaps'),
    rollup = require('rollup-stream'),
    typescript = require('rollup-plugin-typescript'),
    babel = require("rollup-plugin-babel"),
    uglify = require('rollup-plugin-uglify'),
    source = require('vinyl-source-stream'),
    modernizr = require('gulp-modernizr');


elixir.config.sourcemaps = true;

const vendor_scripts = [
    "../../../node_modules/jquery/dist/jquery.js",
    "../../../node_modules/@claviska/jquery-dropdown/jquery.dropdown.js",
    "../../../node_modules/jquery-modal/jquery.modal.js",
    "../../../node_modules/jquery.cookie/jquery.cookie.js",
    "../../../node_modules/Stepper/jquery.fs.stepper.js",
    "../../../node_modules/hideshowpassword/hideShowPassword.js",
    "../../../node_modules/jquery-powertip/dist/jquery.powertip.js",
    "../../../node_modules/dropit/dist/dropit.js",
    "../../../node_modules/dropzone/dist/dropzone.js",
    "../../../node_modules/datetimepicker/dist/DateTimePicker.js",
    "../../../node_modules/autosize/dist/autosize.js",
    "../../../node_modules/jquery-jcrop/js/jquery.color.js",
    "../../../node_modules/jquery-jcrop/js/jquery.Jcrop.js",
    "../../../node_modules/lang.js/src/lang.js"
];

gulp.task("images", () => {
    gulp.src("resources/assets/images/**/*")
        .pipe(imagemin())
        .pipe(gulp.dest("public/assets/images"));
});

gulp.task("typescript", () => {
    return rollup({
            input: 'resources/assets/typescript/mybb.ts',
            format: 'iife',
            sourcemap: 'inline',
            plugins: [
                typescript(),
                babel({
                    exclude: 'node_modules/**',
                }),
                //uglify()
            ]
        }).pipe(source('mybb.js'))
        .pipe(gulp.dest('./public/assets/js'));
});

gulp.task("modernizr", function() {
    gulp.src('./public/assets/**/*.js')
        .pipe(modernizr())
        .pipe(gulp.dest("./public/assets/js/"))
});

elixir(mix => {
    mix.sass("main.scss", "public/assets/css/main.css")
        .sass("admin.scss", "public/assets/css/admin.css")
        .sass("main.rtl.scss", "public/assets/css/rtl.css")
        .sass("admin.rtl.scss", "public/assets/css/admin_rtl.css")
        .copy("node_modules/font-awesome/fonts", "public/assets/fonts")
        .task("images", "resources/assets/images/**/*")
        .scripts(vendor_scripts, "public/assets/js/vendor.js")
        .task("typescript", "resources/assets/typescript/**/*")
        // Run modernizr last as it analyses our JS files
        .task("modernizr");
});
