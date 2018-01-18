const path = require('path');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
  	context: path.resolve(__dirname, './assets'),
  	entry: {
    	wpdfi: ['bootstrap-loader', './wpdfi.js'],
  	},
  	output: {
    	path: path.resolve(__dirname, 'dist'),
    	filename: '[name].js',
  	},
    watch: true,
   	module: {
        rules: [
	        {
            test: /\.(sass|scss)$/,
            use: ExtractTextPlugin.extract({
                fallback: 'style-loader',
                publicPath: '../',
                use: [
                    'css-loader?url=false',
                    'sass-loader',
                ],
            }),
	        },
	        { test: /\.(png|woff|woff2|eot|ttf|svg)$/, loader: 'url-loader?limit=100000' }
        ],
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery",
            "window.jQuery": "jquery",
            select2: "select2",
            sortablejs: "sortablejs",
            progressbar: 'progressbar.js'
        }),
        new ExtractTextPlugin({
            filename: '[name].css',
            allChunks: true,
        }),

    ]


};