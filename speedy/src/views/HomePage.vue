<template>
  <ion-page>
    <ion-content :fullscreen="true" class="ion-padding">
      <ion-header>
        <ion-toolbar>
          <ion-title>Home</ion-title>
        </ion-toolbar>
      </ion-header>
      <ion-content class="ion-padding">
        <div id="container">
          <strong>Welcome to Speedy</strong>
          <p>
            A simple app to help you get started with Ionic and Vue.
          </p>
          <ion-button expand="full" @click="goToLogin()">Login</ion-button>
        </div>
      </ion-content>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonContent, IonHeader, IonPage, IonTitle, IonToolbar, IonButton } from '@ionic/vue';
import { useRouter } from 'vue-router';
const router = useRouter();
const goToLogin = () => {
  router.push('/login');
};

</script>

<style scoped></style>
