<template>
  <v-card>
    <v-card-title>
      各種設定
    </v-card-title>
    <v-divider class="mx-4" />
    <v-list>
      <v-list-group>
        <template v-slot:activator>
          <v-list-item-title>ユーザー名変更</v-list-item-title>
        </template>
        <v-list-item>
          <v-row justify="center" class="pa-6">
            <v-col>
              <EditUserNameForm
                ref="editUserNameForm"
                v-model="editNameData.name"
                @send="editName"
              />
            </v-col>
          </v-row>
        </v-list-item>
      </v-list-group>
      <v-list-group>
        <template v-slot:activator>
          <v-list-item-title>パスワード変更</v-list-item-title>
        </template>
        <v-list-item>
          <v-row justify="center" class="pa-6">
            <v-col
              v-if="unAuthorized !== ''"
              cols="12"
              class="red--text"
              :class="{ unauthorized:$store.state.errorAnimation }"
            >
              {{ unAuthorized }}
            </v-col>
            <v-col cols="12">
              <EditPasswordForm
                v-if="reloadFlag === false"
                ref="editPasswordForm"
                @send="editPassword($event)"
              />
            </v-col>
          </v-row>
        </v-list-item>
      </v-list-group>
      <v-list-group>
        <template v-slot:activator>
          <v-list-item-title>メールアドレス変更</v-list-item-title>
        </template>
        <v-list-item>
          <v-row justify="center" class="pa-6">
            <v-col
              v-if="unAuthorized !== ''"
              cols="12"
              class="red--text"
              :class="{ unauthorized:$store.state.errorAnimation }"
            >
              {{ unAuthorized }}
            </v-col>
            <v-col cols="12">
              <EditEmailForm
                v-if="reloadFlag === false"
                ref="editEmailForm"
                @send="preEditEmail($event)"
              />
            </v-col>
          </v-row>
        </v-list-item>
      </v-list-group>
      <v-list-group>
        <template v-slot:activator>
          <v-list-item-title>退会</v-list-item-title>
        </template>
        <v-list-item>
          <v-row justify="center" class="pa-6">
            <v-col>
              <WithdrawConfirm
                :un-authorized="unAuthorized"
                @send="withdraw($event)"
              />
            </v-col>
          </v-row>
        </v-list-item>
      </v-list-group>
    </v-list>
  </v-card>
</template>

<script>
import EditUserNameForm from '~/components/molecules/EditUserNameForm.vue'
import EditPasswordForm from '~/components/molecules/EditPasswordForm.vue'
import EditEmailForm from '~/components/molecules/EditEmailForm.vue'
import WithdrawConfirm from '~/components/molecules/WithdrawConfirm.vue'

export default {
  components: {
    EditUserNameForm,
    EditPasswordForm,
    EditEmailForm,
    WithdrawConfirm
  },
  data () {
    return {
      reloadFlag: false,
      editNameData: {
        name: this.$auth.user.name
      },
      // 現在のパスワード不一致時にエラーメッセージ
      unAuthorized: '',
      // エラー時、エラーメッセージにアニメーションクラスを付け外しする
      errorAnimation: false
    }
  },
  methods: {
    // ユーザー名変更
    async editName () {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/user'
      const params = this.editNameData

      await this.$axios.$put(url, params)
        .then(() => {
          // ストアのステートの値を更新、this.$auth.fetchUser()←これを使わずにAPI通信を減らす
          this.$store.commit('updateUserName', this.editNameData.name)
          this.flashMessage.show({
            status: 'success',
            title: 'ユーザー名を変更しました',
            time: 3000,
            wrapperClass: 'custom-wrapper-success'
          })
        })
        .catch((err) => {
          this.$nuxt.error({ statusCode: err.response.status })
        })

      this.$store.commit('setLoading', false)
    },
    // パスワード変更
    async editPassword (values) {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/password'
      const params = values
      await this.$axios.$put(url, params)
      // 401エラーのみエラーページではなく、画面にメッセージ表示する
        .then(() => {
          this.flashMessage.show({
            status: 'success',
            title: 'パスワードを変更しました',
            wrapperClass: 'custom-wrapper-success'
          })
          this.unAuthorized = ''
          this.reload()
        })
        .catch((err) => {
          if (err.response.status === 401) {
            this.unAuthorized = err.response.data.message
            // エラーメッセージを一度だけ左右に揺らす
            this.$store.dispatch('shakeErrorMessage')
          } else {
            this.$nuxt.error({ statusCode: err.response.status })
            this.unAuthorized = ''
            this.reload()
          }
        })

      this.$store.commit('setLoading', false)
    },
    // メールアドレス変更準備
    async preEditEmail (values) {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/email'
      const params = values
      await this.$axios.$post(url, params)
      // 401エラーのみエラーページではなく、画面にメッセージ表示する
        .then(() => {
          this.flashMessage.show({
            status: 'success',
            title: '新しいメールアドレスにメールを送信しました',
            message: '届いたメールに従ってメールアドレスの変更を完了させてください',
            time: 10000,
            wrapperClass: 'custom-wrapper-success'
          })
          this.unAuthorized = ''
          this.reload()
        })
        .catch((err) => {
          if (err.response.status === 401) {
            this.unAuthorized = err.response.data.message
            // エラーメッセージを一度だけ左右に揺らす
            this.$store.dispatch('shakeErrorMessage')
          } else {
            this.$nuxt.error({ statusCode: err.response.status })
            this.unAuthorized = ''
            this.reload()
          }
        })

      this.$store.commit('setLoading', false)
    },
    // 退会（最終処理）
    async withdraw (values) {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/user'
      const params = values
      await this.$axios.$delete(url, { data: params })
      // 401エラーのみエラーページではなく、画面にメッセージ表示する
        .then(() => {
          this.flashMessage.show({
            status: 'success',
            title: '退会しました',
            wrapperClass: 'custom-wrapper-success'
          })
          this.unAuthorized = ''
          this.reload()
          this.$auth.logout('laravelJWT')
        })
        .catch((err) => {
          if (err.response.status === 401) {
            this.unAuthorized = err.response.data.message
            // エラーメッセージを一度だけ左右に揺らす
            this.$store.dispatch('shakeErrorMessage')
          } else {
            this.$nuxt.error({ statusCode: err.response.status })
            this.unAuthorized = ''
            this.reload()
          }
        })

      this.$store.commit('setLoading', false)
    },
    // Formコンポーネントを再描画する（入力やバリデーション状態をリセットするため）
    reload () {
      this.reloadFlag = true
      this.$nextTick(() => {
        this.reloadFlag = false
      })
    }
  }
}
</script>
