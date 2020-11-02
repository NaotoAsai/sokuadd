export const state = () => ({
  loading: false,
  activeBtn: 0,
  // 収支分類一覧
  incomeAndExpenditureClasses: {
    incomeClasses: [],
    expenditureClasses: []
  }
})

export const getters = {

}

export const mutations = {
  // ローディング画面切替
  setLoading (state, payload) {
    state.loading = payload
  },
  // ボトムナビのアクティブ状態をサイドメニューと連携したいのでページ遷移時はこのメソッドを通す
  changePage (state, page) {
    switch (page) {
      case 'index':
        state.activeBtn = 0
        break
      case 'calendar':
        state.activeBtn = 1
        break
      case 'monthlydatabyclass':
        state.activeBtn = 2
        break
      case 'classedit':
        state.activeBtn = 3
        break
      default:
        state.activeBtn = undefined
    }
    this.$router.push({ name: page })
  },
  // ユーザー名を変更する
  updateUserName (state, userName) {
    this.$auth.user.name = userName
  },
  // 収支分類配列に一覧データを格納
  setIncomeAndExpenditureClassData (state, incomeAndExpenditureClasses) {
    state.incomeAndExpenditureClasses = incomeAndExpenditureClasses
  },
  // 収支分類配列に新規分類を追加
  addIncomeAndExpenditureClassData (state, newData) {
    state.incomeAndExpenditureClasses[newData.type]
      .push({ id: newData.id, name: newData.name })
  },
  // 収支分類配列の当該データを更新
  updateIncomeAndExpenditureClassData (state, updateData) {
    state.incomeAndExpenditureClasses[updateData.type][updateData.index].name = updateData.name
  },
  // 収支分類配列から当該データを削除
  deleteIncomeAndExpenditureClassData (state, deleteData) {
    state.incomeAndExpenditureClasses[deleteData.type].splice(deleteData.index, 1)
  }
}

export const actions = {
  // ログアウト
  async logout ({ commit, dispatch }) {
    await this.$auth.logout('laravelJWT')
  },
  // 収支分類名一覧の取得
  async getIncomeAndExpenditureClasses ({ commit }) {
    const url = '/api/v1/incomeandexpenditure_classes'
    const response = await this.$axios.$get(url)
    commit('setIncomeAndExpenditureClassData', response)
  }
}
