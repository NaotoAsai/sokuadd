<template>
  <!-- <v-btn to="/" nuxt>
    <v-icon>mdi-arrow-left-bold</v-icon>
    TOPへ
  </v-btn> -->
  <v-btn @click="$store.commit('changePage', 'index')">
    <v-icon>mdi-arrow-left-bold</v-icon>
    TOPへ
  </v-btn>
</template>
