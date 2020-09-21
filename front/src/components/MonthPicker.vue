<template>
  <v-menu v-model="menu" offset-y :close-on-content-click="false">
    <template v-slot:activator="{ on }">
      <v-btn icon dark v-on="on">
        <v-icon>mdi-calendar</v-icon>
      </v-btn>
    </template>
    <v-date-picker
      v-model="picker"
      locale="jp-ja"
      type="month"
      color="green lighten-1"
      @click="menu = false"
    />
  </v-menu>
</template>
<script>
export default {
  props: {
    value: {
      type: String,
      default: new Date().toISOString().substr(0, 7)
    }
  },
  data () {
    return {
      menu: false
    }
  },
  computed: {
    picker: {
      get () {
        return this.value
      },
      set (targetYearMonth) {
        // YYYY-mm
        // 月を決めたらメニューを閉じて、親コンポに値を渡す
        this.menu = false
        this.$emit('input', targetYearMonth)
      }
    }
  }
}
</script>
