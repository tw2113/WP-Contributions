const path = require( 'path' );
const webpack = require( 'webpack' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );

const blockCSSPlugin = new ExtractTextPlugin( {
  filename: './assets/block/dist/block.css',
} );

const extractConfig = {
  use: [
    { loader: 'raw-loader' },
    {
      loader: 'postcss-loader',
      options: {
        plugins: [ require( 'autoprefixer' ) ],
      },
    },
    {
      loader: 'sass-loader',
      query: {
        outputStyle:
          'production' === process.env.NODE_ENV ? 'compressed' : 'nested',
      },
    },
  ],
};

module.exports = {
  entry: {
    './assets/block/dist/block' : './assets/block/src/index.js',
  },
  output: {
    path: path.resolve( __dirname ),
    filename: '[name].js',
  },
  watch: true,
  devtool: 'cheap-eval-source-map',
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
        },
      },
      {
        test: /style\.s?css$/,
        use: blockCSSPlugin.extract( extractConfig ),
      },
    ],
  },
  plugins: [
    blockCSSPlugin,
  ],
};
