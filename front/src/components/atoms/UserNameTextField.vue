<template>
  <ValidationProvider
    v-slot="{ errors }"
    rules="required|max:32"
    name="ユーザー名"
  >
    <v-text-field
      :counter="32"
      :error-messages="errors"
      name="username"
      label="ユーザー名"
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
