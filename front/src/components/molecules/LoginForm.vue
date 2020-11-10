<template>
  <div>
    <v-card-title primary-title>
      <h4>ログイン</h4>
    </v-card-title>
    <v-card-text
      v-if="unAuthorized !== ''"
      class="red--text"
      :class="{ unauthorized:$store.state.errorAnimation }"
    >
      {{ unAuthorized }}
    </v-card-text>
    <ValidationObserver ref="obs" v-slot="{ invalid }">
      <v-form
        ref="form"
        class="pa-9"
        @submit.prevent="login"
      >
        <EmailTextField
          v-model="authData.email"
        />
        <PasswordTextField
          v-model="authData.password"
        />
        <v-btn
          large
          block
          :disabled="invalid"
          type="submit"
        >
          ログイン
        </v-btn>
      </v-form>
    </ValidationObserver>
    <v-card-actions class="justify-center">
      <nuxt-link to="/preresetpassword">
        パスワードを忘れた方
      </nuxt-link>
    </v-card-actions>
  </div>
</template>

<script>
import EmailTextField from '~/components/atoms/EmailTextField.vue'
import PasswordTextField from '~/components/atoms/PasswordTextField.vue'

export default {
  components: {
    EmailTextField,
    PasswordTextField
  },
  data () {
    return {
      authData: {
        email: '',
        password: ''
      },
      unAuthorized: '',
      errorAnimation: false
    }
  },
  methods: {
    async login () {
      this.$store.commit('setLoading', true)
      await this.$auth.loginWith('laravelJWT', { data: this.authData })
      // 401エラーのみエラーページではなく、画面にメッセージ表示する
        .catch((err) => {
          if (err.response.status === 401) {
            this.$store.commit('setLoading', false)
            // 認証失敗時、エラーメッセージ格納
            this.unAuthorized = err.response.data.message
            // エラーメッセージを一度だけ左右に揺らす
            this.$store.dispatch('shakeErrorMessage')
          } else {
            this.$nuxt.error({ statusCode: err.response.status })
          }
        })
      this.$store.commit('setLoading', false)
    }
  }
}
</script>
