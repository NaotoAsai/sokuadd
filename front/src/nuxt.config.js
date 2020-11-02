import colors from 'vuetify/es5/util/colors'

require('dotenv').config()
const { API_URL } = process.env

export default {
  /*
  ** Nuxt rendering mode
  ** See https://nuxtjs.org/api/configuration-mode
  */
  mode: 'spa',
  /*
  ** Nuxt target
  ** See https://nuxtjs.org/api/configuration-target
  */
  target: 'static',
  /*
  ** Headers of the page
  ** See https://nuxtjs.org/api/configuration-head
  */
  head: {
    titleTemplate: '%s - ' + process.env.npm_package_name,
    title: process.env.npm_package_name || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },
  /*
  ** Global CSS
  */
  css: [
    '@/assets/css/main.css'
  ],
  /*
  ** Plugins to load before mounting the App
  ** https://nuxtjs.org/guide/plugins
  */
  plugins: [
    '~/plugins/axios.js',
    '~/plugins/vee-validate',
    { src: '~/plugins/vue-flash-message.js', mode: 'client' }
  ],
  /*
  ** Auto import components
  ** See https://nuxtjs.org/api/configuration-components
  */
  components: true,
  /*
  ** Nuxt.js dev-modules
  */
  buildModules: [
    // Doc: https://github.com/nuxt-community/eslint-module
    '@nuxtjs/eslint-module',
    '@nuxtjs/vuetify'
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    '@nuxtjs/dotenv',
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    'cookie-universal-nuxt',
    // '@nuxtjs/auth' LaravelJWTを使うのでこっち↓
    '@nuxtjs/auth-next'
  ],
  router: {
    middleware: ['auth']
  },
  /*
  ** Axios module configuration
  ** See https://axios.nuxtjs.org/options
  */
  // axios: {
  //   baseURL: process.env.API_URL || 'http://localhost'
  // },
  // auth: {
  //   redirect: {
  //     login: '/entrance', // 未ログイン時に認証ルートへアクセスした際のリダイレクトURL
  //     logout: '/entrance', // ログアウト時のリダイレクトURL
  //     callback: false, // Oauth認証等で必要となる コールバックルート
  //     home: '/' // ログイン後のリダイレクトURL
  //   },
  //   strategies: {
  //     local: {
  //       endpoints: {
  //         login: { url: '/api/auth/login', method: 'post', propertyName: 'access_token' },
  //         logout: { url: '/api/auth/logout', method: 'post' },
  //         user: { url: '/api/auth/user', method: 'get', propertyName: false }
  //       }
  //     }
  //   },
  //   localStorage: false
  // },
  auth: {
    redirect: {
      login: '/entrance', // 未ログイン時に認証ルートへアクセスした際のリダイレクトURL
      logout: '/entrance', // ログアウト時のリダイレクトURL
      callback: false, // Oauth認証等で必要となる コールバックルート
      home: '/' // ログイン後のリダイレクトURL
    },
    strategies: {
      'laravelJWT': {
        provider: 'laravel/jwt',
        url: process.env.API_URL,
        endpoints: {
          login: { url: '/api/v1/login', method: 'post', propertyName: 'access_token' },
          refresh: { url: '/api/v1/refresh', method: 'post' },
          logout: { url: '/api/v1/logout', method: 'post' },
          user: { url: '/api/v1/user', method: 'get', propertyName: false }
        },
        // Token有効期限、バックと一致させておく
        token: {
          property: 'access_token',
          maxAge: 60 * 60
        },
        refreshToken: {
          maxAge: 20160 * 60
        }
      }
    },
    localStorage: false
  },
  /*
  ** vuetify module configuration
  ** https://github.com/nuxt-community/vuetify-module
  */
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: false,
      themes: {
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3
        },
        light: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3
        }
      }
    }
  },
  /*
  ** Build configuration
  ** See https://nuxtjs.org/api/configuration-build/
  */
  build: {
    transpile: [
      'vee-validate/dist/rules'
    ]
  },
  env: {
    API_URL
  }
}
