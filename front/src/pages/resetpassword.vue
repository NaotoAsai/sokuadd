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
          @submit.prevent="resetPassword"
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
            type="submit"
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
    // パスワード再発行（メールのリンクからの遷移時）
    async passResetPassword () {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/resetpassword'
      const params = this.genericToken

      await this.$axios.$get(url, { params })
        .catch((err) => {
          this.$nuxt.error({ statusCode: err.response.status })
        })

      this.$store.commit('setLoading', false)
    },
    // パスワード再発行
    async resetPassword () {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/resetpassword'
      const params = this.newPasswordData
      await this.$axios.$put(url, params)
        .then(() => {
          this.isDone = true
        })
        .catch((err) => {
          if (err.response.status === 403) {
            this.$nuxt.error({
              statusCode: err.response.status,
              message: 'customMessage',
              customMessage: err.response.data.message
            })
          } else {
            this.$nuxt.error({ statusCode: err.response.status })
          }
        })

      this.$store.commit('setLoading', false)
    }
  }
}
</script>
