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
                <v-list-item-title>名前変更</v-list-item-title>
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
      v-model="drawer"
      app
    >
      <v-list>
        <v-list-item @click="$router.push({ name: 'index' })">
          <v-list-item-action>
            <v-icon>mdi-pencil-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>追加する</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({ name: 'calendar' })">
          <v-list-item-action>
            <v-icon>mdi-calendar-month</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>カレンダー</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({ name: 'classdisp' })">
          <v-list-item-action>
            <v-icon>mdi-format-list-numbered</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>分類別の収支を見る</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item @click="$router.push({ name: 'classedit' })">
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
    }
  },
  methods: {
    ...mapActions(['logout'])
  }
}
</script>
