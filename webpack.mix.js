const mix = require('laravel-mix');
let webpack = require('webpack');

mix.options({
    processCssUrls: false
    })
    .vue({version: 2})
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .webpackConfig({
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                Popper: ['popper.js', 'default'],
                Promise: 'es6-promise-promise'
            }),
            new webpack.IgnorePlugin({ resourceRegExp: /\.\/locale$/ })
        ]
    });
