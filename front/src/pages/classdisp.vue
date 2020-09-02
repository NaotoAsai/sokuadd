<template>
  <div>
    <h2 class="mt-1 mb-10">
      分類別収支
    </h2>

    <div class="date-btn">
      <v-btn><v-icon>mdi-chevron-left</v-icon></v-btn>
      2020年05月
      <v-btn><v-icon>mdi-chevron-right</v-icon></v-btn>
    </div>

    <v-row class="ma-1">
      <v-spacer />
      <v-col md="5" cols="12">
        <v-card>
          <v-card-title>
            支出
          </v-card-title>
          <v-list>
            <v-list-item>
              <v-list-item-content>
                食費
              </v-list-item-content>
              <v-list-item-content>
                3000円
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                ガソリン
              </v-list-item-content>
              <v-list-item-content>
                5000円
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                固定費
              </v-list-item-content>
              <v-list-item-content>
                20000円
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
      <v-col md="5" cols="12">
        <v-card>
          <v-card-title>
            収入
          </v-card-title>
          <v-list>
            <v-list-item>
              <v-list-item-content>
                食費
              </v-list-item-content>
              <v-list-item-content>
                3000円
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                ガソリン
              </v-list-item-content>
              <v-list-item-content>
                5000円
              </v-list-item-content>
            </v-list-item>
            <v-list-item>
              <v-list-item-content>
                固定費
              </v-list-item-content>
              <v-list-item-content>
                20000円
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-card>
      </v-col>
      <v-spacer />
    </v-row>
    <v-row class="ma-1">
      <v-spacer />
      <v-col md="4" cols="12">
        <v-card class="pa-4">
          合計： 10000円
        </v-card>
      </v-col>
      <v-spacer />
    </v-row>
  </div>
</template>

<script>

</script>

<style scoped>
h2 {
  text-align: center;
}
.date-btn {
  width: 224px;
  margin: 0 auto;
}
</style>
