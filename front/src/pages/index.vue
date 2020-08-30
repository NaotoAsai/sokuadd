<template>
  <div>
    <h2 style="text-align: center;">
      11月11日月曜日
    </h2>
    <v-tabs grow>
      <v-tab @click="currentForm = 'ExpenditureForm'">
        支出
      </v-tab>
      <v-tab @click="currentForm = 'IncomeForm'">
        収入
      </v-tab>
    </v-tabs>

    <component :is="currentForm" />
  </div>
</template>

<script>
import ExpenditureForm from '~/components/ExpenditureForm.vue'
import IncomeForm from '~/components/IncomeForm.vue'
export default {
  components: {
    ExpenditureForm,
    IncomeForm
  },
  data () {
    return {
      currentForm: 'ExpenditureForm'
    }
  }
}
</script>
