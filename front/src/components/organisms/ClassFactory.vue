<template>
  <v-card
    max-width="600"
    class="mx-auto"
  >
    <v-toolbar
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
      this.newData.type = this.currentType
      await this.$store.dispatch('createIncomeAndExpenditureClass', this.newData)
      // テキストエリアを空にする
      this.newData.name = ''
      this.flashMessage.show({
        status: 'success',
        title: '分類を追加しました',
        time: 3000
      })
    },
    // 編集する分類名情報を送信
    async edit () {
      this.$refs.classEditForm.dialog = false
      await this.$store.dispatch('editIncomeAndExpenditureClass', this.editData)
      this.flashMessage.show({
        status: 'success',
        title: '分類名を修正しました',
        time: 3000
      })
    },
    // 削除する分類名情報を送信
    async remove () {
      this.$refs.deleteDialog.dialog = false
      await this.$store.dispatch('deleteIncomeAndExpenditureClass', this.deleteData)
      this.flashMessage.show({
        status: 'success',
        title: '分類を削除しました',
        time: 3000
      })
    }
  }
}
</script>
