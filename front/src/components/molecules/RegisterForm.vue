<template>
  <div>
    <v-card-title primary-title>
      <h4>ユーザー登録</h4>
    </v-card-title>
    <ValidationObserver ref="obs" v-slot="{ invalid }">
      <v-form
        ref="form"
        class="pa-9"
        @submit.prevent="register"
      >
        <UserNameTextField
          v-model="authData.name"
        />
        <EmailTextField
          v-model="authData.email"
        />
        <PasswordTextField
          v-model="authData.password"
        />
        <PasswordConfirmTextField
          v-model="passwordConfirm"
        />
        <v-btn
          large
          block
          :disabled="invalid"
          type="submit"
        >
          登録
        </v-btn>
      </v-form>
    </ValidationObserver>
  </div>
</template>

<script>
import UserNameTextField from '~/components/atoms/UserNameTextField.vue'
import EmailTextField from '~/components/atoms/EmailTextField.vue'
import PasswordTextField from '~/components/atoms/PasswordTextField.vue'
import PasswordConfirmTextField from '~/components/atoms/PasswordConfirmTextField.vue'

export default {
  components: {
    UserNameTextField,
    EmailTextField,
    PasswordTextField,
    PasswordConfirmTextField
  },
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
      this.$store.commit('setLoading', true)
      const url = '/api/v1/register'
      const params = this.authData
      await this.$axios.$post(url, params)
        .then(() => {
          this.login()
        })
    },
    async login () {
      await this.$auth.loginWith('laravelJWT', { data: this.authData })

      this.flashMessage.show({
        status: 'success',
        title: '会員登録が完了しました',
        time: 3000,
        wrapperClass: 'custom-wrapper-success'
      })

      this.$store.commit('setLoading', false)
    }
  }
}
</script>
