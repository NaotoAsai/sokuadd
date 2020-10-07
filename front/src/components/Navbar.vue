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
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title @click.stop="dialog2 = true">
                  名前変更
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title @click.stop="dialog4 = true">
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
            </v-list-item>
            <v-list-item>
              <v-list-item-content @click="logout">
                <v-list-item-title>ログアウト</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar-items>
    </v-app-bar>

    <!-- 名前変更ダイアログフォーム -->
    <v-row justify="center">
      <v-dialog v-model="dialog2" persistent max-width="600px">
        <v-card>
          <v-card-title>
            <span class="headline">ユーザー名変更</span>
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field label="新しいユーザー名" required />
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn color="blue darken-1" text @click="dialog2 = false">
              Close
            </v-btn>
            <v-btn color="blue darken-1" text @click="dialog2 = false">
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- パスワード変更ダイアログフォーム -->
    <v-row justify="center">
      <v-dialog v-model="dialog3" persistent max-width="600px">
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
              v-model="valid"
              class="pa-9"
            >
              <ValidationProvider
                v-slot="{ errors }"
                rules="required|min:8|max:255"
                name="現在のパスワード"
              >
                <v-text-field
                  v-model="newPasswordData.password"
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
                  v-model="newPasswordData.newPassword"
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
            <v-btn color="blue darken-1" text @click="dialog3 = false">
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
            <v-btn color="blue darken-1" text @click="dialog3 = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <!-- メールアドレス変更ダイアログフォーム -->
    <v-row justify="center">
      <v-dialog v-model="dialog4" persistent max-width="600px">
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
              v-model="valid"
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
            <v-btn color="blue darken-1" text @click="dialog4 = false">
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
            <v-btn color="blue darken-1" text @click="dialog4 = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

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
      drawer: null,
      dialog2: false,
      dialog3: false,
      dialog4: false,
      newPasswordData: {
        password: '',
        newPassword: ''
      },
      editEmailData: {
        password: '',
        email: this.$auth.user.email
      },
      passwordConfirm: '',
      isDone: false,
      unAuthorized: ''
    }
  },
  methods: {
    openEditPasswordForm () {
      this.isDone = false
      this.dialog3 = true
    },
    openEditEmailForm () {
      this.isDone = false
      this.dialog4 = true
    },
    ...mapActions(['logout']),
    // パスワード変更
    async editPassword () {
      await this.$store.dispatch('editPassword', this.newPasswordData)
        .then((res) => {
          // 認証失敗時、エラーメッセージ格納
          if (res.status === 401) {
            this.unAuthorized = res.data.message
          } else if (res.status === 200) {
            this.isDone = true
            this.newPasswordData.password = ''
            this.newPasswordData.newPassword = ''
            this.passwordConfirm = ''
            this.unAuthorized = ''
          } else {
            this.$nuxt.error({ statusCode: res.status })
            this.dialog3 = false
            this.newPasswordData.password = ''
            this.newPasswordData.newPassword = ''
            this.passwordConfirm = ''
            this.unAuthorized = ''
            // バリデーションエラーメッセージ表示防止
            this.$refs.obs.reset()
          }
        })
      this.$store.commit('setLoading', false)
    },
    // メールアドレス変更準備
    async preEditEmail () {
      await this.$store.dispatch('preEditEmail', this.editEmailData)
        .then((res) => {
          // 認証失敗時、エラーメッセージ格納
          if (res.status === 401) {
            this.unAuthorized = res.data.message
          } else if (res.status === 200) {
            this.isDone = true
            this.editEmailData.password = ''
            this.editEmailData.email = ''
            this.passwordConfirm = ''
            this.unAuthorized = ''
          } else {
            this.$nuxt.error({ statusCode: res.status })
            this.dialog4 = false
            this.editEmailData.password = ''
            // this.editEmailData.email = ''
            this.passwordConfirm = ''
            this.unAuthorized = ''
            // バリデーションエラーメッセージ表示防止
            this.$refs.obs.reset()
          }
        })
      this.$store.commit('setLoading', false)
    }
  }
}
</script>
