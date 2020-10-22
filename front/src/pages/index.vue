<template>
  <AddIncomeAndExpenditureCard />
</template>

<script>
import AddIncomeAndExpenditureCard from '~/components/organisms/AddIncomeAndExpenditureCard.vue'

export default {
  components: {
    AddIncomeAndExpenditureCard
  },
  // クラス選択用のデータ取得
  async fetch (context) {
    await context.store.dispatch('getIncomeAndExpenditureClasses')
  }
}
</script>
