<template>
  <v-row row class="text-xs-center">
    <v-col md="4" offset-md="4" class="lighten-4">
      <v-card flat>
        <v-form>
          <v-text-field
            name="username"
            label="金額"
            class="ma-12"
          />
          <v-overflow-btn
            :items="expenditure_classes"
            placeholder="分類"
            class="ma-12"
          />
          <v-card-actions>
            <v-btn primary large block>
              追加
            </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data () {
    return {
      income_classes: ['aaa', 'bbb', 'ccc']
    }
  }
}
</script>
