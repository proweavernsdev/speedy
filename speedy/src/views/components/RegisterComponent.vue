<template>
    <ion-page>
        <ion-content :fullscreen="true" class="ion-padding tw-flex tw-flex-col tw-justify-between tw-h-full">
            <div class="login-container">
                <h1 class="tw-text-white">Register</h1>
                <p class="tw-text-white">We deliver your love to the world.</p>
                <div class="input-container tw-flex tw-flex-col tw-gap-4 tw-my-4">
                    <ion-input type="email" placeholder="Email" v-model="email" class="ion-padding" color="secondary"
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" shape="round"></ion-input>
                    <ion-input type="password" placeholder="Password" v-model="password" class="ion-padding"
                        shape="round"></ion-input>
                </div>
                <ion-button expand="full" @click="registerUser" shape="round" class="tw-my-5"
                    color="secondary">Register</ion-button>
                <p class="tw-text-white">By registering, you agree to our <strong class="tw-underline">Terms
                        of Service</strong> and <strong class="tw-underline">Privacy Policy</strong> </p>
            </div>
            <div class="tw-my-4">
                <p class="tw-text-white">Already have an account?
                    <a @click="navigateTo('login')"
                        class="tw-underline tw-cursor-pointer hover:tw-no-underline tw-text-white">Login</a>
                </p>
            </div>
            <ion-toast :is-open="showToast" :message="toastMessage" duration="2000" position="bottom" color="danger"
                @did-dismiss="showToast = false" />
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonInput, IonButton, IonToast, } from "@ionic/vue";

import { ref } from "vue";
import { useRouter } from "vue-router";
import { register } from "../../services/api/api.js";

const email = ref("");
const password = ref("");
const router = useRouter();

const showToast = ref(false);
const toastMessage = ref("");

const registerUser = async () => {
    try {
        const data = await register(email.value, password.value);
        console.log(data);

        // Show success toast
        toastMessage.value = "Registration successful! Please log in.";
        showToast.value = true;

        // Optionally navigate to the login page or another route
        router.push("/login");

    } catch (error) {
        console.error(error);

        // Show error toast
        toastMessage.value = "Registration failed. Please try again.";
        showToast.value = true;
    }
};

const navigateTo = (path: string) => {
    router.push(`/${path}`);
};
</script>

<style scoped>
ion-content {
    --background: #250902;
    --height: 100vh;
}

ion-input {
    --background: #ffffff;
    --color: #000000;
}
</style>
