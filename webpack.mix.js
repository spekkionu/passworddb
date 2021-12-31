const mix = require('laravel-mix');
require('laravel-mix-svelte');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    resolve: {
        alias: {
            '~': path.resolve(__dirname, 'resources/js'),
            '@': path.resolve(__dirname, 'resources/js/components'),
            '@page': path.resolve(__dirname, 'resources/js/Pages'),
            '@css': path.resolve(__dirname, 'resources/css'),
            '@img': path.resolve(__dirname, 'resources/images'),
        }
    },
});

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ])
    .svelte({
        compilerOptions: {
            dev: !mix.inProduction(), // Default: false
        },
    });

if (mix.inProduction()) {
    mix.version()
} else {
    mix.sourceMaps()
}
