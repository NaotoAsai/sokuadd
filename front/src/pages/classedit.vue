<template>
  <ClassFactoryCard />
</template>

<script>
import ClassFactoryCard from '~/components/organisms/ClassFactoryCard.vue'

export default {
  components: {
    ClassFactoryCard
  },
  async fetch (context) {
    await context.store.dispatch('getIncomeAndExpenditureClasses')
  }
}
</script>
