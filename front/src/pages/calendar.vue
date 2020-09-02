<template>
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
          <!-- 表示形式選択メニュー
          <v-menu bottom right>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                outlined
                color="grey darken-2"
                v-bind="attrs"
                v-on="on"
              >
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right>
                  mdi-menu-down
                </v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>Day</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>Week</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>Month</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 days</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu> -->
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
        <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card :color="selectedEvent.color">
            <v-card-title>支出情報：計 600円</v-card-title>
            <v-card-subtitle>2020-05-04</v-card-subtitle>
            <!-- その日の収支情報の配列をfor文で回す -->
            <v-list three-line subheader>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>食費：-600円</v-list-item-title>
                  <v-list-item-subtitle>銀の皿のまかないで食べた寿司だい、文字数多めにしてみる</v-list-item-subtitle>
                </v-list-item-content>

                <!-- スマホサイズ以外の編集削除アイコン -->
                <v-list-item-action v-if="!$vuetify.breakpoint.xs">
                  <v-btn icon>
                    <v-icon color="grey lighten-1">
                      mdi-lead-pencil
                    </v-icon>
                  </v-btn>
                </v-list-item-action>
                <v-list-item-action v-if="!$vuetify.breakpoint.xs">
                  <v-btn icon>
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
                      <v-list-item-title>編集</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
                      <v-list-item-title>削除</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>食費：-600円</v-list-item-title>
                  <v-list-item-subtitle>銀の皿のまかない</v-list-item-subtitle>
                </v-list-item-content>

                <v-list-item-action v-if="!$vuetify.breakpoint.xs">
                  <v-btn icon>
                    <v-icon color="grey lighten-1">
                      mdi-lead-pencil
                    </v-icon>
                  </v-btn>
                </v-list-item-action>
                <v-list-item-action v-if="!$vuetify.breakpoint.xs">
                  <v-btn icon>
                    <v-icon color="grey lighten-1">
                      mdi-delete
                    </v-icon>
                  </v-btn>
                </v-list-item-action>

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
                      <v-list-item-title>編集</v-list-item-title>
                    </v-list-item>
                    <v-list-item>
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
        </v-menu>
      </v-sheet>
    </v-col>
  </v-row>
</template>

<script>
export default {
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
    // イベントで使う色
    colors: ['blue lighten-1', 'red lighten-1'],
    // イベント名、今回はその日の合計額をその日のイベント名として動的に生成したい
    names: ['-500', '+400', '-600', '+300']
  }),
  mounted () {
    this.$refs.calendar.checkChange()
  },
  methods: {
    getEventColor (event) {
      return event.color
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
    updateRange ({ start, end }) {
      const events = []

      // この辺はサンプル用
      const min = new Date(`${start.date}T00:00:00`)
      const max = new Date(`${end.date}T23:59:59`)
      const days = (max.getTime() - min.getTime()) / 86400000
      // 収支情報の個数、今回は最大月の日数×2
      const eventCount = this.rnd(days, days + 20)

      for (let i = 0; i < eventCount; i++) {
        // const allDay = this.rnd(0, 3) === 0
        const firstTimestamp = this.rnd(min.getTime(), max.getTime())
        const first = new Date(firstTimestamp - (firstTimestamp % 900000))
        // const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
        // const second = new Date(first.getTime() + secondTimestamp)
        events.push({
          name: this.names[this.rnd(0, this.names.length - 1)],
          // 時間の情報は持たせなきゃいけない仕様、timedがfalseの場合startだけでOK(Wed Sep 16 2020 03:00:00 GMT+0900 (日本標準時))
          start: first,
          // end: second,
          // 今回色は支出（赤）収入（青）の2色のみもつ
          color: this.colors[this.rnd(0, this.colors.length - 1)],
          // 時刻を表示するときにtrue、今回は時刻情報は持たないのでfalse
          timed: false
        })
      }

      this.events = events
    },
    rnd (a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a
    }
  }
}
</script>
