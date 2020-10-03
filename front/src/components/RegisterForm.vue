<template>
  <div>
    <v-card-title primary-title>
      <h4>ユーザー登録</h4>
    </v-card-title>
    <ValidationObserver ref="obs" v-slot="{ invalid }">
      <v-form
        ref="form"
        v-model="valid"
        class="pa-9"
      >
        <ValidationProvider
          v-slot="{ errors, valid }"
          rules="required|max:32"
          name="ユーザー名"
        >
          <v-text-field
            v-model="authData.name"
            :counter="32"
            :error-messages="errors"
            :success="valid"
            name="username"
            label="ユーザー名"
            outlined
          />
        </ValidationProvider>
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
        <ValidationProvider
          v-slot="{ errors, valid }"
          rules="required|confirmed|min:8|max:255"
          name="パスワード(確認)"
        >
          <v-text-field
            v-model="authData.password_confirmation"
            :error-messages="errors"
            :success="valid"
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
import { ValidationProvider } from 'vee-validate'

export default {
  components: {
    // ValidationObserver,
    ValidationProvider
  },
  data () {
    return {
      authData: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
      // valid: true
      // vuetifyのルール
      // required: v => !!v || '入力必須です',
      // min_length: v => v.length >= 8 || '8文字以上です',
      // max_length: v => v.length <= 32 || '32文字以内です'
    }
  },
  methods: {
    async register () {
      await this.$store.dispatch('register', this.authData)
    }
  }
}
</script>
