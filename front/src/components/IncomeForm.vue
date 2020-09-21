<template>
  <div>
    <h2 class="text-center mt-6">
      {{ newData.targetDate }}
      <DatePicker v-model="newData.targetDate" />
    </h2>
    <v-form>
      <v-text-field
        v-model="newData.amount"
        label="金額"
        class="ma-12"
        solo-inverted
      />
      <v-overflow-btn
        v-model="newData.classId"
        :items="$store.state.incomeAndExpenditureClasses.incomeClasses"
        item-text="name"
        item-value="id"
        placeholder="分類"
        class="ma-12"
      />
      <v-text-field
        v-model="newData.comment"
        label="コメント"
        class="ma-12"
        solo-inverted
      />
      <div class=" pb-8 pr-12 pl-12">
        <v-btn
          block
          x-large
          color="success"
          dark
          @click="create"
        >
          追加
        </v-btn>
      </div>
    </v-form>
  </div>
</template>

<script>
import DatePicker from '~/components/DatePicker.vue'
export default {
  comopnents: {
    DatePicker
  },
  data () {
    return {
      newData: {
        targetDate: this.getToday(),
        amount: '',
        classId: '',
        comment: '',
        type: 0
      }
    }
  },
  methods: {
    getToday () {
      const now = new Date()
      const year = now.getFullYear()
      const month = ('0' + (now.getMonth() + 1)).slice(-2)
      const date = ('0' + now.getDate()).slice(-2)
      return year + '-' + month + '-' + date
    },
    async create () {
      await this.$store.dispatch('createIncomeAndExpenditure', this.newData)
      // テキストエリアを空にする
      this.newData.amount = ''
      this.newData.comment = ''
    }
  }
}
</script>
