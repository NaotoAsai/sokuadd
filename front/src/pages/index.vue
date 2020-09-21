<template>
  <v-card
    max-width="600"
    class="mx-auto"
  >
    <v-toolbar>
      <v-tabs grow>
        <v-tab @click="currentForm = 'ExpenditureForm'">
          支出
        </v-tab>
        <v-tab @click="currentForm = 'IncomeForm'">
          収入
        </v-tab>
      </v-tabs>
    </v-toolbar>

    <component :is="currentForm" />
  </v-card>
</template>

<script>
import ExpenditureForm from '~/components/ExpenditureForm.vue'
import IncomeForm from '~/components/IncomeForm.vue'
export default {
  components: {
    ExpenditureForm,
    IncomeForm
  },
  // クラス選択用のデータ取得
  async fetch (context) {
    await context.store.dispatch('getIncomeAndExpenditureClasses')
  },
  data () {
    return {
      currentForm: 'ExpenditureForm'
    }
  }
}
</script>
