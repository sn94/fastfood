module.exports = {
  entry: ["./assets/css/style1.js"], 
  module: {
    rules: [
      {
        test: /\.css$/i,
        use: ["style-loader", "css-loader"],
      },
      {
        test: /\.(png|jpg)$|\.ttf$/,
        use: [
          'url-loader',
        ],
        }
    ],
  },
};