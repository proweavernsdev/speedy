<template>
  <ion-page>
    <HeaderComponent
      title="Dashboard"
      buttonText="Profile"
      navigatePath="account"
    />
    <router-view></router-view>
    <FooterComponent footerText="Footer" />
  </ion-page>
</template>

<script setup lang="ts">
import HeaderComponent from "./HeaderComponent.vue";
import FooterComponent from "./FooterComponent.vue";
</script>

<style></style>
