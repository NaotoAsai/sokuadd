<template>
  <div>
    <v-card v-if="isDone === false" class="mx-auto" max-width="344">
      <v-card-title>
        パスワード再発行
      </v-card-title>
      <v-card-subtitle>
        新しいパスワードを設定してください
      </v-card-subtitle>
      <ValidationObserver ref="obs" v-slot="{ invalid }">
        <v-form
          ref="form"
          class="pa-9"
        >
          <PasswordTextField
            v-model="newPasswordData.password"
          />
          <PasswordConfirmTextField
            v-model="passwordConfirm"
          />
          <v-btn
            large
            block
            :disabled="invalid"
            @click="resetPassword"
          >
            送信
          </v-btn>
        </v-form>
      </ValidationObserver>
      <v-card-actions class="justify-center">
        <EntranceButton />
      </v-card-actions>
    </v-card>
    <v-card v-if="isDone === true" class="mx-auto" max-width="344">
      <v-card-title>
        パスワードの再発行が完了しました
      </v-card-title>
      <v-card-subtitle>
        ログインフォームよりログインを行ってください
      </v-card-subtitle>
      <v-card-actions class="justify-center">
        <EntranceButton />
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import EntranceButton from '~/components/atoms/EntranceButton.vue'
import PasswordTextField from '~/components/atoms/PasswordTextField.vue'
import PasswordConfirmTextField from '~/components/atoms/PasswordConfirmTextField.vue'

export default {
  auth: 'guest',
  components: {
    EntranceButton,
    PasswordTextField,
    PasswordConfirmTextField
  },
  data () {
    return {
      genericToken: { genericToken: this.$route.query.genericToken },
      newPasswordData: {
        genericToken: this.$route.query.genericToken,
        password: ''
      },
      passwordConfirm: '',
      isDone: false
    }
  },
  created () {
    this.passResetPassword()
  },
  methods: {
    async passResetPassword () {
      await this.$store.dispatch('passResetPassword', this.genericToken)
        // なぜかstore側でエラー画面ん飛ばしてくれない
        .catch((err) => {
          this.$nuxt.error({ statusCode: err.response.status })
        })
    },
    async resetPassword () {
      await this.$store.dispatch('resetPassword', this.newPasswordData)
      this.isDone = true
    }
  }
}
</script>
