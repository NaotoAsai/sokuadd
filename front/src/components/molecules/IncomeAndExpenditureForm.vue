<template>
  <div>
    <ValidationObserver ref="obs" v-slot="{ invalid }">
      <v-form @submit.prevent="send">
        <AmountTextField
          v-model="values.amount"
        />
        <ClassSelectBox
          v-model="values.classId"
          :current-type="currentType"
        />
        <CommentTextField
          v-model="values.comment"
        />
        <div class=" pb-8 pr-12 pl-12">
          <v-btn
            block
            x-large
            color="success"
            :disabled="invalid"
            type="submit"
          >
            追加
          </v-btn>
        </div>
      </v-form>
    </ValidationObserver>
  </div>
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
    }
  },
  comopnents: {
    AmountTextField,
    ClassSelectBox,
    CommentTextField
  },
  data () {
    return {
      values: {
        amount: '',
        classId: '',
        comment: ''
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
