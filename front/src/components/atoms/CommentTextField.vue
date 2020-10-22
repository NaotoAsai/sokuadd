<template>
  <ValidationProvider
    v-slot="{ errors }"
    rules="max:64"
    name="コメント"
  >
    <v-text-field
      :counter="64"
      :error-messages="errors"
      label="コメント"
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
