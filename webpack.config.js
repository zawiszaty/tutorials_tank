var Encore = require('@symfony/webpack-encore');
Encore
// the project directory where compiled assets will be stored
    .addEntry('js/index', ['babel-polyfill', './assets/js/index.js'])
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .configureBabel(function(babelConfig) {
        babelConfig.presets.push(['env', 'stage-3']);
        babelConfig.plugins.push('transform-object-rest-spread')
    })
    .enableReactPreset();
;
module.exports = Encore.getWebpackConfig();