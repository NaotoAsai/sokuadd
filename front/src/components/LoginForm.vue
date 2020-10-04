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
        v-model="valid"
        class="pa-9"
      >
        <ValidationProvider
          v-slot="{ errors, valid }"
          rules="required|email|max:255"
          name="メールアドレス"
        >
          <v-text-field
            v-model="authData.email"
            :error-messages="errors"
            :success="valid"
            name="email"
            label="メールアドレス"
            outlined
          />
        </ValidationProvider>
        <ValidationProvider
          v-slot="{ errors, valid }"
          rules="required|min:8|max:255"
          name="パスワード"
        >
          <v-text-field
            v-model="authData.password"
            :error-messages="errors"
            :success="valid"
            name="password"
            label="パスワード"
            type="password"
            outlined
          />
        </ValidationProvider>
        <v-btn
          large
          block
          :disabled="invalid"
          @click="login"
        >
          ログイン
        </v-btn>
      </v-form>
      <nuxt-link to="/resetpassword">
        パスワードを忘れた方
      </nuxt-link>
    </ValidationObserver>
  </v-card>
</template>

<script>
export default {
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
