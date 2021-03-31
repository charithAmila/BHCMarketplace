const mix = require("laravel-mix");


mix.js("resources/js/app.js", "public/js")
    .vue()
    .sass("resources/sass/app.scss", "public/css")
    .sass("resources/sass/dropdown.scss", "public/css")
    .sass("resources/sass/global.scss", "public/css")
    .sass("resources/sass/main.scss", "public/css")
    .sass("resources/sass/navbar.scss", "public/css")
    .sass("resources/sass/responsiveness.scss", "public/css")


mix.webpackConfig({
    resolve: {
        fallback: {
            os: false,
            https: false,
            http: false,
            stream: false,
            crypto: false,
        },
    },

});