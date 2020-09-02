export const state = () => ({

})

export const getters = {

}

export const mutations = {

}

export const actions = {
  // async nuxtServerInit ({ commit }, { $axios, $auth, $cookies }) {
  //   if ($cookies.get('auth._token.laravelJWT') !== false) {
  //     const user = await $axios.$get('http://nginx/api/auth/user', {
  //       headers: {
  //         Authorization: `${$cookies.get('auth._token.laravelJWT')}`
  //       }
  //     })
  //     $auth.setUser(user)
  //   }
  // },
  register ({ dispatch }, authData) {
    const url = '/api/auth/register'
    const params = authData
    this.$axios.$post(url, params)
      .then((response) => {
        dispatch('login', authData)
      })
  },
  async login ({ dispatch }, authData) {
    try {
      await this.$auth.loginWith('laravelJWT', { data: authData })
    } catch (err) {

    }
  },
  async logout ({ dispatch }) {
    await this.$auth.logout('laravelJWT')
  }
}
