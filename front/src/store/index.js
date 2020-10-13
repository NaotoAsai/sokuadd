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
  // ボトムナビのアクティブ状態をサイドメニューと連携したいのでこのメソッドを通す
  changePage (state, page) {
    if (page === 'index') {
      state.activeBtn = 0
    }
    if (page === 'calendar') {
      state.activeBtn = 1
    }
    if (page === 'monthlydatabyclass') {
      state.activeBtn = 2
    }
    if (page === 'classedit') {
      state.activeBtn = 3
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
  // 新規ユーザー登録
  async register ({ dispatch }, authData) {
    const url = '/api/v1/register'
    const params = authData
    await this.$axios.$post(url, params)
      .then(() => {
        dispatch('login', authData)
      })
  },
  // ログイン
  async login ({ dispatch }, authData) {
    return await this.$auth.loginWith('laravelJWT', { data: authData })
      // 401エラーのみエラーページではなく、画面にメッセージ表示する
      .catch((err) => {
        console.log(err.response)
        if (err.response.status === 401) {
          return err.response
        }
      })
  },
  // ログアウト
  async logout ({ commit, dispatch }) {
    await this.$auth.logout('laravelJWT')
  },

  // パスワード再発行（準備）
  async preResetPassword ({ commit }, email) {
    const url = '/api/v1/resetpassword'
    commit('setLoading', true)

    await this.$axios.$post(url, email)

    commit('setLoading', false)
  },
  // パスワード再発行（メールのリンクからの遷移時）
  async passResetPassword ({ commit }, genericToken) {
    const url = '/api/v1/resetpassword'
    commit('setLoading', true)

    await this.$axios.$get(url, { params: genericToken })

    commit('setLoading', false)
  },
  // パスワード再発行
  async resetPassword ({ commit }, newPasswordData) {
    const url = '/api/v1/resetpassword'
    commit('setLoading', true)

    await this.$axios.$put(url, newPasswordData)

    commit('setLoading', false)
  },

  // パスワード変更
  async editPassword ({ commit }, editPasswordData) {
    const url = '/api/v1/password'
    commit('setLoading', true)

    return await this.$axios.$put(url, editPasswordData)
      // 401エラーのみエラーページではなく、画面にメッセージ表示する
      .then(() => {
        return { status: 200 }
      })
      .catch((err) => {
        if (err.response.status === 401) {
          return err.response
        } else {
          return err.response
        }
      })
  },
  // メールアドレス変更（準備）
  async preEditEmail ({ commit }, editEmailData) {
    const url = '/api/v1/email'
    commit('setLoading', true)

    return await this.$axios.$post(url, editEmailData)
      // 401エラーのみエラーページではなく、画面にメッセージ表示する
      .then(() => {
        return { status: 200 }
      })
      .catch((err) => {
        if (err.response.status === 401) {
          return err.response
        } else {
          return err.response
        }
      })
  },
  // メールアドレス変更
  async editEmail ({ commit }, genericToken) {
    const url = '/api/v1/email'
    commit('setLoading', true)

    await this.$axios.$get(url, { params: genericToken })

    commit('setLoading', false)
  },
  async editName ({ commit }, editNameData) {
    const url = '/api/v1/user'
    commit('setLoading', true)

    return await this.$axios.$put(url, editNameData)
      .then(() => {
        return { status: 200 }
      })
      .catch((err) => {
        return err.response
      })
  },

  // 収支分類名一覧の取得
  async getIncomeAndExpenditureClasses ({ commit }) {
    const url = '/api/v1/incomeandexpenditure_classes'
    commit('setLoading', true)
    const response = await this.$axios.$get(url)
    commit('setIncomeAndExpenditureClassData', response)
    commit('setLoading', false)
  },
  // 収支分類名の作成
  async createIncomeAndExpenditureClass ({ commit }, newData) {
    const url = '/api/v1/incomeandexpenditure_classes'

    commit('setLoading', true)

    await this.$axios.$post(url, newData)
      .then((newId) => {
        // 配列に追加
        commit('addIncomeAndExpenditureClassData', {
          type: newData.type === 0 ? 'incomeClasses' : 'expenditureClasses',
          id: newId,
          name: newData.name
        })
      })

    commit('setLoading', false)
  },
  // 収支分類名の編集
  async editIncomeAndExpenditureClass ({ commit }, editData) {
    const url = '/api/v1/incomeandexpenditure_classes'
    commit('setLoading', true)

    await this.$axios.$put(url, editData)
      .then(() => {
        // 配列の当該データを更新
        commit('updateIncomeAndExpenditureClassData', {
          type: editData.type === 0 ? 'incomeClasses' : 'expenditureClasses',
          index: editData.index,
          name: editData.name
        })
      })

    commit('setLoading', false)
  },
  // 収支分類名の削除
  async deleteIncomeAndExpenditureClass ({ commit }, deleteData) {
    const url = '/api/v1/incomeandexpenditure_classes'
    commit('setLoading', true)

    await this.$axios.$delete(url, { data: deleteData })
      .then(() => {
        // 配列から当該データ削除
        commit('deleteIncomeAndExpenditureClassData', { type: deleteData.type === 0 ? 'incomeClasses' : 'expenditureClasses', index: deleteData.index })
      })

    commit('setLoading', false)
  },
  // 新規収支データの作成
  async createIncomeAndExpenditure ({ commit }, newData) {
    const url = '/api/v1/incomeandexpenditures'
    commit('setLoading', true)

    await this.$axios.$post(url, newData)

    commit('setLoading', false)
  },
  // 収支データの削除
  async deleteIncomeAndExpenditure ({ commit }, deleteData) {
    const url = '/api/v1/incomeandexpenditures'
    commit('setLoading', true)

    const events = await this.$axios.$delete(url, { data: deleteData })

    commit('setLoading', false)
    // カレンダーの収支データ再描画のため収支情報を返す
    return events
  },
  // 収支データの編集
  async editIncomeAndExpenditure ({ commit }, editData) {
    const url = '/api/v1/incomeandexpenditures'
    commit('setLoading', true)

    const events = await this.$axios.$put(url, editData)

    commit('setLoading', false)
    // カレンダーの収支データ再描画のため収支情報を返す
    return events
  }
}
