<template>
  <div>
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
  </div>
</template>

<script>
import LoginForm from '~/components/organisms/LoginForm.vue'
import RegisterForm from '~/components/organisms/RegisterForm.vue'
export default {
  auth: 'guest',
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
