<template>
  <v-card
    max-width="368"
    class="mx-auto"
  >
    <v-toolbar
      color="#2f4f4f"
      dark
    >
      <v-tabs grow>
        <v-tab @click="currentForm = 'RegisterForm'">
          会員登録
        </v-tab>
        <v-tab @click="currentForm = 'LoginForm'">
          ログイン
        </v-tab>
      </v-tabs>
    </v-toolbar>
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
