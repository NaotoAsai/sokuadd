export default ({ $axios }) => {
  // エラー画面リダイレクト処理共通化
  // $axios.onError((error) => {
  //   422(入力エラーのみエラー回避)
  //   if (error.response.status === 422) {
  //     return Promise.resolve(error.response)
  //   }
  //   $nuxt.error({ statusCode: Number(error.response.status) })
  // })
}
