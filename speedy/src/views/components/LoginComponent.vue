<template>
    <ion-page>
        <ion-content :fullscreen="true" class="ion-padding">
            <ion-card class="ion-padding">
                <ion-card-header>
                    <ion-card-title>Login</ion-card-title>
                </ion-card-header>
                <ion-card-content>
                    <ion-item>
                        <ion-label position="floating">Email</ion-label>
                        <ion-input v-model="email"></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-label position="floating">Password</ion-label>
                        <ion-input type="password" v-model="password"></ion-input>
                    </ion-item>
                    <ion-button expand="full" @click="login()">Login</ion-button>
                </ion-card-content>
                <ion-card-subtitle
                    class="tw-flex tw-justify-center tw-items-center tw-w-full tw-border-2 gap-2 text-black">
                    <a href="#">Forgot Password?</a>
                    <a href="#">Register</a>
                </ion-card-subtitle>
            </ion-card>
        </ion-content>
    </ion-page>
</template>
<script setup lang="ts">
import { IonCard, IonCardContent, IonCardHeader, IonCardTitle, IonContent, IonHeader, IonInput, IonItem, IonLabel, IonPage, IonTitle, IonToolbar } from '@ionic/vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
const email = ref('');
const password = ref('');
const router = useRouter();
const login = () => {
    router.push('/home');
};
</script>

<style scoped></style>
