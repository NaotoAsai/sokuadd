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
    <FlashMessage id="flash" :position="'right top'" />
    <HeaderNav />
    <v-main>
      <v-container>
        <nuxt />
      </v-container>
    </v-main>
    <VueFooter />
    <BottomNav v-if="$vuetify.breakpoint.xs" />
  </v-app>
</template>

<script>
import HeaderNav from '~/components/organisms/HeaderNav.vue'
import BottomNav from '~/components/organisms/BottomNav.vue'
import VueFooter from '~/components/organisms/VueFooter.vue'
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
  #flash {
    z-index:9999;
  }
</style>
