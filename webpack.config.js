const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('./resources/assets/js'),
        },
    },

    devServer: {
        host: '0.0.0.0',
        disableHostCheck: true
    },
};
