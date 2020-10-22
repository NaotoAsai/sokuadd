<template>
  <ValidationProvider
    v-slot="{ errors }"
    rules="required|integer|max_value:9999999999999999999999999999999999999999999999999999999999999999"
    name="金額"
  >
    <v-text-field
      :error-messages="errors"
      label="金額"
      class="ma-12"
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
      type: [String, Number],
      required: true
    }
  },
  methods: {
    updateText (newVal) {
      this.$emit('input', newVal)
    }
  }
}
</script>
