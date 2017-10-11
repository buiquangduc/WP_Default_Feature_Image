const path = require('path');
const webpack = require('webpack');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = {
    context: path.resolve(__dirname, './assets'), // assets folder
    entry: {
        app: './app.js',
    },
    output: {
        path: path.resolve(__dirname, './dist'), // dist folder
        filename: '[name].js',
    },
    module: {
        rules: [{
                test: /\.(sass|scss)$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    publicPath: '../',
                    use: [
                        'css-loader?url=false',
                        'sass-loader',
                    ],
                }),
            }
            // …
        ],
    },
    plugins: [
        // Clean dist folder fist
        new CleanWebpackPlugin(['dist']),
        // Copy the images folder and optimize all the images
        new CopyWebpackPlugin([{
            from: 'images/',
            to: 'images/'
        }]),
        new ImageminPlugin({
            test: /\.(jpe?g|png|gif|svg)$/i
        }),
        new ExtractTextPlugin({
            filename: '[name].css',
            allChunks: true,
        }),
    ]
};
