<template>
  <ion-page>
    <HeaderComponent
      title="Account"
      headerIcon="person-circle"
      backIcon="chevron-back-outline"
      navigatePathonRight="account_management"
      navigatePathonLeft="admin_dashboard"
    />

    <div class="ion-padding">
      <h1>Admin Dashboard</h1>
    </div>
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage } from "@ionic/vue";
import HeaderComponent from "../main_components/HeaderComponent.vue";
</script>

<style scoped>
ion-page {
  display: flex;
  flex-direction: column;
  height: 100vh;
  justify-content: normal;
}
</style>
