<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="400px">
      <v-card>
        <v-card-title>
          <span class="headline">分類名修正</span>
        </v-card-title>
        <v-card-text>
          <ValidationObserver ref="obs" v-slot="{ invalid }">
            <v-form @submit.prevent="send">
              <ClassTextField
                :class-value="'ma-6'"
                :value="value"
                @input="updateText"
              />
              <div class="ma-6">
                <v-btn
                  block
                  x-large
                  color="success"
                  :disabled="invalid"
                  type="submit"
                >
                  修正
                </v-btn>
              </div>
            </v-form>
          </ValidationObserver>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="dialog = false">
            やめる
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import ClassTextField from '~/components/atoms/ClassTextField.vue'
export default {
  components: {
    ClassTextField
  },
  props: {
    value: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      dialog: false
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
