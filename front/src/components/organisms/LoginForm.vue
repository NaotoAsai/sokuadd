<template>
  <v-card flat>
    <v-card-title primary-title>
      <h4>ログイン</h4>
    </v-card-title>
    <v-card-text v-if="unAuthorized !== ''" class="red--text">
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
      <nuxt-link to="/preresetpassword">
        パスワードを忘れた方
      </nuxt-link>
    </ValidationObserver>
  </v-card>
</template>

<script>
import EmailTextField from '~/components/molecules/EmailTextField.vue'
import PasswordTextField from '~/components/molecules/PasswordTextField.vue'

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
      unAuthorized: ''
    }
  },
  methods: {
    async login () {
      await this.$store.dispatch('login', this.authData)
        .then((res) => {
          // 認証失敗時、エラーメッセージ格納
          if (res.status === 401) {
            this.unAuthorized = res.data.message
          }
        })
    }
  }
}
</script>
