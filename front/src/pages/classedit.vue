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
      <v-btn>
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
  data: () => ({
    currentClass: 'ExpenditureClass',
    items: [
      { id: '0', title: '食費' },
      { id: '1', title: '固定費' },
      { id: '2', title: '娯楽' },
      { id: '3', title: '食費' },
      { id: '4', title: '固定費' },
      { id: '5', title: '娯楽' },
      { id: '8', title: '食費' },
      { id: '9', title: '固定費' },
      { id: '10', title: '娯楽' },
      { id: '11', title: '食費' },
      { id: '12', title: '固定費' },
      { id: '13', title: '娯楽' },
      { id: '14', title: '食費' },
      { id: '15', title: '固定費' },
      { id: '16', title: '娯楽' },
      { id: '17', title: '食費' },
      { id: '18', title: '固定費' },
      { id: '19', title: '娯楽' }
    ]
  })
}
</script>
