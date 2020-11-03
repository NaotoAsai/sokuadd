<template>
  <div>
    <v-card-actions>
      <HomeButton />
    </v-card-actions>
    <ValidationObserver ref="obs" v-slot="{ invalid }">
      <v-form @submit.prevent="send">
        <ClassTextField
          :class-value="'mr-2 ml-2'"
          :value="value"
          @input="updateText"
        />
        <div class=" mb-8 pr-12 pl-12">
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
