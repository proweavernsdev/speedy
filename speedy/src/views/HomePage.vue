<template>
  <ion-page>
    <ion-content :fullscreen="true" class="ion-padding">
      <div class="center">
        <h1>
          <strong>Welcome to Speedy</strong>
        </h1>
        <p>A simple app to help you get started with Ionic and Vue.</p>
        <ion-button expand="full" @click="router.push('/login')"
          >Login</ion-button
        >
        <ion-button
          expand="full"
          @click="router.push('/register')"
          color="secondary"
          >Register</ion-button
        >
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { IonContent, IonPage, IonButton } from "@ionic/vue";
import { useRouter } from "vue-router";
const router = useRouter();
</script>

<style scoped>
ion-page {
  display: flex;
  flex-direction: column;
  height: 100vh;
  justify-content: normal;
}

ion-button {
  width: 100%;
  margin: 0.25rem;
}
</style>
