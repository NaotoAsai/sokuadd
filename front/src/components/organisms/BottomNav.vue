<template>
  <!-- ログイン状態でのみ表示 -->
  <v-bottom-navigation
    v-if="$auth.loggedIn"
    v-model="activeBtn"
    app
    grow
    color="teal"
    height="60px"
  >
    <v-btn @click="$router.push({ name: 'index' })">
      <span>追加する</span>
      <v-icon>mdi-pencil-plus</v-icon>
    </v-btn>

    <v-btn @click="$router.push({ name: 'calendar' })">
      <span>カレンダー</span>
      <v-icon>mdi-calendar-month</v-icon>
    </v-btn>

    <v-btn @click="$router.push({ name: 'monthlydatabyclass' })">
      <span>分類別収支</span>
      <v-icon>mdi-format-list-numbered</v-icon>
    </v-btn>

    <v-btn @click="$router.push({ name: 'classedit' })">
      <span>分類を追加</span>
      <v-icon>mdi-playlist-plus</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script>
export default {
  computed: {
    // v-modelでstoreのstateを参照したいのでこの形、参照のみなのでset()は空
    activeBtn: {
      get () {
        return this.$store.state.activeBtn
      },
      set () {

      }
    }
  }
}
</script>
