<template>
  <div>
    <v-card-title primary-title>
      <h4>ユーザー登録</h4>
    </v-card-title>
    <ValidationObserver ref="obs" v-slot="{ invalid }">
      <v-form
        ref="form"
        class="pa-9"
      >
        <ValidationProvider
          v-slot="{ errors }"
          rules="required|max:32"
          name="ユーザー名"
        >
          <v-text-field
            v-model="authData.name"
            :counter="32"
            :error-messages="errors"
            name="username"
            label="ユーザー名"
            outlined
          />
        </ValidationProvider>
        <ValidationProvider
          v-slot="{ errors }"
          rules="required|email|max:255"
          name="メールアドレス"
        >
          <v-text-field
            v-model="authData.email"
            :error-messages="errors"
            name="email"
            label="メールアドレス"
            outlined
          />
        </ValidationProvider>
        <ValidationProvider
          v-slot="{ errors }"
          rules="required|min:8|max:255"
          vid="password"
          name="パスワード"
        >
          <v-text-field
            v-model="authData.password"
            :error-messages="errors"
            name="password"
            label="パスワード"
            type="password"
            outlined
          />
        </ValidationProvider>
        <ValidationProvider
          v-slot="{ errors }"
          rules="required|confirmed:password"
          name="パスワード(確認)"
        >
          <v-text-field
            v-model="passwordConfirm"
            :error-messages="errors"
            name="password_confirmation"
            label="パスワード(確認)"
            type="password"
            outlined
          />
        </ValidationProvider>
        <v-btn
          large
          block
          :disabled="invalid"
          @click="register"
        >
          登録
        </v-btn>
      </v-form>
    </ValidationObserver>
  </div>
</template>

<script>
export default {
  data () {
    return {
      authData: {
        name: '',
        email: '',
        password: ''
      },
      passwordConfirm: ''
    }
  },
  methods: {
    async register () {
      await this.$store.dispatch('register', this.authData)
    }
  }
}
</script>
