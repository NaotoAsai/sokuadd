<template>
  <v-card
    max-width="600"
    class="mx-auto"
  >
    <v-toolbar dark>
      <v-tabs grow>
        <v-tab @click="currentType = 1">
          支出
        </v-tab>
        <v-tab @click="currentType = 0">
          収入
        </v-tab>
      </v-tabs>
    </v-toolbar>
    <h2 class="text-center mt-6">
      {{ newData.targetDate }}
      <DatePicker v-model="newData.targetDate" />
    </h2>
    <!-- 追加するたびフォームコンポーネントをリロードし、状態をリセットする -->
    <IncomeAndExpenditureForm
      v-if="reloadFlag === false"
      ref="incomeAndExpenditureForm"
      :current-type="currentType"
      @send="create($event)"
    />
  </v-card>
</template>

<script>
import DatePicker from '~/components/molecules/DatePicker.vue'
import IncomeAndExpenditureForm from '~/components/molecules/IncomeAndExpenditureForm.vue'

export default {
  components: {
    DatePicker,
    IncomeAndExpenditureForm
  },
  data () {
    return {
      reloadFlag: false,
      currentType: 1,
      newData: {
        targetDate: this.getToday(),
        type: '',
        amount: '',
        classId: '',
        comment: ''
      }
    }
  },
  methods: {
    // 初期描画時に今日の日付を取得しセット
    getToday () {
      const now = new Date()
      const year = now.getFullYear()
      const month = ('0' + (now.getMonth() + 1)).slice(-2)
      const date = ('0' + now.getDate()).slice(-2)
      return year + '-' + month + '-' + date
    },
    async create (values) {
      this.newData.type = this.currentType
      // 子コンポーネントから受け取ったパラメータをマージ
      Object.assign(this.newData, values)
      await this.$store.dispatch('createIncomeAndExpenditure', this.newData)
      // テキストエリアを空にする
      // this.$refs.incomeAndExpenditureForm.values.amount = ''
      // this.$refs.incomeAndExpenditureForm.values.comment = ''
      // バリデーションエラーメッセージ表示防止（送信ボタンにdisabledがかからない）
      // this.$refs.incomeAndExpenditureForm.$refs.obs.reset()
      // ↓↓これに代替
      this.reload()
      this.flashMessage.show({
        status: 'success',
        title: '収支情報を追加しました',
        time: 3000
      })
    },
    // Formコンポーネントを再描画する（入力やバリデーション状態をリセットするため）
    reload () {
      this.reloadFlag = true
      this.$nextTick(() => {
        this.reloadFlag = false
      })
    }
  }
}
</script>
