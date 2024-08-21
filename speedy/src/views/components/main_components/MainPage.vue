<template>
  <ion-page>
    <HeaderComponent title="Dashboard" buttonText="Profile" navigatePath="account" class="sticky-header" />
    <ion-content class="main-content">
      <router-view></router-view>
    </ion-content>
    <FooterComponent footerText="Footer" class="sticky-footer" />
  </ion-page>
</template>


<script setup lang="ts">
import HeaderComponent from "./HeaderComponent.vue";
import FooterComponent from "./FooterComponent.vue";
</script>

<style scoped>
.sticky-header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

.main-content {
  padding-top: 56px;
  padding-bottom: 56px;
  overflow-y: auto;
  flex: 1;
}

.sticky-footer {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}
</style>
