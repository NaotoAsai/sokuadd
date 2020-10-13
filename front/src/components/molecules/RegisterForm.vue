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
          @click="register"
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
      await this.$store.dispatch('register', this.authData)
    }
  }
}
</script>
