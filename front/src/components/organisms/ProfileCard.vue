<template>
  <v-card>
    <v-card-title>
      プロフィール
    </v-card-title>
    <v-divider class="mx-4" />
    <v-card-subtitle>
      ユーザー名
    </v-card-subtitle>
    <v-card-text>
      {{ $auth.user.name }}
    </v-card-text>
    <v-card-subtitle>
      メールアドレス
    </v-card-subtitle>
    <v-card-text>
      {{ $auth.user.email }}
    </v-card-text>
  </v-card>
</template>
