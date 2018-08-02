path = require('path');
const dev = process.env.NODE_ENV === 'dev';
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const MiniCssExtractPlugIn = require('mini-css-extract-plugin');
const OptimizeCssAssetsWebpackPlugIn = require('optimize-css-assets-webpack-plugin');
const webpack = require('webpack');

let config = {
	entry: {
		frontend: './public_html/JavaScript/entries/frontend.js',
		articles: './public_html/JavaScript/entries/articles.js',
		services: './public_html/JavaScript/entries/services.js',
		information: './public_html/JavaScript/entries/information.js',
		sphinx: './public_html/JavaScript/entries/sphinx.js',
		backend: './public_html/JavaScript/entries/backend.js',
		styleFrontend: './public_html/Css/entries/frontend.css',
		styleCours: './public_html/Css/entries/cours.css',
		styleArticles: './public_html/Css/entries/articles.css',
		styleInformation: './public_html/Css/entries/information.css',
		styleLogIn: './public_html/Css/entries/logIn.css',
		styleServices: './public_html/Css/entries/services.css',
		styleSphinx: './public_html/Css/entries/sphinx.css',
		styleBackend: './public_html/Css/entries/backend.css'
	},
	output: {
		path: path.resolve('./public_html/dist'),
		filename: '[name].js',
		publicPath: '/public_html/dist/'
	},
	watch: dev,
	module: {
	    rules: [
		    {
				test: '/\.js$/',
				exclude: /(node.module|bower_components)/,
				use:['babel-loader']
			},
			{
		        test: /\.(sa|sc|c)ss$/,
		        use: [
		        	MiniCssExtractPlugIn.loader,
		        	'css-loader',
		        	'sass-loader'
		        ]
		    },
		    {
			    test: /\.(eot|ttf|otf)(\?.*)?$/,
			    loader: 'file-loader',
			    options: {
			    	name: '[name].[ext]',
			    	publicPath: '../dist/'
			    }
			},
			{ 
			    test: /\.(png|jpg|gif|svg)$/,
			    use: [
			      {
			        loader: 'url-loader',
			        options: {
			          limit: 8192,
			          name: '[name].[hash:7].[ext]'
			        }
			      },
			      { 
			        loader: 'img-loader',
			        options: {
			          enabled: !dev
			        }
			      }
			    ]
			}
		]},
	plugins:[
		new MiniCssExtractPlugIn({
			filename:'[name].css',
			chunkFilename: '[id].css',
			publicPath: '/public_html/Css/dist/'
		}),
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery: 'jquery',
			'window.jQuery': 'jQuery',
			katex: 'katex',
			'window.katex': 'katex',
			Dropzone: 'dropzone',
			ProgressBar: 'progressbar.js',
			_: 'lodash'
		})
	],
	devtool: dev ? "cheap-module-eval-source-map":false
}

if(!dev){
	//config.plugins.push(new UglifyJsPlugin({
	//	sourceMap: false
	//}));
	config.plugins.push(new OptimizeCssAssetsWebpackPlugIn({
		cssProcessorOptions: {
			safe: true // Permet d'utiliser uniquement le minify en mode safe
		}
	}));
}

module.exports = config;