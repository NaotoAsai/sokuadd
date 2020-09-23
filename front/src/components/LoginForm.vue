<template>
  <v-card flat>
    <v-card-title primary-title>
      <h4>ログイン</h4>
    </v-card-title>
    <v-form>
      <v-text-field v-model="authData.email" name="email" label="メールアドレス" />
      <v-text-field v-model="authData.password" name="password" label="パスワード" type="password" />
      <v-card-actions>
        <v-btn primary large block @click="login">
          ログイン
        </v-btn>
      </v-card-actions>
      <nuxt-link to="/resetpassword">
        パスワードを忘れた方
      </nuxt-link>
    </v-form>
  </v-card>
</template>

<script>
export default {
  data () {
    return {
      authData: {
        email: '',
        password: ''
      }
    }
  },
  methods: {
    login () {
      this.$store.dispatch('login', this.authData)
    }
  }
}
</script>
