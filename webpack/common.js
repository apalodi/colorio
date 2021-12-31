const path = require('path');
const webpack = require('webpack');
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { merge } = require('webpack-merge');

module.exports = (config) => {
	const isProductionMode = (config.mode === 'production');
	const filename = isProductionMode ? '[name]-[contenthash]' : '[name]';
	const chunkFilename = isProductionMode ? '[id]-[contenthash]' : '[id]';

	let settings = {
		devtool: isProductionMode ? false : 'inline-source-map',
		devServer: {
			contentBase: `./${config.distFolder}`,
		},
	};

	settings.output = {
		path: path.resolve(config.dirname, config.distFolder),
		// overwritten in ${config.srcFolder}/scripts/publicPath.js
		publicPath: config.distFolder,
		filename: `${filename}.js`,
	};

	settings.plugins = [
		// new webpack.ProgressPlugin(),
		new CleanWebpackPlugin(),
		new WebpackManifestPlugin({
			fileName: path.resolve(config.dirname, config.manifestFileName)
		}),
		new FixStyleOnlyEntriesPlugin({
			silent: true,
		}),
		new MiniCssExtractPlugin({
			filename: `${filename}.css`,
			chunkFilename: `${chunkFilename}.css`,
		}),
	];

	settings.module = {
		rules: [
			{
				test: /\.js$/,
				include: [
					path.resolve(config.dirname, config.srcFolder)
				],
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env'],
						plugins: [
							// '@babel/plugin-syntax-dynamic-import',
							'@babel/plugin-transform-runtime',
						],
						cacheDirectory: true,
					},
				},
			},
			{
				test: /\.scss$/,
				include: [
					path.resolve(config.dirname, config.srcFolder)
				],
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
					},
					{
						loader: 'postcss-loader',
						options: {
							postcssOptions: {
								plugins: [
									require('postcss-preset-env'),
								],
							},
						},
					},
					{
						loader: 'sass-loader',
					},
					{
						loader: 'import-glob-loader',
					},
				],
			}
		]
	};

	// settings.optimization.runtimeChunk = false;

	const developmentSettings = require('./development')(config);
	const productionSettings = require('./production')(config);
	const modeSettings = isProductionMode ? productionSettings : developmentSettings;

	settings = merge(settings, modeSettings);

	return merge(settings, config.webpack);
};
