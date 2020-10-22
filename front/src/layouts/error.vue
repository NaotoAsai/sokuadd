<template>
  <v-app>
    <v-alert
      prominent
      type="error"
    >
      <h1>{{ error.statusCode }}</h1>
      <div v-if="error.statusCode === 404">
        ページが見つかりません
      </div>
      <div v-else-if="error.statusCode === 422">
        入力エラー
        <p>※又はメールアドレスが既に登録されている可能性があります</p>
      </div>
      <div v-else-if="error.statusCode === 500">
        サーバーエラー
      </div>
      <div v-else>
        エラーが発生しました
      </div>
      <div v-if="error.message === 'customMessage'">
        {{ error.customMessage }}
      </div>
      <nuxt-link to="/">
        ホームへ戻る
      </nuxt-link>
    </v-alert>
  </v-app>
</template>

<script>
export default {
  layout: 'empty',
  props: {
    error: {
      type: Object,
      default: null
    }
  },
  mounted () {
    this.$store.commit('setLoading', false)
  }
  // head () {
  //   const title =
  //     this.error.statusCode === 404 ? this.pageNotFound : this.otherError
  //   return {
  //     title
  //   }
  // }
}
</script>
