<template>
  <ValidationObserver ref="obs" v-slot="{ invalid }">
    <v-form
      ref="form"
    >
      <p>現在のパスワード</p>
      <PasswordTextField
        v-model="values.password"
      />
      <p>新しいパスワード</p>
      <PasswordTextField
        v-model="values.newPassword"
      />
      <PasswordConfirmTextField
        v-model="passwordConfirm"
      />
      <v-btn
        large
        block
        :disabled="invalid"
        @click="send"
      >
        送信
      </v-btn>
    </v-form>
  </ValidationObserver>
</template>

<script>
import PasswordTextField from '~/components/atoms/PasswordTextField.vue'
import PasswordConfirmTextField from '~/components/atoms/PasswordConfirmTextField.vue'
export default {
  components: {
    PasswordTextField,
    PasswordConfirmTextField
  },
  data () {
    return {
      values: {
        password: '',
        newPassword: ''
      },
      passwordConfirm: ''
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
