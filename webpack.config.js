// webpack.config.js
var Encore = require('@symfony/webpack-encore');

Encore
// the project directory where all compiled assets will be stored
  .setOutputPath('public/assets/')

  // the public path used by the web server to access the previous directory
  .setPublicPath('/assets')

  // will create public/build/app.js and public/build/app.css
  // .addEntry('app', './assets/js/app.js')
  .addStyleEntry('css/dashboard', './assets/css/dashboard.css')
  .addStyleEntry('css/login', './assets/css/login.css')


// export the final configuration
module.exports = Encore.getWebpackConfig();
