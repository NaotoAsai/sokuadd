<template>
  <!-- 収支情報修正ダイアログフォーム -->
  <v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="400px">
      <v-card>
        <v-card-title>
          <span class="headline">収支情報修正</span>
        </v-card-title>
        <ValidationObserver ref="obs" v-slot="{ invalid }">
          <v-form>
            <AmountTextField
              v-model="targetData.amount"
            />
            <ClassSelectBox
              v-model="targetData.classId"
              :current-type="currentType"
            />
            <CommentTextField
              v-model="targetData.comment"
            />
            <div class=" pb-8 pr-12 pl-12">
              <v-btn
                block
                x-large
                color="success"
                :disabled="invalid"
                @click="send"
              >
                修正
              </v-btn>
            </div>
          </v-form>
        </ValidationObserver>
        <v-card-actions>
          <v-btn color="blue darken-1" text @click="dialog = false">
            やめる
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import AmountTextField from '~/components/atoms/AmountTextField.vue'
import ClassSelectBox from '~/components/atoms/ClassSelectBox.vue'
import CommentTextField from '~/components/atoms/CommentTextField.vue'
export default {
  props: {
    currentType: {
      type: Number,
      default: 1,
      required: true
    },
    targetData: {
      type: Object,
      default: () => ({
        amount: '',
        classId: '',
        comment: ''
      }),
      required: false
    }
  },
  comopnents: {
    AmountTextField,
    ClassSelectBox,
    CommentTextField
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
      this.$emit('send', this.targetData)
    }
  }
}
</script>
