<template>
  <ion-page>
    <ion-content :fullscreen="true" class="ion-padding">
      <div class="center">
        <h1>
          <strong>Welcome to Speedy</strong>
        </h1>
        <p>A simple app to help you get started with Ionic and Vue.</p>
      </div>
    </ion-content>
  </ion-page>
</template>

<script setup lang="ts">
import { useRouter } from "vue-router";
import { onMounted } from "vue";

const router = useRouter();

// Redirect to login page after 5 seconds
onMounted(() => {
  setTimeout(() => {
    router.push('/login');
  }, 5000); // 5000 milliseconds = 5 seconds
});
</script>

<style scoped>
ion-page {
  display: flex;
  flex-direction: column;
  height: 100vh;
  justify-content: normal;
  background-color: #250902;
}

ion-button {
  width: 100%;
  margin: 0.25rem;
}
</style>
