<template>
  <div>
    <v-dialog
      v-model="firstDialog"
      width="500"
    >
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          dark
          block
          color="red"
          v-bind="attrs"
          v-on="on"
        >
          退会する
        </v-btn>
      </template>

      <v-card>
        <v-card-title>
          本当に退会しますか？
        </v-card-title>
        <v-card-text>
          退会すると全てのデータが削除され復元することができなくなります。
        </v-card-text>
        <v-card-actions>
          <v-btn
            color="primary"
            text
            @click="firstDialog = false"
          >
            やめる
          </v-btn>
          <v-spacer />
          <v-btn
            color="red"
            text
            @click="openForm"
          >
            パスワードを入力して退会する
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="secondDialog"
      width="500"
    >
      <v-card>
        <v-card-title>
          <span class="headline">退会最終確認</span>
        </v-card-title>
        <v-card-text
          v-if="unAuthorized !== ''"
          cols="12"
          class="red--text py-0"
          :class="{ unauthorized:$store.state.errorAnimation }"
        >
          {{ unAuthorized }}
        </v-card-text>
        <ValidationObserver ref="obs" v-slot="{ invalid }">
          <v-form
            ref="form"
            class="pa-6"
            @submit.prevent="send"
          >
            <p>パスワード</p>
            <PasswordTextField
              v-model="values.password"
            />
            <v-btn
              color="red"
              class="white--text"
              block
              :disabled="invalid"
              type="submit"
            >
              退会
            </v-btn>
          </v-form>
        </ValidationObserver>
        <v-card-actions>
          <v-btn
            color="primary"
            text
            @click="secondDialog = false"
          >
            やめる
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: {
    unAuthorized: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      firstDialog: false,
      secondDialog: false,
      values: {
        password: ''
      }
    }
  },
  methods: {
    openForm () {
      this.secondDialog = true
    },
    updateText (newVal) {
      this.$emit('input', newVal)
    },
    send () {
      this.$emit('send', this.values)
    }
  }
}
</script>
