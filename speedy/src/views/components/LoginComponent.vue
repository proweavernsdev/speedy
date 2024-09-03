<template>
    <ion-page>
        <ion-content :fullscreen="true" class="ion-padding tw-flex tw-flex-col tw-justify-between tw-h-full">
            <div class="login-container">
                <h1 class="tw-text-white">Login</h1>
                <p class="tw-text-white">We deliver your love to the world.</p>
                <div class="input-container tw-flex tw-flex-col tw-gap-4 tw-my-4">
                    <ion-input type="email" placeholder="Email" v-model="email" class="ion-padding"
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" shape="round"></ion-input>
                    <ion-input type="password" placeholder="Password" v-model="password" class="ion-padding"
                        shape="round"></ion-input>
                </div>
                <ion-button expand="full" mode="ios" shape="round" color="secondary" @click="login"
                    class="tw-my-5">Login</ion-button>
                <a @click="navigateTo('forgotPassword')"
                    class="tw-underline tw-cursor-pointer hover:tw-no-underline tw-text-white">Forgot Password?</a>
            </div>
            <div class="register-container">
                <p class="tw-text-white">Don't have an account?
                    <a @click="navigateTo('register')"
                        class="tw-underline tw-cursor-pointer hover:tw-no-underline tw-text-white">Register</a>
                </p>
            </div>
            <ion-toast :is-open="showToastSuccess" :message="toastMessage" duration="1250" position="bottom"
                color="success" @did-dismiss="showToastSuccess = false" />
            <ion-toast :is-open="showToastError" :message="toastMessage" duration="1250" position="bottom"
                color="danger" @did-dismiss="showToastError = false" />
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import {
    IonPage,
    IonContent,
    IonInput,
    IonButton,
    IonToast,
} from "@ionic/vue";

import { ref } from "vue";
import { useRouter } from "vue-router";
import { loginAuth } from "../../services/api/api.js";
import { users } from "../../views/data/users.js";

const email = ref("");
const password = ref("");
const router = useRouter();

const showToastSuccess = ref(false);
const showToastError = ref(false);
const toastMessage = ref("");
const isValid = ref(false);

const login = async () => {
    // try {
    //     const data = await loginAuth(email.value, password.value);
    //     console.log(data);
    //     toastMessage.value = "Login successful!";
    //     showToast.value = true;
    // } catch (error) {
    //     console.error(error);
    //     toastMessage.value = "Invalid email or password.";
    //     showToast.value = true;
    // }
    // =======================================
    const user = users.find((user) => user.email === email.value && user.password === password.value);

    if (user) {
        isValid.value = true;
        localStorage.setItem("userId", user.id); // Save user ID to localStorage

        // Navigate based on user type
        switch (user.type) {
            case "driver":
                toastMessage.value = "Login successful!";
                showToastSuccess.value = true;
                setTimeout(() => {
                    router.push("/driver_dashboard");
                }, 1000);
                break;
            case "customer":
                toastMessage.value = "Login successful!";
                showToastSuccess.value = true;
                setTimeout(() => {
                    router.push("/customer_dashboard");
                }, 1000);
                break;
            default:
                router.push("/login");
                break;
        }
    } else {
        setTimeout(() => {
            toastMessage.value = "Invalid email or password.";
            showToastSuccess.value = true;
        }, 2000);
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
}
</style>
