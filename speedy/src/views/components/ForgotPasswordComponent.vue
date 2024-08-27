<template>
    <ion-page>
        <ion-content class="ion-padding tw-flex tw-flex-col tw-gap-6" color="primary">
            <h1>Forgot Password</h1>
            <p>Required code sent to your entered email.</p>
            <form @submit.prevent="submitEmail" class="tw-flex tw-flex-col tw-my-4">
                <ion-input type="email" placeholder="Email" v-model="email"
                    class="ion-padding tw-bg-white tw-rounded-md tw-text-black" shape="round"
                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required></ion-input>
                <ion-button expand="full" type="submit" color="secondary">Send Reset Link</ion-button>
            </form>
            <ion-toast :is-open="toastOpen" :message="toastMessage" duration="3000"
                @didDismiss="toastOpen = false"></ion-toast>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IonPage, IonContent, IonInput, IonButton, IonToast } from '@ionic/vue';

const email = ref('');
const toastOpen = ref(false);
const toastMessage = ref('');

const submitEmail = () => {
    if (email.value) {
        // Logic to handle sending the email
        toastMessage.value = `Email sent to: ${email.value}`;
    } else {
        toastMessage.value = 'Please enter a valid email address';
    }
    toastOpen.value = true;
};
</script>

<style scoped>
/* Add any styles you need */
</style>
