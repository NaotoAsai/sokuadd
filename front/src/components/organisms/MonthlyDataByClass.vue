<template>
  <v-card max-width="800" class="mx-auto pa-md-6">
    <v-card-title class="justify-center text-md-h5">
      {{ dispYear }}年{{ dispMonth }}月
      <MonthPicker v-model="selectMonth" />
    </v-card-title>
    <v-row>
      <!-- 収入カード -->
      <v-col cols="6">
        <v-list two-line subheader>
          <v-subheader class="text-md-h6">
            収入
          </v-subheader>
          <div v-if="oneMonthData !== ''">
            <v-list-item
              v-for="item in oneMonthData.incomes.classes"
              :key="item.name"
            >
              <v-list-item-content>
                <v-list-item-title class="text-md-h5">
                  {{ item.name }}
                </v-list-item-title>
                <v-list-item-subtitle class="text-md-h5">
                  {{ item.amount }}円
                </v-list-item-subtitle>
                <v-divider />
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="text-md-h5">
                  合計
                </v-list-item-title>
                <v-list-item-subtitle class="text-md-h5">
                  {{ oneMonthData.incomes.totalAmount }}円
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </div>
        </v-list>
      </v-col>
      <!-- 支出カード -->
      <v-col cols="6">
        <v-list two-line subheader>
          <v-subheader class="text-md-h6">
            支出
          </v-subheader>
          <div v-if="oneMonthData !== ''">
            <v-list-item
              v-for="item in oneMonthData.expenditures.classes"
              :key="item.name"
            >
              <v-list-item-content>
                <v-list-item-title class="text-md-h5">
                  {{ item.name }}
                </v-list-item-title>
                <v-list-item-subtitle class="text-md-h5">
                  {{ item.amount }}円
                </v-list-item-subtitle>
                <v-divider />
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title class="text-md-h5">
                  合計
                </v-list-item-title>
                <v-list-item-subtitle class="text-md-h5">
                  {{ oneMonthData.expenditures.totalAmount }}円
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </div>
        </v-list>
      </v-col>
    </v-row>
    <!-- 合計収支カード -->
    <v-card-text class="text-center text-h5 text-md-h4">
      合計収支： {{ oneMonthData.totalAmount }}円
    </v-card-text>
  </v-card>
</template>

<script>
import MonthPicker from '~/components/molecules/MonthPicker.vue'
export default {
  props: {
    value: {
      type: Object,
      required: true
    }
  },
  comopnents: {
    MonthPicker
  },
  data () {
    return {
      selectMonth: this.getYearMonth(),
      oneMonthData: this.value
    }
  },
  computed: {
    // 表示用かつデータ取得時パラメータ用年月
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
      this.$axios.$get(url, { params: targetMonth })
        .then((res) => { this.oneMonthData = res })
        .catch((err) => { this.$nuxt.error({ statusCode: err.status }) })
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
