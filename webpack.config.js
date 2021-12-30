const path = require('path');

module.exports = {
  mode: 'development',
  entry: './src/scripts/arcw.ts',
  devtool: 'source-map',
  target: ['web', 'es5'],
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: 'ts-loader',
        exclude: /node_modules/,
      },
    ],
  },
  resolve: {
    extensions: [ '.tsx', '.ts', '.js' ],
  },
  output: {
    filename: 'arcw.js',
    path: path.resolve(__dirname, './src/scripts/dist'),
  },
};
