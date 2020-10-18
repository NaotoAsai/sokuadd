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
              :class="{ unauthorized:errorAnimation }"
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
              :class="{ unauthorized:errorAnimation }"
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
    </v-list>
  </v-card>
</template>

<script>
import EditUserNameForm from '~/components/molecules/EditUserNameForm.vue'
import EditPasswordForm from '~/components/molecules/EditPasswordForm.vue'
import EditEmailForm from '~/components/molecules/EditEmailForm.vue'

export default {
  components: {
    EditUserNameForm,
    EditPasswordForm,
    EditEmailForm
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
      await this.$store.dispatch('editName', this.editNameData)
        .then((res) => {
          if (res.status === 200) {
            // ストアのステートの値を更新、this.$auth.fetchUser()←これを使わずにAPI通信を減らす
            this.$store.commit('updateUserName', this.editNameData.name)
            this.flashMessage.show({
              status: 'success',
              title: 'ユーザー名を変更しました',
              time: 3000
            })
          } else {
            this.$nuxt.error({ statusCode: res.status })
          }
        })

      this.$store.commit('setLoading', false)
    },
    // パスワード変更
    async editPassword (values) {
      await this.$store.dispatch('editPassword', values)
        .then((res) => {
          // 認証失敗時、エラーメッセージ格納
          if (res.status === 401) {
            this.unAuthorized = res.data.message
            this.shakeErrorMessage()
          } else if (res.status === 200) {
            this.flashMessage.show({
              status: 'success',
              title: 'パスワードを変更しました'
            })
            this.unAuthorized = ''
            this.reload()
          } else {
            this.$nuxt.error({ statusCode: res.status })
            this.unAuthorized = ''
            this.reload()
          }
        })
      this.$store.commit('setLoading', false)
    },
    // メールアドレス変更準備
    async preEditEmail (values) {
      await this.$store.dispatch('preEditEmail', values)
        .then((res) => {
          // 認証失敗時、エラーメッセージ格納
          if (res.status === 401) {
            this.unAuthorized = res.data.message
            this.shakeErrorMessage()
          } else if (res.status === 200) {
            this.flashMessage.show({
              status: 'success',
              title: '新しいメールアドレスにメールを送信しました',
              message: '届いたメールに従ってメールアドレスの変更を完了させてください',
              time: 10000
            })
            this.unAuthorized = ''
            this.reload()
          } else {
            this.$nuxt.error({ statusCode: res.status })
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
    },
    // エラーメッセージを一度だけ左右に揺らす
    shakeErrorMessage () {
      this.errorAnimation = true
      setTimeout(() => { this.errorAnimation = false }, 1000)
    }
  }
}
</script>

<style>
.unauthorized {
  animation: yureru-s 2s infinite;
}

@keyframes yureru-s {
  0% {
      transform: translate(2px, 0px);
  }
  5% {
      transform: translate(-2px, 0px);
  }
  10% {
      transform: translate(2px, 0px);
  }
  15% {
      transform: translate(-2px, 0px);
  }
  20% {
      transform: translate(2px, 0px);
  }
  25% {
      transform: translate(-2px, 0px);
  }
  30% {
      transform: translate(0px, 0px);
  }
}
</style>
