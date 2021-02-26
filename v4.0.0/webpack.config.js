const DEV = 'production' !== process.env.NODE_ENV;

/**
* NPM Plugins.
*/
const path                    = require( 'path' );
const MiniCssExtractPlugin    = require( 'mini-css-extract-plugin' );
const OptimizeCssAssetsPlugin = require( 'optimize-css-assets-webpack-plugin' );
const cssnano                 = require( 'cssnano' );
const CleanWebpackPlugin      = require( 'clean-webpack-plugin' );
const TerserPlugin            = require( 'terser-webpack-plugin' );
const StyleLintPlugin         = require( 'stylelint-webpack-plugin' );
const FriendlyErrorsPlugin    = require( 'friendly-errors-webpack-plugin' );

// Assets Directory path.
const JSDir     = path.resolve( __dirname, 'assets/admin/js' );
const Assets    = path.resolve( __dirname, 'assets/admin' );
const BUILD_DIR = path.resolve( __dirname, 'assets/admin/build' );

// Entry points
const entry = {
	rmpMain: [ Assets + '/rmp-main.js' ]
};

// Outputs
const output = {
	path: BUILD_DIR,
	filename: DEV ? 'js/[name].js' : 'js/[name].min.js'
};

const plugins = ( argv ) => [
	new CleanWebpackPlugin( [ BUILD_DIR ] ),

	new MiniCssExtractPlugin( {
		filename: DEV ? 'css/[name].css' : 'css/[name].min.css'
	} ),

	new StyleLintPlugin( {
		'extends': 'stylelint-config-wordpress/scss'
	} ),

	new FriendlyErrorsPlugin( {
		clearConsole: false
	} )
];

const rules = [
	{
		enforce: 'pre',
		test: /\.js$/,
		exclude: /node_modules/,
		use: 'eslint-loader'
	},
	{
		test: /\.js$/,
		include: [ JSDir ],
		exclude: /node_modules/,
		use: 'babel-loader'
	},
	{
		test: /\.scss$/,
		exclude: /node_modules/,
		use: [
			MiniCssExtractPlugin.loader,
			'css-loader',
			'postcss-loader',
			'sass-loader'
		]
	}
];

const optimization = [
	new OptimizeCssAssetsPlugin( {
		cssProcessor: cssnano
	} ),

	new TerserPlugin()
];

module.exports = ( argv ) => ( {
	entry: entry,
	output: output,
	plugins: plugins( argv ),

	module: {
		'rules': rules
	},

	optimization: {
		minimize: true,
		minimizer: optimization
	},

	externals: {
		jquery: 'jQuery'
	}

} );
