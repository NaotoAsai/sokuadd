<template>
  <v-form>
    <v-text-field
      name="username"
      label="金額"
      class="ma-12"
      solo-inverted
    />
    <v-overflow-btn
      :items="income_classes"
      placeholder="分類"
      class="ma-12"
    />
    <div class=" pb-8 pr-12 pl-12">
      <v-btn block x-large color="success" dark>
        追加
      </v-btn>
    </div>
  </v-form>
</template>

<script>
export default {
  data () {
    return {
      income_classes: null
    }
  }
}
</script>
