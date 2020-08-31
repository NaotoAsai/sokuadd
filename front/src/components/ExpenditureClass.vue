<template>
  <v-list two-line subheader>
    <v-text-field
      label="Solo"
      placeholder="分類を追加"
      solo-inverted
      class="mr-2 ml-2"
    />
    <div class=" mb-8 pr-12 pl-12">
      <v-btn block x-large color="success" dark>
        追加
      </v-btn>
    </div>

    <v-list-item
      v-for="item in items"
      :key="item.id"
    >
      <v-list-item-content>
        <v-list-item-title v-text="item.title" />
      </v-list-item-content>

      <v-list-item-action>
        <v-btn icon>
          <v-icon color="grey lighten-1">
            mdi-lead-pencil
          </v-icon>
        </v-btn>
      </v-list-item-action>
      <v-list-item-action>
        <v-btn icon>
          <v-icon color="grey lighten-1">
            mdi-delete
          </v-icon>
        </v-btn>
      </v-list-item-action>
    </v-list-item>
  </v-list>
</template>

<script>
export default {
  data: () => ({
    items: [
      { id: '0', title: '食費' },
      { id: '1', title: '固定費' },
      { id: '2', title: '娯楽' },
      { id: '3', title: '食費' },
      { id: '4', title: '固定費' },
      { id: '5', title: '娯楽' },
      { id: '8', title: '食費' },
      { id: '9', title: '固定費' },
      { id: '10', title: '娯楽' },
      { id: '11', title: '食費' },
      { id: '12', title: '固定費' },
      { id: '13', title: '娯楽' },
      { id: '14', title: '食費' },
      { id: '15', title: '固定費' },
      { id: '16', title: '娯楽' },
      { id: '17', title: '食費' },
      { id: '18', title: '固定費' },
      { id: '19', title: '娯楽' }
    ]
  })
}
</script>
