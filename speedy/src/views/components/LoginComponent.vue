<template>
    <ion-page>
        <ion-content :fullscreen="true" class="ion-padding" :style="backgroundStyle">
            <div class="flex justify-center items-center w-screen h-screen">
                <ion-card class="ion-padding text-center max-w-[500px]" :style="cardStyle">
                    <h1 class="text-3xl font-bold leading-snug py-5" :style="headingStyle"> Speedy Repair and Delivery
                    </h1>
                    <form class="flex flex-col gap-5" @submit.prevent="login(email, pass)">
                        <ion-input v-model="email" type="email" placeholder="Email" required></ion-input>
                        <div class="relative">
                            <ion-input v-model="pass" type="password" placeholder="Password" required></ion-input>
                            <ion-icon class="absolute w-7 h-7 mx-2 float-right cursor-pointer"
                                :icon="showPassword ? icons.eye : icons.eyeOff" @click="togglePasswordVisibility"
                                style="right: 15px; top: 50%; transform: translateY(-50%);" />
                        </div>
                        <ion-checkbox v-model="rememberMe" class="ion-margin-end" /> Remember me
                        <ion-button expand="block" type="submit" :style="buttonStyle">Sign In</ion-button>
                    </form>
                    <div class="flex flex-col items-center">
                        <ion-router-link :routerLink="'/reset'" class="text-sm">
                            <ion-button fill="clear" :style="forgotButtonStyle">Forgot Password?</ion-button>
                        </ion-router-link>
                        <p class="text-sm">Need an account? <ion-router-link :routerLink="'/signup'"
                                :style="registerLinkStyle">Register</ion-router-link>
                        </p>
                    </div>
                </ion-card>
            </div>
            <ion-loading :isOpen="isLoading" message="Please wait..." />
        </ion-content>
    </ion-page>
</template>

<script setup>
// Ionic imports
import { ref, onMounted } from 'vue';
import { IonPage, IonContent, IonCard, IonInput, IonButton, IonLoading, IonCheckbox, IonIcon } from "@ionic/vue";
import { useRouter, useRoute } from 'vue-router';
import { loginAuth } from "../../services/api/api.js";
import icons from '@/assets/icons';

// Constants
const email = ref('');
const pass = ref('');
const rememberMe = ref(false);
const isLoading = ref(false);
const showPassword = ref(false);

// Router
const router = useRouter();
const route = useRoute();

// Styles
const backgroundStyle = {
    background: 'linear-gradient(to right, #550514, #AA0927)',
};
const cardStyle = {
    backgroundColor: 'white',
};
const headingStyle = {
    color: '#1C0207',
};
const buttonStyle = {
    backgroundColor: '#39030D',
    color: 'white',
};
const forgotButtonStyle = {
    color: '#39030D',
};
const registerLinkStyle = {
    color: '#39030D',
};

// Functions
function togglePasswordVisibility() {
    showPassword.value = !showPassword.value;
}

async function login(email, pass) {
    try {
        isLoading.value = true;
        const data = await loginAuth(email, pass);
        if (data && data.result) {
            localStorage.setItem('token', JSON.parse(data.result));
            if (rememberMe.value) {
                localStorage.setItem('rememberMe', 'true');
            } else {
                localStorage.removeItem('rememberMe');
            }
            router.push('/load');
        } else {
            throw new Error('Invalid login data');
        }
    } catch (error) {
        console.error('Login error:', error);
        alert('Invalid login data');
    } finally {
        isLoading.value = false;
    }
}

// Lifecycle
onMounted(() => {
    const status = route.query.success;
    if (status === 'true') {
        alert('Login Again');
    }
});
</script>

<style scoped>
ion-content {
    --background: #250902;
    --height: 100vh;
}

ion-input {
    --background: #ffffff;
}
</style>
