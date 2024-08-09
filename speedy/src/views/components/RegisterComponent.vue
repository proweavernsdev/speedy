<template>
    <ion-page>
        <ion-content :fullscreen="true">

            <!-- User Type Selection -->
            <ion-card v-show="!userType">
                <ion-card-header>
                    <ion-card-title>Select User Type</ion-card-title>
                </ion-card-header>
                <ion-card-content>
                    <ion-button @click="userType = 'company'" expand="full" color="tertiary">Company</ion-button>
                    <ion-button @click="userType = 'user'" expand="full" color="tertiary">User</ion-button>
                    <ion-button @click="userType = 'driver'" expand="full" color="tertiary">Driver</ion-button>
                </ion-card-content>
            </ion-card>

            <!-- Registration Form -->
            <ion-card v-show="userType">
                <ion-card-header>
                    <ion-card-title>Register as <span class="tw-text-red-700">{{
                        userType }}</span></ion-card-title>
                </ion-card-header>
                <ion-card-content>
                    <form @submit.prevent="handleRegister">
                        <ion-item>
                            <ion-label position="floating">Name</ion-label>
                            <ion-input v-model="name" type="text" required></ion-input>
                        </ion-item>
                        <ion-item>
                            <ion-label position="floating">Email</ion-label>
                            <ion-input v-model="email" type="email" required></ion-input>
                        </ion-item>
                        <ion-item>
                            <ion-label position="floating">Password</ion-label>
                            <ion-input v-model="password" type="password" required></ion-input>
                        </ion-item>
                        <ion-item>
                            <ion-label position="floating">Confirm Password</ion-label>
                            <ion-input v-model="confirmPassword" type="password" required></ion-input>
                        </ion-item>
                        <ion-button expand="full" type="submit" color="secondary" shape="round">Register</ion-button>
                        <ion-button expand="full" color="primary" shape="round" @click="userType = ''">Change
                            User</ion-button>
                        <ion-card-subtitle class="tw-flex tw-justify-around ion-padding">
                            <a @click="router.push('/login')"
                                class="tw-underline tw-cursor-pointer hover:tw-no-underline">Already have an
                                account? Login</a>
                        </ion-card-subtitle>
                    </form>
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

const name = ref('');
const email = ref('');
const password = ref('');
const confirmPassword = ref('');

const userType = ref(''); // Store the selected user type

const handleRegister = async () => {
    if (password.value !== confirmPassword.value) {
        alert('Passwords do not match');
        return;
    } else {
        alert('Registration Successful');
        router.push('/login');
    }
}
</script>

<style scoped></style>
