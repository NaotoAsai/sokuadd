<template>
  <ValidationProvider
    v-slot="{ errors }"
    rules="required|email|max:255"
    name="メールアドレス"
  >
    <v-text-field
      :error-messages="errors"
      name="email"
      label="メールアドレス"
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
