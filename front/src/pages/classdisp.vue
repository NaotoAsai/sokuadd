<template>
  <div>
    <div>
      <h2 class="text-center mt-6">
        {{ dispYear }}年{{ dispMonth }}月
        <MonthPicker v-model="selectMonth" />
      </h2>
    </div>
    <v-row>
      <v-col cols="6">
        <v-card>
          <v-list two-line subheader>
            <v-subheader>収入</v-subheader>
            <v-list-item
              v-for="item in oneMonthData.incomes.classes"
              :key="item.name"
            >
              <v-list-item-content>
                <v-list-item-title>{{ item.name }}</v-list-item-title>
                <v-list-item-subtitle>{{ item.amount }}円</v-list-item-subtitle>
                <v-divider />
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>合計</v-list-item-title>
                <v-list-item-subtitle>{{ oneMonthData.incomes.totalAmount }}円</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
      <v-col cols="6">
        <v-card>
          <v-list two-line subheader>
            <v-subheader>支出</v-subheader>
            <v-list-item
              v-for="item in oneMonthData.expenditures.classes"
              :key="item.name"
            >
              <v-list-item-content>
                <v-list-item-title>{{ item.name }}</v-list-item-title>
                <v-list-item-subtitle>{{ item.amount }}円</v-list-item-subtitle>
                <v-divider />
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>合計</v-list-item-title>
                <v-list-item-subtitle>{{ oneMonthData.expenditures.totalAmount }}円</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-card class="pa-4">
          トータル収支： {{ oneMonthData.totalAmount }}円
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import MonthPicker from '~/components/MonthPicker.vue'
export default {
  comopnents: {
    MonthPicker
  },
  async asyncData (context) {
    // コンポーネント生成前にAPIから指定月の分類別収支データ取得
    const now = new Date()
    const nowYear = now.getFullYear()
    const nowMonth = now.getMonth() + 1
    const url = '/api/v1/incomeandexpendituresbyclass'
    const targetMonth = { year: nowYear, month: nowMonth }
    const res = await context.$axios.$get(url, { params: targetMonth })
    return { oneMonthData: res }
  },
  data () {
    return {
      selectMonth: this.getYearMonth(),
      oneMonthData: []
    }
  },
  computed: {
    // 表示、データ取得時パラメータ用年月
    dispYear () {
      return this.selectMonth.substr(0, 4)
    },
    dispMonth () {
      if (this.selectMonth.substr(5, 1) === '0') {
        return this.selectMonth.substr(6, 1)
      }
      return this.selectMonth.substr(5, 2)
    }
  },
  watch: {
    // MonthPickerで年月指定時に当該データ取得
    selectMonth: function (month) { // eslint-disable-line
      const nowYear = this.dispYear
      const nowMonth = this.dispMonth
      const targetMonth = { year: nowYear, month: nowMonth }
      const url = '/api/v1/incomeandexpendituresbyclass'
      this.$axios.$get(url, { params: targetMonth }).then((res) => { this.oneMonthData = res })
    }
  },
  methods: {
    // ページ読み込み時、現在の年月取得、MonthPickerと同期される
    getYearMonth () {
      const now = new Date()
      const year = now.getFullYear()
      const month = ('0' + (now.getMonth() + 1)).slice(-2)
      return year + '-' + month
    }
  }
}
</script>
