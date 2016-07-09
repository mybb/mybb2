"use strict";

const
    gulp = require("gulp"),
    imagemin = require('gulp-imagemin'),
    elixir = require("laravel-elixir");

elixir.config.sourcemaps = true;

gulp.task("images", () => {
    gulp.src("resources/assets/images/**/*")
        .pipe(imagemin())
        .pipe(gulp.dest("public/assets/images"));
});

elixir(mix => {
    mix
        .sass("main.scss", "public/assets/css/main.css")
        .sass("admin.scss", "public/assets/css/admin.css")
        .sass("rtl.scss", "public/assets/css/rtl.css")
        .sass("admin.rtl.scss", "public/assets/css/admin_rtl.css")
        .copy("node_modules/font-awesome/fonts", "public/assets/fonts")
        .task("images", "resources/assets/images/**/*");
});
