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
        <span>※会員登録時この画面が表示された場合、メールアドレスが既に登録されている可能性があります</span>
      </div>
      <div v-else-if="error.statusCode === 500">
        サーバーエラー
      </div>
      <div v-else>
        エラーが発生しました
      </div>
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
  data () {
    return {

    }
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
