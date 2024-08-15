<template>
    <ion-header>
        <ion-toolbar>
            <ion-icon id="leftIcon" :name="backIcon" slot="start" @click="navigateTo" v-show="backIcon"></ion-icon>
            <ion-title class="ion-padding">{{ title }}</ion-title>
            <ion-icon id="rightIcon" :name="headerIcon" slot="end" @click="navigateTo"></ion-icon>
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
    navigatePath: String,
    backIcon: String
});

const router = useRouter();

const navigateTo = () => {
    console.log('navigateTo: ', props.navigatePath);
    router.push(`/${props.navigatePath}`);
};
</script>

<style scoped>
#rightIcon {
    font-size: 32px;
    color: #ad2831;
    cursor: pointer;
    margin: 0 16px;
}

#leftIcon {
    font-size: 32px;
    color: #ad2831;
    cursor: pointer;
    margin-left: 16px;
}
</style>
