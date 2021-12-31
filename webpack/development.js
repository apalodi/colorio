const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

module.exports = (config) => {
	const defaultSyncConfig = {
		host: 'localhost',
		port: 3000,
		https: false,
		proxy: 'http://localhost/',
	};

	const plugins = [
		new BrowserSyncPlugin(
			{
				...defaultSyncConfig,
				...config.browserSync
			},
			{
				// prevent BrowserSync from reloading the page
				// and let Webpack Dev Server take care of this
				reload: false
			}
		)
	];

	return {
		plugins,
	}
};
