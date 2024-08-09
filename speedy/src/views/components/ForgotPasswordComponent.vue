<template>
    <ion-page>
        <ion-content>
            <ion-card>
                <ion-card-header>
                    <ion-card-title>Forgot Password</ion-card-title>
                </ion-card-header>
                <ion-card-content>
                    <form @submit.prevent="handleForgotPassword">
                        <ion-item>
                            <ion-label position="floating">Email</ion-label>
                            <ion-input v-model="email" type="email" required></ion-input>
                        </ion-item>
                        <ion-button expand="full" type="submit" color="primary" shape="round">Reset
                            Password</ion-button>
                    </form>
                    <ion-card-subtitle class="tw-flex tw-justify-around ion-padding">
                        <a @click.prevent="goToLogin"
                            class="tw-underline tw-cursor-pointer hover:tw-no-underline">Login</a>
                    </ion-card-subtitle>
                </ion-card-content>
            </ion-card>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonItem, IonLabel, IonInput, IonButton, IonCardSubtitle } from '@ionic/vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const email = ref('');

const handleForgotPassword = () => {
    // Logic for handling forgot password (e.g., API call) goes here
    // After processing, navigate to the login page
    router.push('/login');
}

const goToLogin = () => {
    router.push('/login');
}
</script>

<style></style>
