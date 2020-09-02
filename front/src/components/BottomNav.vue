<template>
  <v-bottom-navigation
    v-if="$auth.loggedIn"
    grow
    color="teal"
    height="90px"
    absolute
    :input-value="false"
  >
    <v-btn @click="$router.push({ name: 'index' })">
      <span>追加する</span>
      <v-icon>mdi-pencil-plus</v-icon>
    </v-btn>

    <v-btn @click="$router.push({ name: 'calendar' })">
      <span>カレンダー</span>
      <v-icon>mdi-calendar-month</v-icon>
    </v-btn>

    <v-btn @click="$router.push({ name: 'classdisp' })">
      <span>分類別の収支を見る</span>
      <v-icon>mdi-format-list-numbered</v-icon>
    </v-btn>
  </v-bottom-navigation>
</template>

<script>
export default {
  data () {
    return {
      activeBtn: 1
    }
  }
}
</script>
