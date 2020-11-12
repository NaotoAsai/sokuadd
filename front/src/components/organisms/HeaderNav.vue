<template>
  <div>
    <!-- ↓↓ヘッダーナビ↓↓ -->
    <v-app-bar
      app
      color="#20b2aa"
      dark
    >
      <v-app-bar-nav-icon v-if="$auth.loggedIn" @click="drawer = !drawer" />
      <v-toolbar-title class="pl-0" @click="$store.commit('changePage', 'index')">
        <v-img :src="require('@/assets/images/cooltext367075954437377.png')" width="120" height="30" />
      </v-toolbar-title>
      <v-spacer />
      <!-- ↓↓ドロップダウンメニュー↓↓ -->
      <v-toolbar-items v-if="$auth.loggedIn">
        <v-menu offset-y>
          <template v-slot:activator="{on}">
            <!-- v-btnがデフォルトでアルファベットがすべて大文字になるので、回避のためのstyle適応 -->
            <v-btn
              text
              style="text-transform: none"
              v-on="on"
            >
              {{ dispName() }}
              <v-icon>mdi-menu-down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item @click="$store.commit('changePage', 'mypage')">
              <v-list-item-content>
                <v-list-item-title>
                  マイページ
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

    <!-- ↓↓サイドメニュー↓↓ -->
    <v-navigation-drawer
      v-if="$auth.loggedIn"
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
        <v-list-item @click="$store.commit('changePage', 'monthlydatabyclass')">
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
        <v-list-item @click="$store.commit('changePage', 'mypage')">
          <v-list-item-action>
            <v-icon>mdi-account</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>マイページ</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  data () {
    return {
      drawer: null
    }
  },
  methods: {
    // ログアウト
    async logout () {
      await this.$auth.logout('laravelJWT')
    },
    // 名前表示は最大7文字まで
    dispName () {
      const maxLength = 7
      const dispName = this.$auth.user.name
      if (dispName.length > maxLength) {
        return dispName.substr(0, maxLength) + '...'
      } else {
        return dispName
      }
    }
  }
}
</script>
