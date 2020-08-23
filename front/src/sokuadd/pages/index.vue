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
import IncomeForm from '~/components/IncomeForm.vue'
import ExpenditureForm from '~/components/ExpenditureForm.vue'
export default {
  components: {
    IncomeForm,
    ExpenditureForm
  },
  data () {
    return {
      currentForm: 'ExpenditureForm'
    }
  }
}
</script>

<style>
/* Sample `apply` at-rules with Tailwind CSS
.container {
  @apply min-h-screen flex justify-center items-center text-center mx-auto;
}
*/
.container {
  margin: 0 auto;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.title {
  font-family: 'Quicksand', 'Source Sans Pro', -apple-system, BlinkMacSystemFont,
    'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  display: block;
  font-weight: 300;
  font-size: 100px;
  color: #35495e;
  letter-spacing: 1px;
}

.subtitle {
  font-weight: 300;
  font-size: 42px;
  color: #526488;
  word-spacing: 5px;
  padding-bottom: 15px;
}

.links {
  padding-top: 15px;
}
</style>
