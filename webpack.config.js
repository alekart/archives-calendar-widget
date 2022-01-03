const path = require('path');
const fs = require('fs');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const themesPath = './src/styles';
function getScssEntries() {
  const themes = {};
  fs.readdirSync(themesPath).forEach(file => {
    if (/^_/.test(file)) {
      return;
    }
    const filename = path.basename(file, '.scss');
    themes[filename] = `${themesPath}/${file}`;
  });
  return themes;
}

module.exports = {
  mode: 'development',
  devtool: 'source-map',
  entry: {
    arcw: './src/scripts/arcw.ts',
    ...getScssEntries(),
  },
  plugins: [
    new MiniCssExtractPlugin(),
  ],
  target: ['web', 'es5'],
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: [
          'ts-loader',
        ],
        exclude: /node_modules/,
      },
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          {
            loader: 'sass-loader',
            options: {
              // Prefer `dart-sass`
              implementation: require('sass'),
              sourceMap: true,
            },
          },
        ],
      },
    ],
  },
  resolve: {
    extensions: ['.tsx', '.ts', '.js'],
  },
  output: {
    path: path.resolve(__dirname, './src/scripts/dist'),
  },
  devServer: {
    open: true,
    hot: false,
    static: [{
      directory: path.join(__dirname, 'src/scripts'),
    }, {
      directory: path.join(__dirname, 'src/scripts/dist'),
    }],
    port: 9000,
  },
};
