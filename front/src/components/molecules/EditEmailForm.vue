<template>
  <ValidationObserver ref="obs" v-slot="{ invalid }">
    <v-form
      ref="form"
      @submit.prevent="send"
    >
      <EmailTextField
        v-model="values.email"
      />
      <PasswordTextField
        v-model="values.password"
      />
      <v-btn
        large
        block
        :disabled="invalid"
        type="submit"
      >
        送信
      </v-btn>
    </v-form>
  </ValidationObserver>
</template>

<script>
import PasswordTextField from '~/components/atoms/PasswordTextField.vue'
import EmailTextField from '~/components/atoms/EmailTextField.vue'

export default {
  components: {
    PasswordTextField,
    EmailTextField
  },
  data () {
    return {
      values: {
        password: '',
        email: this.$auth.user.email
      }
    }
  },
  methods: {
    updateText (newVal) {
      this.$emit('input', newVal)
    },
    send () {
      this.$emit('send', this.values)
    }
  }
}
</script>
