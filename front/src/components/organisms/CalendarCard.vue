<template>
  <div>
    <v-row class="fill-height">
      <v-col>
        <v-sheet height="64">
          <v-toolbar flat>
            <!-- 前月へ移動 -->
            <v-btn fab text small color="grey darken-2" @click="prev">
              <v-icon small>
                mdi-chevron-left
              </v-icon>
            </v-btn>
            <!-- 翌月へ移動 -->
            <v-btn fab text small color="grey darken-2" @click="next">
              <v-icon small>
                mdi-chevron-right
              </v-icon>
            </v-btn>
            <!-- 年号、月、表示 -->
            <v-toolbar-title v-if="$refs.calendar">
              {{ $refs.calendar.title }}
            </v-toolbar-title>
            <v-spacer />
          </v-toolbar>
        </v-sheet>
        <v-sheet height="600">
          <!-- カレンダー本体 -->
          <v-calendar
            ref="calendar"
            v-model="focus"
            color="primary"
            :events="events"
            :event-color="getEventColor"
            type="month"
            @click:event="showEvent"
            @change="updateRange"
          />
          <!-- イベントカード -->
          <v-dialog
            v-model="selectedOpen"
            :close-on-content-click="false"
            max-width="400"
          >
            <v-card :color="colors[selectedEvent.type]">
              <v-card-title class="white--text">
                {{ cardDispTitle[selectedEvent.type] }}：{{ selectedEvent.name }}円
              </v-card-title>
              <v-card-subtitle class="white--text">
                {{ selectedEvent.start }}
              </v-card-subtitle>
              <!-- その日の収支情報の配列をfor文で回す -->
              <v-list class="ma-2">
                <v-list-item
                  v-for="item in selectedEvent.items"
                  :key="item.id"
                  three-line
                  subheader
                >
                  <v-list-item-content>
                    <v-list-item-title>
                      {{ item.className }}：{{ item.amount }}円
                    </v-list-item-title>
                    <v-list-item-subtitle>
                      {{ item.comment }}
                    </v-list-item-subtitle>
                  </v-list-item-content>

                  <!-- スマホサイズ以外の編集削除アイコン -->
                  <v-list-item-action v-if="!$vuetify.breakpoint.xs">
                    <v-btn
                      icon
                      @click.stop="setEditData(item, selectedEvent.start)"
                    >
                      <v-icon color="grey lighten-1">
                        mdi-lead-pencil
                      </v-icon>
                    </v-btn>
                  </v-list-item-action>
                  <v-list-item-action v-if="!$vuetify.breakpoint.xs">
                    <v-btn
                      icon
                      @click.stop="setDeleteData(item.id, selectedEvent.start)"
                    >
                      <v-icon color="grey lighten-1">
                        mdi-delete
                      </v-icon>
                    </v-btn>
                  </v-list-item-action>

                  <!-- スマホサイズ時の編集削除は、縦三連ドットアイコンからメニュー表示 -->
                  <v-menu v-if="$vuetify.breakpoint.xs" offset-x left>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        icon
                        v-on="on"
                      >
                        <v-icon>mdi-dots-vertical</v-icon>
                      </v-btn>
                    </template>

                    <v-list>
                      <v-list-item>
                        <v-list-item-title @click="setEditData(item, selectedEvent.start)">
                          編集
                        </v-list-item-title>
                      </v-list-item>
                      <v-list-item @click="setDeleteData(item.id, selectedEvent.start)">
                        <v-list-item-title>削除</v-list-item-title>
                      </v-list-item>
                    </v-list>
                  </v-menu>
                </v-list-item>
              </v-list>
              <v-card-actions>
                <v-btn
                  class="white--text"
                  text
                  @click="selectedOpen = false"
                >
                  やめる
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-sheet>
      </v-col>
    </v-row>

    <!-- 削除確認ダイアログ -->
    <DeleteDialog
      ref="deleteDialog"
      @remove="remove"
    />

    <!-- 収支情報修正ダイアログフォーム -->
    <IncomeAndExpenditureEditForm
      ref="incomeAndExpenditureEditForm"
      :current-type="currentType"
      :target-data="targetData"
      @send="edit($event)"
    />
  </div>
</template>

<script>
import DeleteDialog from '~/components/molecules/DeleteDialog.vue'
import IncomeAndExpenditureEditForm from '~/components/molecules/IncomeAndExpenditureEditForm.vue'

