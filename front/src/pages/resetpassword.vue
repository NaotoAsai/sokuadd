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
          v-model="valid"
          class="pa-9"
        >
          <ValidationProvider
            v-slot="{ errors, valid }"
            rules="required|min:8|max:255"
            vid="password"
            name="パスワード"
          >
            <v-text-field
              v-model="newPasswordData.password"
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
            rules="required|confirmed:password"
            name="パスワード(確認)"
          >
            <v-text-field
              v-model="passwordConfirm"
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
            @click="resetPassword"
          >
            送信
          </v-btn>
        </v-form>
      </ValidationObserver>
    </v-card>
    <v-card v-if="isDone === true" class="mx-auto" max-width="344">
      <v-card-title>
        パスワードの再発行が完了しました
      </v-card-title>
      <v-card-subtitle>
        ログインフォームよりログインを行ってください
      </v-card-subtitle>
      <nuxt-link to="entrance">
        ログイン
      </nuxt-link>
    </v-card>
  </div>
</template>

<script>
export default {
  auth: 'guest',
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
