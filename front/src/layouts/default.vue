<template>
  <v-app>
    <div v-show="$store.state.loading" id="loading">
      <v-progress-circular
        :size="70"
        :width="7"
        color="green"
        indeterminate
      />
    </div>
    <HeaderNav />
    <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
    <BottomNav v-if="$vuetify.breakpoint.xs" />
    <VueFooter />
  </v-app>
</template>

<script>
import HeaderNav from '~/components/HeaderNav.vue'
import BottomNav from '~/components/BottomNav.vue'
import VueFooter from '~/components/VueFooter.vue'
export default {
  components: {
    HeaderNav,
    BottomNav,
    VueFooter
  }
}
</script>

<style lang="scss">
  #loading {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100vh;
    z-index:9999;
    position: fixed;
    background-color: rgba(#000, 0.5);
  }
</style>
