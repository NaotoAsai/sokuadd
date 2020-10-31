<template>
  <v-card flat>
    <v-card-title primary-title>
      <h4>ログイン</h4>
    </v-card-title>
    <v-card-text
      v-if="unAuthorized !== ''"
      class="red--text"
      :class="{ unauthorized:errorAnimation }"
    >
      {{ unAuthorized }}
    </v-card-text>
    <ValidationObserver ref="obs" v-slot="{ invalid }">
      <v-form
        ref="form"
        class="pa-9"
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
          @click="login"
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
  </v-card>
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
            this.shakeErrorMessage()
          } else {
            this.$nuxt.error({ statusCode: err.response.status })
          }
        })
      this.$store.commit('setLoading', false)
    },
    // エラーメッセージを一度だけ左右に揺らす
    shakeErrorMessage () {
      this.errorAnimation = true
      setTimeout(() => { this.errorAnimation = false }, 1000)
    }
  }
}
</script>

<style>
.unauthorized {
  animation: yureru-s 2s infinite;
}

@keyframes yureru-s {
  0% {
      transform: translate(2px, 0px);
  }
  5% {
      transform: translate(-2px, 0px);
  }
  10% {
      transform: translate(2px, 0px);
  }
  15% {
      transform: translate(-2px, 0px);
  }
  20% {
      transform: translate(2px, 0px);
  }
  25% {
      transform: translate(-2px, 0px);
  }
  30% {
      transform: translate(0px, 0px);
  }
}
</style>