export default {
  components: {
    DeleteDialog,
    IncomeAndExpenditureEditForm
  },
  data: () => ({
    // 月移動時に月末日がここに入る
    focus: '',
    // 収支クリック時、対象の収支データを格納する
    selectedEvent: {},
    selectedElement: null,
    // 収支クリック時の詳細カードの状態
    selectedOpen: false,
    // ここに収支データがはいる
    events: [],
    cardDispTitle: ['収入情報', '支出情報'],
    // イベントで使う色
    colors: ['blue lighten-1', 'red lighten-1'],
    deleteDialog: false,
    editDialog: false,
    deleteData: {
      id: '',
      year: '',
      month: ''
    },
    targetData: {
      amount: '',
      classId: '',
      comment: ''
    },
    currentType: 1,
    editData: {
      id: '',
      targetDate: '',
      amount: '',
      classId: '',
      comment: '',
      year: '',
      month: ''
    }
  }),
  mounted () {
    this.$refs.calendar.checkChange()
  },
  methods: {
    getEventColor (event) {
      return this.colors[event.type]
    },
    prev () {
      this.$refs.calendar.prev()
    },
    next () {
      this.$refs.calendar.next()
    },
    // 収支詳細カード表示
    // 引数はまうすマウスイベント、イベントデータ
    showEvent ({ nativeEvent, event }) {
      const open = () => {
        this.selectedEvent = event
        this.selectedElement = nativeEvent.target
        setTimeout(() => this.selectedOpen = true, 10)// eslint-disable-line no-return-assign
      }

      // もし他の詳細カードが開いていれば
      if (this.selectedOpen) {
        // カードが同時に開くのを防止するため、閉じてから開く
        this.selectedOpen = false
        setTimeout(open, 10)
      } else {
        open()
      }

      // 伝搬防止
      nativeEvent.stopPropagation()
    },
    // ここで収支データを格納、引数に取得したい期間を渡す、今回は一ヶ月分
    async updateRange ({ start, end }) {
      // ここでAPIから指定月の収支データを取得しストアで保持する
      const url = '/api/v1/incomeandexpenditures'
      const targetMonth = { year: start.year, month: start.month }
      const events = await this.$axios.$get(url, { params: targetMonth })

      this.events = events
    },
    // 削除対象データセット
    setDeleteData (targetId, targetDate) {
      const year = targetDate.substr(0, 4)
      let month = ''
      if (targetDate.substr(5, 1) === '0') {
        month = targetDate.substr(6, 1)
      } else {
        month = targetDate.substr(5, 2)
      }
      this.deleteData.id = targetId
      this.deleteData.year = year
      this.deleteData.month = month
      this.$refs.deleteDialog.dialog = true
    },
    setEditData (targetItem, targetDate) {
      // 分類セレクトボックス用に編集データのタイプを取得
      this.currentType = this.selectedEvent.type
      // 編集データの現在の値をtargetDataに格納し、子コンポーネントへ渡す
      this.targetData.amount = targetItem.amount
      // atomsの仕様でnullを渡せない
      if (targetItem.classId !== null) {
        this.targetData.classId = targetItem.classId
      } else {
        this.targetData.classId = ''
      }
      if (targetItem.comment !== null) {
        this.targetData.comment = targetItem.comment
      } else {
        this.targetData.comment = ''
      }
      const year = targetDate.substr(0, 4)
      let month = ''
      if (targetDate.substr(5, 1) === '0') {
        month = targetDate.substr(6, 1)
      } else {
        month = targetDate.substr(5, 2)
      }
      this.editData.id = targetItem.id
      this.editData.targetDate = targetDate
      this.editData.year = year
      this.editData.month = month
      this.$refs.incomeAndExpenditureEditForm.dialog = true
    },
    // 削除して、新しいデータをセット
    async remove () {
      this.$store.commit('setLoading', true)

      const url = '/api/v1/incomeandexpenditures'
      const params = this.deleteData

      // カレンダーの収支データ再描画のため収支情報を受け取る
      const events = await this.$axios.$delete(url, { data: params })
      this.events = events
      // 開いているイベントカード再描画のため配列を更新する
      const updatedSelectedEvent = this.events.find((val) => {
        return val.start === this.selectedEvent.start
      })
      // 要素が無くなれば、カードを閉じる
      if (updatedSelectedEvent === undefined) {
        this.selectedOpen = false
      } else {
        this.selectedEvent = updatedSelectedEvent
      }

      this.$refs.deleteDialog.dialog = false
      this.flashMessage.show({
        status: 'success',
        title: '収支情報を削除しました',
        time: 3000
      })
      this.$store.commit('setLoading', false)
    },
    // 収支データの編集
    async edit (values) {
      this.$store.commit('setLoading', true)

      // 子コンポーネントから受け取ったパラメータをマージ
      Object.assign(this.editData, values)

      const url = '/api/v1/incomeandexpenditures'
      const params = this.editData

      // カレンダーの収支データ再描画のため収支情報を受け取る
      const events = await this.$axios.$put(url, params)
      this.events = events
      // 開いているイベントカード再描画のため配列を更新する
      const updatedSelectedEvent = this.events.find((val) => {
        return val.start === this.selectedEvent.start
      })
      this.selectedEvent = updatedSelectedEvent

      this.$refs.incomeAndExpenditureEditForm.dialog = false
      this.flashMessage.show({
        status: 'success',
        title: '収支情報を修正しました',
        time: 3000
      })

      this.$store.commit('setLoading', false)
    }
  }
}
</script>
