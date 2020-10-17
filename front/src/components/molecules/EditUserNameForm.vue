<template>
  <ValidationObserver ref="obs" v-slot="{ invalid }">
    <v-form
      ref="form"
    >
      <UserNameTextField
        :value="value"
        @input="updateText"
      />
      <v-btn
        large
        block
        :disabled="invalid"
        @click="send"
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
