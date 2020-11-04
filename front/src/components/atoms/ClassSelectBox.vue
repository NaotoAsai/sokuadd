<template>
  <v-overflow-btn
    :items="currentTypeClassArray"
    item-text="name"
    item-value="id"
    hide-selected
    placeholder="分類"
    class="mx-12 my-6"
    :value="value"
    @input="updateText"
  />
</template>

<script>
export default {
  props: {
    value: {
      type: [String, Number],
      required: true
    },
    currentType: {
      type: Number,
      default: 1,
      required: true
    }
  },
  computed: {
    currentTypeClassArray () {
      if (this.currentType === 0) {
        return this.$store.state.incomeAndExpenditureClasses.incomeClasses
      } else {
        return this.$store.state.incomeAndExpenditureClasses.expenditureClasses
      }
    }
  },
  methods: {
    updateText (newVal) {
      this.$emit('input', newVal)
    }
  }
}
</script>
