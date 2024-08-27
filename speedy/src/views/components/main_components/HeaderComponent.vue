<template>
  <ion-header>
    <ion-toolbar color="primary" class="tw-flex tw-justify-between">
      <div>
        <ion-icon id="leftIcon" :name="backIcon" slot="start" @click="navigateTo('leftNav')"
          v-show="backIcon"></ion-icon>
        <ion-title class="ion-padding tw-text-white">{{ title }}</ion-title>
      </div>
      <ion-icon id="rightIcon" :name="headerIcon" slot="end" @click="navigateTo('rightNav')"
        :v-show="headerIcon"></ion-icon>
    </ion-toolbar>
  </ion-header>
</template>

<script setup lang="ts">
import { IonHeader, IonToolbar, IonTitle, IonIcon } from '@ionic/vue';
import { defineProps } from "vue";
import { useRouter } from "vue-router";

const props = defineProps({
  title: String,
  headerIcon: String,
  backIcon: String,
  navigatePathonRight: String,
  navigatePathonLeft: String,
});

const router = useRouter();

function navigateTo(state: string) {
  if (state === "rightNav") {
    console.log(props.navigatePathonRight);
    router.push("/" + props.navigatePathonRight);
  } else if (state === "leftNav") {
    console.log(props.navigatePathonLeft);
    router.push("/" + props.navigatePathonLeft);
  } else {
    router.push({ name: "Home" });
  }
}
</script>

<style scoped>
#rightIcon {
  font-size: 32px;
  color: #ffffff;
  cursor: pointer;
  margin: 8px 16px;
}

#leftIcon {
  font-size: 32px;
  color: #ffffff;
  cursor: pointer;
  margin: 8px 16px;
}
</style>
