<template>
  <ClassFactory />
</template>

<script>
import ClassFactory from '~/components/organisms/ClassFactory.vue'

export default {
  components: {
    ClassFactory
  },
  async fetch (context) {
    await context.store.dispatch('getIncomeAndExpenditureClasses')
  }
}
</script>
