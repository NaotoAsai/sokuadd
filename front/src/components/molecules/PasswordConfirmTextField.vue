<template>
  <ValidationProvider
    v-slot="{ errors }"
    rules="required|confirmed:password"
    name="パスワード(確認)"
  >
    <v-text-field
      :error-messages="errors"
      name="password_confirmation"
      label="パスワード(確認)"
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
