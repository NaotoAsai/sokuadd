<template>
  <div>
    <v-row>
      <v-col md="6" cols="12">
        <ProfileCard />
      </v-col>
      <v-col md="6" cols="12">
        <SettingCard />
      </v-col>
    </v-row>
  </div>
</template>

<script>
import ProfileCard from '~/components/organisms/ProfileCard.vue'
import SettingCard from '~/components/organisms/SettingCard.vue'

export default {
  components: {
    ProfileCard,
    SettingCard
  },
  created () {
    this.$store.commit('changePage', 'mypage')
  }
}
</script>
