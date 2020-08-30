<template>
  <v-row row class="text-xs-center">
    <v-col md="2" offset-md="5" class="lighten-4">
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
        </v-form>
      </v-card>
    </v-col>
  </v-row>
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
