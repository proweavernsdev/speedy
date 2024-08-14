<template>
    <ion-header>
        <ion-toolbar>
            <ion-title slot="start">{{ title }}</ion-title>
            <ion-icon :name="headerIcon" slot="end" @click="navigateTo"></ion-icon>
        </ion-toolbar>
    </ion-header>
</template>

<script setup lang="ts">
import { IonHeader, IonToolbar, IonTitle } from '@ionic/vue';
import { defineProps } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
    title: String,
    headerIcon: String,
    navigatePath: String
});

const router = useRouter();

const navigateTo = () => {
    console.log('navigateTo: ', props.navigatePath);
    router.push(`/${props.navigatePath}`);
};
</script>

<style scoped>
ion-icon {
    font-size: 32px;
    color: #ad2831;
    cursor: pointer;
    margin-right: 16px;

}
</style>
