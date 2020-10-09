<template>
  <div>
    <!-- ↓↓ヘッダーナビ↓↓ -->
    <v-app-bar
      app
      color="#CCCC99"
      dark
    >
      <v-app-bar-nav-icon v-if="$auth.loggedIn" @click="drawer = !drawer" />
      <v-toolbar-title>即add</v-toolbar-title>
      <v-spacer />
      <!-- ↓↓ドロップダウンメニュー↓↓ -->
      <v-toolbar-items v-if="$auth.loggedIn">
        <v-menu offset-y>
          <template v-slot:activator="{on}">
            <!-- v-btnがデフォルトでアルファベットがすべて大文字になるので、回避のためのstyle適応 -->
            <v-btn text style="text-transform: none" v-on="on">
              {{ $auth.user.name }}
              <v-icon>mdi-menu-down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item @click="$router.push('/setting')">
              <v-list-item-content>
                <v-list-item-title>
                  設定
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <!-- <v-list-item>
              <v-list-item-content>
                <v-list-item-title @click.stop="openEditNameForm">
                  ユーザー名変更
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title @click.stop="openEditEmailForm">
                  メールアドレス変更
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title @click.stop="openEditPasswordForm">
                  パスワード変更
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item> -->
            <v-list-item>
              <v-list-item-content @click="logout">
                <v-list-item-title>ログアウト</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar-items>
    </v-app-bar>

    <!-- ユーザー名変更ダイアログフォーム -->
    <!-- <v-row justify="center">
      <v-dialog v-model="editNameForm" persistent max-width="600px">
        <v-card>
          <v-card-title>
            <span class="headline">ユーザー名変更</span>
          </v-card-title>
          <ValidationObserver ref="obs" v-slot="{ invalid }">
            <v-form
              ref="form"
              class="pa-9"
            >
              <ValidationProvider
                v-slot="{ errors }"
                rules="required|max:32"
                name="ユーザー名"
              >
                <v-text-field
                  v-model="editNameData.name"
                  :counter="32"
                  :error-messages="errors"
                  name="username"
                  label="ユーザー名"
                  outlined
                />
              </ValidationProvider>
              <v-btn
                large
                block
                :disabled="invalid"
                @click="editName"
              >
                変更
              </v-btn>
            </v-form>
          </ValidationObserver>
          <v-card-actions>
            <v-spacer />
            <v-btn color="blue darken-1" text @click="editNameForm = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row> -->

    <!-- パスワード変更ダイアログフォーム -->
    <!-- <v-row justify="center">
      <v-dialog v-model="editPasswordFrom" persistent max-width="600px">
        <v-card v-if="isDone === false">
          <v-card-title>
            パスワード変更
          </v-card-title>
          <v-card-subtitle>
            新しいパスワードを設定してください
          </v-card-subtitle>
          <v-card-text v-if="unAuthorized !== ''" class="red--text">
            {{ unAuthorized }}
          </v-card-text>
          <ValidationObserver ref="obs" v-slot="{ invalid }">
            <v-form
              ref="form"
              class="pa-9"
            >
              <ValidationProvider
                v-slot="{ errors }"
                rules="required|min:8|max:255"
                name="現在のパスワード"
              >
                <v-text-field
                  v-model="editPasswordData.password"
                  :error-messages="errors"
                  name="nowpassword"
                  label="現在のパスワード"
                  type="password"
                  outlined
                />
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                rules="required|min:8|max:255"
                vid="password"
                name="新しいパスワード"
              >
                <v-text-field
                  v-model="editPasswordData.newPassword"
                  :error-messages="errors"
                  name="password"
                  label="新しいパスワード"
                  type="password"
                  outlined
                />
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                rules="required|confirmed:password"
                name="新しいパスワード(確認)"
              >
                <v-text-field
                  v-model="passwordConfirm"
                  :error-messages="errors"
                  name="password_confirmation"
                  label="新しいパスワード(確認)"
                  type="password"
                  outlined
                />
              </ValidationProvider>
              <v-btn
                large
                block
                :disabled="invalid"
                @click="editPassword"
              >
                送信
              </v-btn>
            </v-form>
          </ValidationObserver>
          <v-card-actions>
            <v-spacer />
            <v-btn color="blue darken-1" text @click="editPasswordFrom = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
        <v-card v-if="isDone === true">
          <v-card-title>
            パスワード変更
          </v-card-title>
          <v-card-text>
            パスワードを変更しました
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn color="blue darken-1" text @click="editPasswordFrom = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row> -->

    <!-- メールアドレス変更ダイアログフォーム -->
    <!-- <v-row justify="center">
      <v-dialog v-model="editEmailForm" persistent max-width="600px">
        <v-card v-if="isDone === false">
          <v-card-title>
            メールアドレス変更
          </v-card-title>
          <v-card-subtitle>
            新しいメールアドレスを設定してください
          </v-card-subtitle>
          <v-card-text v-if="unAuthorized !== ''" class="red--text">
            {{ unAuthorized }}
          </v-card-text>
          <ValidationObserver ref="obs" v-slot="{ invalid }">
            <v-form
              ref="form"
              class="pa-9"
            >
              <ValidationProvider
                v-slot="{ errors, valid }"
                rules="required|email|max:255"
                name="メールアドレス"
              >
                <v-text-field
                  v-model="editEmailData.email"
                  :error-messages="errors"
                  :success="valid"
                  name="email"
                  label="メールアドレス"
                  outlined
                />
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                rules="required|min:8|max:255"
                name="現在のパスワード"
              >
                <v-text-field
                  v-model="editEmailData.password"
                  :error-messages="errors"
                  name="nowpassword"
                  label="現在のパスワード"
                  type="password"
                  outlined
                />
              </ValidationProvider>
              <v-btn
                large
                block
                :disabled="invalid"
                @click="preEditEmail"
              >
                送信
              </v-btn>
            </v-form>
          </ValidationObserver>
          <v-card-actions>
            <v-spacer />
            <v-btn color="blue darken-1" text @click="editEmailForm = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
        <v-card v-if="isDone === true">
          <v-card-title>
            メールアドレス変更
          </v-card-title>
          <v-card-text>
            新しいメールアドレスにメールを送信しました。届いたメールに従ってメールアドレスの変更を完了させてください。
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn color="blue darken-1" text @click="editEmailForm = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row> -->

    <!-- ↓↓サイドメニュー↓↓ -->
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <v-list>
        <v-list-item @click="$store.commit('changePage', 'index')">
          <v-list-item-action>
            <v-icon>mdi-pencil-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>追加する</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$store.commit('changePage', 'calendar')">
          <v-list-item-action>
            <v-icon>mdi-calendar-month</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>カレンダー</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$store.commit('changePage', 'classdisp')">
          <v-list-item-action>
            <v-icon>mdi-format-list-numbered</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>分類別の収支を見る</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$store.commit('changePage', 'classedit')">
          <v-list-item-action>
            <v-icon>mdi-playlist-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>分類を追加</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  data () {
    return {
      drawer: null
    //   editNameForm: false,
    //   editPasswordFrom: false,
    //   editEmailForm: false,
    //   editNameData: {
    //     name: this.$auth.user.name
    //   },
    //   editPasswordData: {
    //     password: '',
    //     newPassword: ''
    //   },
    //   editEmailData: {
    //     password: '',
    //     email: this.$auth.user.email
    //   },
    //   passwordConfirm: '',
    //   // 各フォーム、完了状況
    //   isDone: false,
    //   // 現在のパスワード不一致時にエラーメッセージ
    //   unAuthorized: ''
    }
  },
  methods: {
    // openEditNameForm () {
    //   this.editNameData.name = this.$auth.user.name
    //   this.editNameForm = true
    // },
    // openEditPasswordForm () {
    //   this.isDone = false
    //   this.editPasswordFrom = true
    // },
    // openEditEmailForm () {
    //   this.isDone = false
    //   this.editEmailForm = true
    // },
    ...mapActions(['logout'])
    // // ユーザー名変更
    // async editName () {
    //   await this.$store.dispatch('editName', this.editNameData)
    //     .then((res) => {
    //       if (res.status === 200) {
    //         // ストアのステートの値を更新
    //         this.$store.commit('updateUserName', this.editNameData.name)
    //       } else {
    //         this.$nuxt.error({ statusCode: res.status })
    //         // バリデーションエラーメッセージ表示防止
    //         this.$refs.obs.reset()
    //       }
    //     })

    //   this.editNameForm = false
    //   this.$store.commit('setLoading', false)
    // },
    // // パスワード変更
    // async editPassword () {
    //   await this.$store.dispatch('editPassword', this.editPasswordData)
    //     .then((res) => {
    //       // 認証失敗時、エラーメッセージ格納
    //       if (res.status === 401) {
    //         this.unAuthorized = res.data.message
    //       } else if (res.status === 200) {
    //         this.isDone = true
    //         this.editPasswordData.password = ''
    //         this.editPasswordData.newPassword = ''
    //         this.passwordConfirm = ''
    //         this.unAuthorized = ''
    //       } else {
    //         this.$nuxt.error({ statusCode: res.status })
    //         this.editPasswordFrom = false
    //         this.editPasswordData.password = ''
    //         this.editPasswordData.newPassword = ''
    //         this.passwordConfirm = ''
    //         this.unAuthorized = ''
    //         // バリデーションエラーメッセージ表示防止
    //         this.$refs.obs.reset()
    //       }
    //     })
    //   this.$store.commit('setLoading', false)
    // },
    // // メールアドレス変更準備
    // async preEditEmail () {
    //   await this.$store.dispatch('preEditEmail', this.editEmailData)
    //     .then((res) => {
    //       // 認証失敗時、エラーメッセージ格納
    //       if (res.status === 401) {
    //         this.unAuthorized = res.data.message
    //       } else if (res.status === 200) {
    //         this.isDone = true
    //         this.editEmailData.password = ''
    //         this.editEmailData.email = ''
    //         this.passwordConfirm = ''
    //         this.unAuthorized = ''
    //       } else {
    //         this.$nuxt.error({ statusCode: res.status })
    //         this.editEmailForm = false
    //         this.editEmailData.password = ''
    //         // this.editEmailData.email = ''
    //         this.passwordConfirm = ''
    //         this.unAuthorized = ''
    //         // バリデーションエラーメッセージ表示防止
    //         this.$refs.obs.reset()
    //       }
    //     })
    //   this.$store.commit('setLoading', false)
    // }
  }
}
</script>
