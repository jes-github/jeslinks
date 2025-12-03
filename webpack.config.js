const webpackConfig = require('@nextcloud/webpack-vue-config')

webpackConfig.performance = {
	                                maxEntrypointSize: 5242880, // 5MB
	                                maxAssetSize: 5242880
}

module.exports = webpackConfig

