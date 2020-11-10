<template>
  <div>
    <!-- 分類がない時 -->
    <v-card-text
      v-if="currentTypeClassArray.length === 0"
      class="text-h5 text-center"
    >
      分類がありません
    </v-card-text>
    <!-- リストにスクロールバー表示のスタイルを適応 -->
    <v-list subheader style="overflow-y: scroll; height: 400px">
      <transition-group name="list">
        <v-list-item
          v-for="(item, index) in currentTypeClassArray"
          :key="item.id"
        >
          <v-list-item-content>
            <v-list-item-title v-text="item.name" />
          </v-list-item-content>

          <v-list-item-action>
            <v-btn icon @click="setEditData(item.id, item.name, index)">
              <v-icon color="grey lighten-1">
                mdi-lead-pencil
              </v-icon>
            </v-btn>
          </v-list-item-action>
          <v-list-item-action>
            <v-btn icon @click="setDeleteData(item.id, index)">
              <v-icon color="grey lighten-1">
                mdi-delete
              </v-icon>
            </v-btn>
          </v-list-item-action>
        </v-list-item>
      </transition-group>
    </v-list>
  </div>
</template>

<script>
export default {
  props: {
    currentType: {
      type: Number,
      default: 1,
      required: true
    }
  },
  computed: {
    currentTypeClassArray () {
      if (this.currentType === 0) {
        return this.$store.state.incomeAndExpenditureClasses.incomeClasses
      } else {
        return this.$store.state.incomeAndExpenditureClasses.expenditureClasses
      }
    }
  },
  methods: {
    setEditData (id, name, index) {
      const values = {
        id,
        name,
        index
      }
      this.$emit('setEditData', values)
    },
    setDeleteData (id, index) {
      const values = {
        id,
        index
      }
      this.$emit('setDeleteData', values)
    }
  }
}
</script>

<style>
.list-enter-active, .list-leave-active {
  transition: all 0.3s;
}
.list-enter, .list-leave-to /* .list-leave-active for below version 2.1.8 */ {
  opacity: 0;
  transform: translateX(30px);
}
</style>
