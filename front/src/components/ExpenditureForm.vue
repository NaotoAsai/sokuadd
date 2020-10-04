<template>
  <div>
    <h2 class="text-center mt-6">
      {{ newData.targetDate }}
      <DatePicker v-model="newData.targetDate" />
    </h2>
    <ValidationObserver ref="obs" v-slot="{ invalid }">
      <v-form>
        <ValidationProvider
          v-slot="{ errors }"
          rules="required|integer|max_value:9999999999999999999999999999999999999999999999999999999999999999"
          name="金額"
        >
          <v-text-field
            v-model="newData.amount"
            :error-messages="errors"
            label="金額"
            class="ma-12"
            solo-inverted
          />
        </ValidationProvider>
        <v-overflow-btn
          v-model="newData.classId"
          :items="$store.state.incomeAndExpenditureClasses.expenditureClasses"
          item-text="name"
          item-value="id"
          hide-selected
          placeholder="分類"
          class="ma-12"
        />
        <ValidationProvider
          v-slot="{ errors }"
          rules="max:64"
          name="コメント"
        >
          <v-text-field
            v-model="newData.comment"
            :counter="64"
            :error-messages="errors"
            label="コメント"
            class="ma-12"
            solo-inverted
          />
        </ValidationProvider>
        <div class=" pb-8 pr-12 pl-12">
          <v-btn
            block
            x-large
            color="success"
            :disabled="invalid"
            @click="create"
          >
            追加
          </v-btn>
        </div>
      </v-form>
    </ValidationObserver>
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
        type: 1
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
      // バリデーションエラーメッセージ表示防止
      this.$refs.obs.reset()
    }
  }
}
</script>
