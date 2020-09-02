<template>
  <div>
    <h2 class="text-center mt-6">
      {{ selectDay }}
      <DatePicker v-model="selectDay" />
    </h2>
    <v-form>
      <v-text-field
        label="金額"
        class="ma-12"
        solo-inverted
      />
      <v-overflow-btn
        :items="expenditure_classes"
        placeholder="分類"
        class="ma-12"
      />
      <v-text-field
        label="コメント"
        class="ma-12"
        solo-inverted
      />
      <div class=" pb-8 pr-12 pl-12">
        <v-btn block x-large color="success" dark>
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
      expenditure_classes: ['aaa', 'bbb', 'ccc'],
      selectDay: this.getToday()
    }
  },
  methods: {
    getToday () {
      const now = new Date()
      const year = now.getFullYear()
      const month = ('0' + (now.getMonth() + 1)).slice(-2)
      const date = ('0' + now.getDate()).slice(-2)
      return year + '-' + month + '-' + date
    }
  }
}
</script>
