<template>
  <ValidationProvider
    v-slot="{ errors }"
    rules="required|min:8|max:255"
    name="パスワード"
  >
    <v-text-field
      :error-messages="errors"
      name="password"
      label="パスワード"
      type="password"
      outlined
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
    }
  },
  methods: {
    updateText (newVal) {
      this.$emit('input', newVal)
    }
  }
}
</script>
