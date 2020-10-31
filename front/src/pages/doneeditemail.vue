<template>
  <div>
    <v-card v-if="isDone === true" class="mx-auto" max-width="400">
      <v-card-title>
        メールアドレスの変更が完了しました。
      </v-card-title>
      <br>
      <v-card-actions class="justify-center">
        <HomeButton />
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import HomeButton from '~/components/atoms/HomeButton.vue'
export default {
  components: {
    HomeButton
  },
  data () {
    return {
      genericToken: { genericToken: this.$route.query.genericToken },
      isDone: false
    }
  },
  created () {
    this.$store.commit('changePage', 'doneeditemail')
    this.editEmail()
  },
  methods: {
    async editEmail () {
      await this.$store.dispatch('editEmail', this.genericToken)
        .then(() => {
          this.isDone = true
          // ユーザー情報更新
          this.$auth.fetchUser()
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
