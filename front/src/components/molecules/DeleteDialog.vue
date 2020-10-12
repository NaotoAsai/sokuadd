<template>
  <v-dialog
    v-model="dialog"
    max-width="290"
  >
    <v-card>
      <v-card-title class="headline">
        本当に削除しますか？
      </v-card-title>

      <v-card-actions>
        <v-spacer />

        <v-btn
          color="green darken-1"
          text
          @click="dialog = false"
        >
          いいえ
        </v-btn>

        <v-btn
          color="green darken-1"
          text
          @click="remove"
        >
          はい
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  data () {
    return {
      dialog: false
    }
  },
  methods: {
    remove () {
      this.$emit('remove')
    }
  }
}
</script>
