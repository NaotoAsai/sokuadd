<template>
  <v-card
    max-width="600"
    class="mx-auto"
  >
    <v-toolbar
      dark
    >
      <v-tabs grow>
        <v-tab @click="currentClass = 'ExpenditureClass'">
          支出分類
        </v-tab>
        <v-tab @click="currentClass = 'IncomeClass'">
          収入分類
        </v-tab>
      </v-tabs>
    </v-toolbar>
    <v-card-actions>
      <v-btn to="/" nuxt>
        <v-icon>mdi-arrow-left-bold</v-icon>
        TOPへ
      </v-btn>
    </v-card-actions>

    <component :is="currentClass" />
  </v-card>
</template>

<script>
import ExpenditureClass from '~/components/ExpenditureClass.vue'
import IncomeClass from '~/components/IncomeClass.vue'
export default {
  components: {
    ExpenditureClass,
    IncomeClass
  },
  async fetch (context) {
    await context.store.dispatch('getIncomeAndExpenditureClasses')
  },
  data () {
    return {
      currentClass: 'ExpenditureClass'
    }
  }
}
</script>
