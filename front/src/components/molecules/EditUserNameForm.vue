<template>
  <ValidationObserver ref="obs" v-slot="{ invalid }">
    <v-form
      ref="form"
      @submit.prevent="send"
    >
      <UserNameTextField
        :value="value"
        @input="updateText"
      />
      <v-btn
        large
        block
        :disabled="invalid"
        type="submit"
      >
        変更
      </v-btn>
    </v-form>
  </ValidationObserver>
</template>

<script>
import UserNameTextField from '~/components/atoms/UserNameTextField.vue'
export default {
  components: {
    UserNameTextField
  },
  props: {
    value: {
      type: String,
      required: true
    }
  },
  methods: {
    updateText (newVal) {
      this.$emit('input', newVal)
    },
    send () {
      this.$emit('send')
    }
  }
}
</script>
