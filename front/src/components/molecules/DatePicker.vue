<template>
  <v-menu v-model="menu" offset-y :close-on-content-click="false">
    <template v-slot:activator="{ on }">
      <v-btn icon v-on="on">
        <v-icon>mdi-calendar</v-icon>
      </v-btn>
    </template>
    <v-date-picker
      v-model="picker"
      locale="jp-ja"
      :day-format="date => new Date(date).getDate()"
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
      default: new Date().toISOString().substr(0, 10)
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
      set (newVal) {
        this.menu = false
        this.$emit('input', newVal)
      }
    }
  }
}
</script>
