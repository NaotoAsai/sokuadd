<template>
  <div>
    <v-card class="mx-auto" max-width="344">
      <v-form>
        <v-text-field name="password" label="パスワード" type="password" />
        <v-text-field name="password_confirmation" label="パスワード(確認)" type="password" />
        <v-card-actions>
          <v-btn primary large block @click="login">
            送信
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </div>
</template>

<script>
export default {
  auth: 'guest'
}
</script>
