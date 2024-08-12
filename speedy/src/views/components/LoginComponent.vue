<template>
    <ion-page>
        <ion-content :fullscreen="true">
            <ion-card>
                <ion-card-header>
                    <ion-card-title>Login</ion-card-title>
                </ion-card-header>
                <ion-card-content>
                    <ion-item>
                        <ion-label position="floating">Email</ion-label>
                        <ion-input type="email" v-model="email"></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-label position="floating">Password</ion-label>
                        <ion-input type="password" v-model="password"></ion-input>
                    </ion-item>
                    <ion-button expand="full" @click="login" shape="round">Login</ion-button>
                </ion-card-content>
                <ion-card-subtitle class="tw-flex tw-justify-around ion-padding">
                    <a @click="navigateTo('forgotPassword')"
                        class="tw-underline tw-cursor-pointer hover:tw-no-underline">Forgot Password?</a>
                    <a @click="navigateTo('register')"
                        class="tw-underline tw-cursor-pointer hover:tw-no-underline">Register</a>
                </ion-card-subtitle>
            </ion-card>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonItem, IonLabel, IonInput, IonButton, IonCardSubtitle } from '@ionic/vue';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { users } from '../data/users';

const email = ref('');
const password = ref('');
const router = useRouter();
const userslist = ref(users);

const login = () => {
    console.log(email.value);
    console.log(password.value);

    const user = userslist.value.find(
        (user) => user.email === email.value && user.password === password.value
    );

    const userId = user?.id;

    localStorage.setItem('userId', userId);

    console.log(user);
    console.log(userId);

    if (user) {
        switch (user.type) {
            case 'super-admin':
                router.push('/super_admin_dashboard');
                break;
            case 'admin':
                router.push('/admin_dashboard');
                break;
            case 'company':
                router.push('/company_dashboard');
                break;
            case 'driver':
                router.push('/driver_dashboard');
                break;
            case 'customer':
                router.push('/customer_dashboard');
                break;
            default:
                router.push('/login'); // Fallback in case no type matches
                break;
        }
    } else {
        alert('Invalid email or password');
    }
};

const navigateTo = (path: string) => {
    router.push(`/${path}`);
};
</script>

<style scoped></style>
