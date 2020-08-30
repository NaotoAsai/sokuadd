<template>
  <v-app dark>
    <Navbar />
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
import Navbar from '~/components/Navbar.vue'
import BottomNav from '~/components/BottomNav.vue'
import VueFooter from '~/components/VueFooter.vue'
export default {
  components: {
    Navbar,
    BottomNav,
    VueFooter
  }
}
</script>
