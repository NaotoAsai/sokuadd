<template>
  <ValidationProvider
    rules="required|max:32"
    name="分類名"
  >
    <v-text-field
      :class="classValue"
      :counter="32"
      label="Solo"
      placeholder="分類を追加"
      solo-inverted
      :value="value"
      @input="updateText"
    />
  </ValidationProvider>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      required: true
    },
    classValue: {
      type: String,
      default: null
    }
  },
  methods: {
    updateText (newVal) {
      this.$emit('input', newVal)
    }
  }
}
</script>
