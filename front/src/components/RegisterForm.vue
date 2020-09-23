<template>
  <div>
    <v-card-title primary-title>
      <h4>ユーザー登録</h4>
    </v-card-title>
    <v-form>
      <v-text-field v-model="authData.name" name="username" label="ユーザー名" />
      <v-text-field v-model="authData.email" name="email" label="メールアドレス" />
      <v-text-field v-model="authData.password" name="password" label="パスワード" type="password" />
      <v-text-field v-model="authData.password_confirmation" name="password_confirmation" label="パスワード(確認)" type="password" />
      <v-card-actions>
        <v-btn primary large block @click="register">
          登録
        </v-btn>
      </v-card-actions>
    </v-form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      authData: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  methods: {
    register () {
      this.$store.dispatch('register', this.authData)
    }
  }
}
</script>
