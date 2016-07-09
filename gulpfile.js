"use strict";

const elixir = require("laravel-elixir");

elixir.config.sourcemaps = true;


elixir(mix => {
    mix.sass("main.scss", "public/assets/css/main.css");
});
