<template>
  <div>
    <v-card v-if="isDone === true" class="mx-auto" max-width="344">
      <v-card-title>
        メールアドレスの変更が完了しました
      </v-card-title>
      <nuxt-link to="/">
        ホームへ戻る
      </nuxt-link>
    </v-card>
  </div>
</template>

<script>
export default {
  data () {
    return {
      genericToken: { genericToken: this.$route.query.genericToken },
      isDone: false
    }
  },
  created () {
    this.editEmail()
  },
  methods: {
    async editEmail () {
      await this.$store.dispatch('editEmail', this.genericToken)
        .then(() => {
          this.isDone = true
        })
        // なぜかstore側でエラー画面飛ばしてくれない
        .catch((err) => {
          if (err.response.status === 422) {
            this.$nuxt.error({
              statusCode: err.response.status,
              message: 'customMessage',
              customMessage: err.response.data.message
            })
          } else {
            this.$nuxt.error({ statusCode: err.response.status })
          }
        })
    }
  }
}
</script>
