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
          >
            <!-- <v-menu
            v-model="selectedOpen"
            :close-on-content-click="false"
            :activator="selectedElement"
            offset-x
          > -->
            <v-card :color="colors[selectedEvent.type]">
              <v-card-title>{{ cardDispTitle[selectedEvent.type] }}：{{ selectedEvent.name }}円</v-card-title>
              <v-card-subtitle>{{ selectedEvent.start }}</v-card-subtitle>
              <!-- その日の収支情報の配列をfor文で回す -->
              <v-list
                v-for="item in selectedEvent.items"
                :key="item.id"
                three-line
                subheader
              >
                <v-list-item>
                  <v-list-item-content>
                    <v-list-item-title>{{ item.className }}：{{ item.amount }}円</v-list-item-title>
                    <v-list-item-subtitle>{{ item.comment }}</v-list-item-subtitle>
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
                        dark
                        icon
                        v-on="on"
                      >
                        <v-icon>mdi-dots-vertical</v-icon>
                      </v-btn>
                    </template>

                    <v-list>
                      <v-list-item>
                        <v-list-item-title @click.stop="setEditData(item, selectedEvent.start)">
                          編集
                        </v-list-item-title>
                      </v-list-item>
                      <v-list-item @click.stop="setDeleteData(item.id, selectedEvent.start)">
                        <v-list-item-title>削除</v-list-item-title>
                      </v-list-item>
                    </v-list>
                  </v-menu>
                </v-list-item>
              </v-list>
              <v-card-actions>
                <v-btn
                  text
                  color="secondary"
                  @click="selectedOpen = false"
                >
                  Cancel
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-sheet>
      </v-col>
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

    <!-- 収支情報修正ダイアログフォーム -->
    <v-row justify="center">
      <v-dialog v-model="editDialog" persistent max-width="600px">
        <v-card>
          <v-card-title>
            <span class="headline">収支情報修正</span>
          </v-card-title>
          <v-card-text>
            <v-form>
              <v-text-field
                v-model="editData.amount"
                label="金額"
                class="ma-12"
                solo-inverted
              />
              <v-overflow-btn
                v-model="editData.classId"
                :items="$store.state.incomeAndExpenditureClasses.expenditureClasses"
                item-text="name"
                item-value="id"
                placeholder="分類"
                class="ma-12"
              />
              <v-text-field
                v-model="editData.comment"
                label="コメント"
                class="ma-12"
                solo-inverted
              />
              <div class=" pb-8 pr-12 pl-12">
                <v-btn
                  block
                  x-large
                  color="success"
                  dark
                  @click="edit"
                >
                  追加
                </v-btn>
              </div>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn color="blue darken-1" text @click="editDialog = false">
              Close
            </v-btn>
            <v-btn color="blue darken-1" text @click="editDialog = false">
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>

<script>
export default {
  async fetch (context) {
    await context.store.dispatch('getIncomeAndExpenditureClasses')
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
      this.deleteDialog = true
    },
    setEditData (targetItem, targetDate) {
      const year = targetDate.substr(0, 4)
      let month = ''
      if (targetDate.substr(5, 1) === '0') {
        month = targetDate.substr(6, 1)
      } else {
        month = targetDate.substr(5, 2)
      }
      this.editData.id = targetItem.id
      this.editData.targetDate = targetDate
      this.editData.amount = targetItem.amount
      this.editData.classId = targetItem.classId
      this.editData.comment = targetItem.comment
      this.editData.year = year
      this.editData.month = month
      this.editDialog = true
    },
    // 削除して、新しいデータをセット
    async remove () {
      this.deleteDialog = false
      const events = await this.$store.dispatch('deleteIncomeAndExpenditure', this.deleteData)
      this.events = events
      this.selectedOpen = false
    },
    async edit () {
      this.editDialog = false
      const events = await this.$store.dispatch('editIncomeAndExpenditure', this.editData)
      this.events = events
      this.selectedOpen = false
    }
  }
}
</script>
