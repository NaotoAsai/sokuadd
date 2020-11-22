<template>
  <ValidationProvider
    v-slot="{ errors }"
    rules="required|integer|min_value:1|max_value:999999999"
    name="金額"
  >
    <v-text-field
      v-if="!$vuetify.breakpoint.xs"
      :error-messages="errors"
      label="金額"
      solo-inverted
      class="mx-12 my-6"
      :value="value"
      @input="updateText"
    />
    <div id="numeric-input-wrapper">
      <NumericInput
        v-if="$vuetify.breakpoint.xs"
        class="numeric-input"
        placeholder="金額"
        :error-messages="errors"
        :value="value"
        @input="updateText"
        @focus="hideBottomNav"
        @blur="showBottomNav"
      />
    </div>
  </ValidationProvider>
</template>

<script>
import { NumericInput } from 'numeric-keyboard'
export default {
  components: {
    NumericInput
  },
  props: {
    value: {
      type: [String, Number],
      required: true
    }
  },
  data () {
    return {
      amount: ''
    }
  },
  methods: {
    hideBottomNav () {
      this.$store.commit('toggleBottomNav', false)
    },
    showBottomNav () {
      this.$store.commit('toggleBottomNav', true)
    },
    updateText (newVal) {
      this.$emit('input', newVal)
    }
  }
}
</script>
