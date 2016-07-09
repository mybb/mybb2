"use strict";

const
    gulp = require("gulp"),
    imagemin = require('gulp-imagemin'),
    elixir = require("laravel-elixir");

elixir.config.sourcemaps = true;

const vendor_scripts = [
    "../../../node_modules/jquery/dist/jquery.js",
    // TODO: modernizr...
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

const scripts = [
    "cookie.js",
    "spinner.js",
    "modal.js",
    "post.js",
    "poll.js",
    "quote.js",
    "avatar.js",
    "other.js",
    "moderation.js"
];

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
        .task("images", "resources/assets/images/**/*")
        .scripts(vendor_scripts, "public/assets/js/vendor.js")
        .scripts(scripts, "public/assets/js/main.js")
});
