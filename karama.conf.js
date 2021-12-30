const path = require('path');

const defaultWebpackOptions = {
  mode: 'development',
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
    filename: '[name].js',
    path: path.resolve(__dirname, 'spec'),
  },
  stats: {
    modules: false,
    colors: true,
  },
  watch: false,
  optimization: {
    runtimeChunk: 'single',
    splitChunks: {
      chunks: 'all',
      minSize: 0,
      cacheGroups: {
        commons: {
          name: 'commons',
          chunks: 'initial',
          minChunks: 1,
        },
      },
    },
  },
  plugins: [],
};

module.exports = function (config) {
  config.set({
    basePath: '',
    frameworks: ['jasmine', 'webpack'],
    files: [
      // !!! use watched: false as we use webpacks watch
      { pattern: '**/*.spec.ts', watched: false }
    ],
    preprocessors: {
      // add webpack as preprocessor
      '**/*.spec.ts': [ 'webpack' ]
    },
    client: {
      clearContext: false, // leave Jasmine Spec Runner output visible in browser
    },
    plugins: [
      require('karma-jasmine'),
      require('karma-jasmine-html-reporter'),
      require('karma-chrome-launcher'),
      require('karma-coverage-istanbul-reporter'),
      require('karma-webpack'),
    ],
    coverageIstanbulReporter: {
      dir: path.join(__dirname, 'src/scripts/coverage'),
      reports: ['html', 'lcovonly'],
      fixWebpackSourcePaths: true
    },
    webpack: {
      ...defaultWebpackOptions,
    },
    reporters: ['progress', 'kjhtml'],
    colors: true,
    logLevel: config.LOG_INFO,
    autoWatch: false,
    browsers: ['Chrome'],
    singleRun: false,
    concurrency: Infinity,
  });
};
