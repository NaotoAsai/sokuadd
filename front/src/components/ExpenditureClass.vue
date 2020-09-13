<template>
  <div>
    <v-text-field
      v-model="newData.name"
      label="Solo"
      placeholder="分類を追加"
      solo-inverted
      class="mr-2 ml-2"
    />
    <div class=" mb-8 pr-12 pl-12">
      <v-btn
        v-if="!newData.name"
        block
        x-large
        color="success"
        dark
        disabled
        @click="create"
      >
        追加
      </v-btn>
      <v-btn
        v-if="newData.name"
        block
        x-large
        color="success"
        dark
        @click="create"
      >
        追加
      </v-btn>
    </div>
    <!-- 分類がない時 -->
    <v-card-text
      v-if="$store.state.incomeAndExpenditureClasses.expenditureClasses.length === 0"
      class="text-h5 text-center"
    >
      分類がありません
    </v-card-text>
    <!-- リストにスクロールバー表示のスタイルを適応 -->
    <v-list subheader style="overflow-y: scroll; height: 550px">
      <transition-group name="list">
        <v-list-item
          v-for="(item, index) in $store.state.incomeAndExpenditureClasses.expenditureClasses"
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
    <!-- 分類名修正ダイアログフォーム -->
    <v-row justify="center">
      <v-dialog v-model="editDialog" persistent max-width="600px">
        <v-card>
          <v-card-title>
            <span class="headline">分類名修正</span>
          </v-card-title>
          <v-card-text>
            <v-form>
              <v-text-field
                v-model="editData.name"
                label="コメント"
                class="ma-12"
                solo-inverted
              />
              <div class=" pb-8 pr-12 pl-12">
                <v-btn block x-large color="success" dark @click="edit">
                  修正
                </v-btn>
              </div>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="editDialog = false">
              やめる
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
    <!-- 削除確認ダイアログ -->
    <v-dialog
      v-model="deleteDialog"
      max-width="290"
    >
      <v-card>
        <v-card-title class="headline">
          本当に削除しますか？
        </v-card-title>

        <v-card-actions>
          <v-spacer />

          <v-btn
            color="green darken-1"
            text
            @click="deleteDialog = false"
          >
            いいえ
          </v-btn>

          <v-btn
            color="green darken-1"
            text
            @click="remove"
          >
            はい
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data () {
    return {
      editDialog: false,
      deleteDialog: false,
      newData: {
        name: '',
        type: 1
      },
      editData: {
        id: '',
        name: '',
        index: ''
      },
      deleteData: {
        id: '',
        index: ''
      }
    }
  },
  methods: {
    async create () {
      const newId = await this.$store.dispatch('createIncomeAndExpenditureClass', this.newData)
      // 配列に追加
      this.$store.commit('addIncomeAndExpenditureClassData', {
        type: 'expenditureClasses',
        id: newId,
        name: this.newData.name
      })
      // テキストエリアを空にする
      this.newData.name = ''
    },
    setEditData (targetId, targetName, index) {
      this.editData.id = targetId
      this.editData.name = targetName
      this.editData.index = index
      this.editDialog = true
    },
    setDeleteData (targetId, index) {
      this.deleteData.id = targetId
      this.deleteData.index = index
      this.deleteDialog = true
    },
    async edit () {
      this.editDialog = false
      await this.$store.dispatch('editIncomeAndExpenditureClass', this.editData)
      // 配列の当該データを更新
      this.$store.commit('updateIncomeAndExpenditureClassData', { type: 'expenditureClasses', index: this.editData.index, name: this.editData.name })
    },
    async remove () {
      this.deleteDialog = false
      await this.$store.dispatch('deleteIncomeAndExpenditureClass', this.deleteData)
      // 配列から当該データ削除
      this.$store.commit('deleteIncomeAndExpenditureClassData', { type: 'expenditureClasses', index: this.deleteData.index })
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
