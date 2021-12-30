const path = require('path');
const fs = require('fs');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const themesPath = './src/styles';

function getScssEntries() {
  const themes = {};
  fs.readdirSync(themesPath).forEach(file => {
    if(/^_/.test(file)){
      return;
    }
    const filename = path.basename(file, '.scss');
    themes[filename] = `${themesPath}/${file}`;
  });
  return themes;
}

module.exports = {
  mode: 'development',
  plugins: [
    new MiniCssExtractPlugin(),
  ],
  entry: {
    arcw: './src/scripts/arcw.ts',
    ...getScssEntries(),
  },
  devtool: 'source-map',
  target: ['web', 'es5'],
  watch: true,
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
};
