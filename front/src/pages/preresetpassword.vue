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
          class="pa-9"
          @submit.prevent="sendEmail"
        >
          <EmailTextField
            v-model="email.email"
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
        入力されたメールアドレスにメールを送信しました。
      </v-card-title>
      <v-card-subtitle>
        送信されたメールに従って、引き続き再発行手続きを行ってください
      </v-card-subtitle>
      <v-card-actions class="justify-center">
        <EntranceButton />
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import EmailTextField from '~/components/atoms/EmailTextField.vue'
import EntranceButton from '~/components/atoms/EntranceButton.vue'

export default {
  auth: 'guest',
  components: {
    EmailTextField,
    EntranceButton
  },
  data () {
    return {
      email: { email: '' },
      isDone: false
    }
  },
  methods: {
    // パスワード再発行（準備）
    async sendEmail () {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/resetpassword'
      const params = this.email

      await this.$axios.$post(url, params)

      this.isDone = true

      this.$store.commit('setLoading', false)
    }
  }
}
</script>
