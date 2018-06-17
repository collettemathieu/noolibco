path = require('path');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const dev = process.env.NODE_ENV === 'dev';
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const webpack = require('webpack');

let config = {
	entry: {
		frontend: './public_html/JavaScript/entries/frontend.js',
		articles: './public_html/JavaScript/entries/articles.js',
		services: './public_html/JavaScript/entries/services.js',
		information: './public_html/JavaScript/entries/information.js',
		sphinx: './public_html/JavaScript/entries/sphinx.js'
	},
	output: {
		path: path.resolve('./public_html/JavaScript/dist'),
		filename: '[name].js',
		publicPath: '/public_html/JavaScript/dist/'
	},
	watch: dev,
	module: {
	    rules: [{
			test: '/\.js$/',
			exclude: /(node.module|bower_components)/,
			use:['babel-loader']
		},
		{
	        test: /\.css$/,
	        use: ExtractTextPlugin.extract({
	          fallback: "style-loader",
	          use: "css-loader"
	        })
	      }
	    ]
	},
	plugins:[
		new ExtractTextPlugin({
			filename:'[name].css'
		}),
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery: 'jquery',
			'window.jQuery': 'jQuery',
			katex: 'katex',
			'window.katex': 'katex'
		})
	],
	devtool: dev ? "cheap-module-eval-source-map":false
}

if(!dev){
	config.plugins.push(new UglifyJsPlugin({
		sourceMap: false
	}));
}

module.exports = config;