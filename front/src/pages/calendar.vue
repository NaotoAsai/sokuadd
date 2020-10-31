<template>
  <CalendarCard />
</template>

<script>
import CalendarCard from '~/components/organisms/CalendarCard.vue'

export default {
  components: {
    CalendarCard
  },
  async fetch (context) {
    await context.store.dispatch('getIncomeAndExpenditureClasses')
  },
  created () {
    this.$store.commit('changePage', 'calendar')
  }
}
</script>
