<template>
  <v-card
    max-width="600"
    class="mx-auto"
  >
    <v-toolbar
      color="#2f4f4f"
      dark
    >
      <v-tabs grow>
        <v-tab @click="currentType = 1">
          支出分類
        </v-tab>
        <v-tab @click="currentType = 0">
          収入分類
        </v-tab>
      </v-tabs>
    </v-toolbar>
    <ClassForm
      ref="classForm"
      v-model="newData.name"
      @send="create"
    />

    <ClassList
      :current-type="currentType"
      @setEditData="setEditData($event)"
      @setDeleteData="setDeleteData($event)"
    />

    <!-- 分類名修正ダイアログフォーム -->
    <ClassEditForm
      ref="classEditForm"
      v-model="editData.name"
      @send="edit"
    />
    <!-- 削除確認ダイアログ -->
    <DeleteDialog
      ref="deleteDialog"
      @remove="remove"
    />
  </v-card>
</template>

<script>
import ClassForm from '~/components/molecules/ClassForm.vue'
import ClassList from '~/components/molecules/ClassList.vue'
import ClassEditForm from '~/components/molecules/ClassEditForm.vue'
import DeleteDialog from '~/components/molecules/DeleteDialog.vue'

export default {
  components: {
    ClassForm,
    ClassList,
    ClassEditForm,
    DeleteDialog
  },
  data () {
    return {
      currentType: 1,
      newData: {
        name: '',
        type: ''
      },
      editData: {
        id: '',
        name: '',
        index: '',
        type: ''
      },
      deleteData: {
        id: '',
        index: '',
        type: ''
      }
    }
  },
  methods: {
    // 編集対象の分類名情報をセット
    setEditData (values) {
      this.editData.id = values.id
      this.editData.name = values.name
      this.editData.index = values.index
      this.editData.type = this.currentType
      this.$refs.classEditForm.dialog = true
    },
    // 削除対象の分類名情報をセット
    setDeleteData (values) {
      this.deleteData.id = values.id
      this.deleteData.index = values.index
      this.deleteData.type = this.currentType
      this.$refs.deleteDialog.dialog = true
    },
    // 分類名の新規作成
    async create () {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/incomeandexpenditure_classes'
      this.newData.type = this.currentType
      const params = this.newData

      await this.$axios.$post(url, params)
        .then((newId) => {
          // 配列に追加
          this.$store.commit('addIncomeAndExpenditureClassData', {
            type: this.newData.type === 0 ? 'incomeClasses' : 'expenditureClasses',
            id: newId,
            name: this.newData.name
          })
          // テキストエリアを空にする
          this.newData.name = ''
          this.flashMessage.show({
            status: 'success',
            title: '分類を追加しました',
            time: 3000
          })
        })

      this.$store.commit('setLoading', false)
    },
    // 編集する分類名情報を送信
    async edit () {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/incomeandexpenditure_classes'
      const params = this.editData

      await this.$axios.$put(url, params)
        .then(() => {
          // 配列の当該データを更新
          this.$store.commit('updateIncomeAndExpenditureClassData', {
            type: this.editData.type === 0 ? 'incomeClasses' : 'expenditureClasses',
            index: this.editData.index,
            name: this.editData.name
          })
          this.$refs.classEditForm.dialog = false
          this.flashMessage.show({
            status: 'success',
            title: '分類名を修正しました',
            time: 3000
          })
        })

      this.$store.commit('setLoading', false)
    },
    // 削除する分類名情報を送信
    async remove () {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/incomeandexpenditure_classes'
      const params = this.deleteData

      await this.$axios.$delete(url, { data: params })
        .then(() => {
          // 配列から当該データ削除
          this.$store.commit('deleteIncomeAndExpenditureClassData', { type: this.deleteData.type === 0 ? 'incomeClasses' : 'expenditureClasses', index: this.deleteData.index })
          this.$refs.deleteDialog.dialog = false
          this.flashMessage.show({
            status: 'success',
            title: '分類を削除しました',
            time: 3000
          })
        })

      this.$store.commit('setLoading', false)
    }
  }
}
</script>
