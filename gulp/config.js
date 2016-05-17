/**
 *  Config file used for gulp tasks
 */
var assets = './resources/assets/';

module.exports = {
    scsslint: {
        src: [
            assets + '/sass/**/*.{sass,scss}',
            '!' + assets + '/scss/_sprites.scss', //ignore generated sprites 
            '!' + assets + '/scss/_sprites_jpg.scss'
        ],
        options: {
            bundleExec: false
        }
    },
    jshint: {
        src: assets + '/js/*.js'
    }
};