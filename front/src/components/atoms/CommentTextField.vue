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
      solo-inverted
      class="mx-12 my-6"
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
