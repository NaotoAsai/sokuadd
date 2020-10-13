<template>
  <v-card class="mx-auto" max-width="368">
    <v-tabs grow>
      <v-tab @click="currentForm = 'RegisterForm'">
        会員登録
      </v-tab>
      <v-tab @click="currentForm = 'LoginForm'">
        ログイン
      </v-tab>
    </v-tabs>
    <component :is="currentForm" />
  </v-card>
</template>

<script>
import LoginForm from '~/components/molecules/LoginForm.vue'
import RegisterForm from '~/components/molecules/RegisterForm.vue'
export default {
  components: {
    LoginForm,
    RegisterForm
  },
  data () {
    return {
      currentForm: 'RegisterForm'
    }
  }
}
</script>
