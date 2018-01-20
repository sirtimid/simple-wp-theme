const path = require('path')
const webpack = require('webpack')
const CleanWebpackPlugin = require('clean-webpack-plugin')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

const settings = {
  host: 'localhost',
  port: 3333,
  proxy: 'wptest.dev',
  urlOverride: /wptest.dev/g
}

module.exports = {
  entry: {
    app: path.resolve(__dirname, 'assets/js/app.js')
  },
  output: {
    filename: '[name].min.js',
    path: path.resolve(__dirname, 'assets/js')
  },
  devtool: 'source-map',
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.jsx?$/,
        loader: 'standard-loader',
        exclude: /(node_modules|bower_components)/,
        options: {
          error: false,
          snazzy: true,
          parser: 'babel-eslint'
        }
      },
      {
        test: /\.js$/,
        exclude: [/node_modules/],
        use: [{
          loader: 'babel-loader'
        }]
      },
      {
        test: /\.html$/,
        exclude: [/node_modules/],
        use: [
          {
            loader: 'raw-loader'
          }
        ]
      },
      {
        test: /\.css$/,
        exclude: [/node_modules/],
        use: [
          {
            loader: 'style-loader'
          },
          {
            loader: 'css-loader', options: { sourceMap: true }
          }
        ]
      },
      {
        test: /\.(sass|scss)$/,
        exclude: [/node_modules/],
        use: [
          {
            loader: 'style-loader'
          },
          {
            loader: 'css-loader', options: { sourceMap: true }
          },
          {
            loader: 'sass-loader', options: { sourceMap: true }
          }
        ]
      },
      {
        test: /\.woff($|\?)|\.woff2($|\?)|\.ttf($|\?)|\.eot($|\?)|\.svg($|\?)/,
        use: [
          {
            loader: 'url-loader'
          }
        ]
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin(['dist']),
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NamedModulesPlugin(),
    new BrowserSyncPlugin({
      host: settings.host,
      port: settings.port,
      proxy: settings.proxy,
      files: [
        {
          match: [
            '**/*.php'
          ]
        }
      ],
      rewriteRules: [
        {
          match: settings.urlOverride,
          fn: function (req, res, match) {
            return settings.host + ':' + settings.port
          }
        }
      ]
    })
  ]
}
