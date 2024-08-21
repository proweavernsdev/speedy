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
      <h1>Customization Management</h1>
    </div>

    <FooterComponent
      :button1Text="'Dashboard'"
      :button1Link="'admin_dashboard'"
      :button1Icon="'home'"
      :button2Text="'Users'"
      :button2Link="'users'"
      :button2Icon="'people'"
      :button3Text="'Custom'"
      :button3Link="'customization'"
      :button3Icon="'options'"
      :button4Text="'Settings'"
      :button4Link="'settings'"
      :button4Icon="'settings'"
    />
  </ion-page>
</template>

<script setup lang="ts">
import { IonPage } from "@ionic/vue";
import HeaderComponent from "../main_components/HeaderComponent.vue";
import FooterComponent from "../main_components/FooterComponent.vue";
</script>

<style></style>
