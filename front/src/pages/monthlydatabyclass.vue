<template>
  <MonthlyDataByClass
    :value="oneMonthData"
  />
</template>

<script>
import MonthlyDataByClass from '~/components/organisms/MonthlyDataByClass.vue'
export default {
  comopnents: {
    MonthlyDataByClass
  },
  async asyncData (context) {
    // コンポーネント生成前にAPIから指定月の分類別収支データ取得
    // 子コンポーネントへ渡す
    const now = new Date()
    const nowYear = now.getFullYear()
    const nowMonth = now.getMonth() + 1
    const url = '/api/v1/incomeandexpendituresbyclass'
    const targetMonth = { year: nowYear, month: nowMonth }
    const res = await context.$axios.$get(url, { params: targetMonth })
    return { oneMonthData: res }
  },
  created () {
    this.$store.commit('changePage', 'monthlydatabyclass')
  }
}
</script>
