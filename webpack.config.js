/**
 * Main entrypoint for Webpack config.
 *
 * Set default config with specific variables for this theme
 * and then pass it next to the rest of webpack settings.
 */

const path = require('path');

module.exports = (env, argv) => {
	const srcFolder = 'assets/src/';
	const distFolder = 'assets/dist/';
	const config = {
		mode: argv.mode,
		dirname: __dirname,
		srcFolder: srcFolder,
		distFolder: distFolder,
		manifestFileName: 'assets/manifest.json',
		webpack: {
			entry: {
				'main': `./${srcFolder}main.js`,
				'style': `./${srcFolder}style.scss`,
				'print': `./${srcFolder}print.scss`,
				'rtl': `./${srcFolder}rtl.scss`,
				'editor': `./${srcFolder}editor.js`,
				'admin/admin': `./${srcFolder}admin/admin.js`,
				'admin/customize-controls': `./${srcFolder}admin/customize-controls.js`,
				'admin/customize-preview': `./${srcFolder}admin/customize-preview.js`,
			},
			resolve: {
				alias: {
					scripts: path.resolve(__dirname, `${srcFolder}/scripts/`),
				}
			}
		},
		browserSync: {
			https: false,
			proxy: 'http://localhost/devenv/',
		},
	};

	console.log('NODE_ENV', process.env.NODE_ENV);


	const webpack = require('./webpack/common')(config);
	console.log(webpack);

	return webpack;
};
