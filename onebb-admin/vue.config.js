module.exports = {
  configureWebpack: {
    devServer: {
      historyApiFallback: true,
      allowedHosts: 'all',
    }
  },

  publicPath: process.env.NODE_ENV === 'production'
    ? './admin/standard/'
    : './',

  pluginOptions: {
    i18n: {
      locale: undefined,
      fallbackLocale: undefined,
      localeDir: undefined,
      enableLegacy: undefined,
      runtimeOnly: false,
      compositionOnly: false,
      fullInstall: true
    }
  }
}
