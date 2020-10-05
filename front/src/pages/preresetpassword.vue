<template>
  <div>
    <v-card v-if="isDone === false" class="mx-auto" max-width="344">
      <v-card-title>
        パスワード再発行
      </v-card-title>
      <v-card-subtitle>
        現在登録中のメールアドレスを入力してください
      </v-card-subtitle>
      <ValidationObserver ref="obs" v-slot="{ invalid }">
        <v-form
          ref="form"
          v-model="valid"
          class="pa-9"
        >
          <ValidationProvider
            v-slot="{ errors, valid }"
            rules="required|email|max:255"
            name="メールアドレス"
          >
            <v-text-field
              v-model="email.email"
              :error-messages="errors"
              :success="valid"
              name="email"
              label="メールアドレス"
              outlined
            />
          </ValidationProvider>
          <v-btn
            large
            block
            :disabled="invalid"
            @click="sendEmail"
          >
            送信
          </v-btn>
        </v-form>
      </ValidationObserver>
    </v-card>
    <v-card v-if="isDone === true" class="mx-auto" max-width="344">
      <v-card-title>
        入力されたメールアドレスにメールを送信しました。
      </v-card-title>
      <v-card-subtitle>
        送信されたメールに従って、引き続き再発行手続きを行ってください
      </v-card-subtitle>
    </v-card>
  </div>
</template>

<script>
export default {
  auth: 'guest',
  data () {
    return {
      email: { email: '' },
      isDone: false
    }
  },
  methods: {
    async sendEmail () {
      await this.$store.dispatch('preResetPassword', this.email)
      this.isDone = true
    }
  }
}
</script>
