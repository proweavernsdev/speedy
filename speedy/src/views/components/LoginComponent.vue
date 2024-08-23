<template>
    <ion-page>
        <ion-content :fullscreen="true" class="ion-padding tw-flex tw-flex-col tw-justify-between tw-h-full">
            <div class="login-container">
                <h1 class="tw-text-white">Login</h1>
                <p class="tw-text-white">We deliver your love to the world.</p>
                <div class="input-container">
                    <ion-input type="email" placeholder="Email" v-model="email" class="ion-padding"
                        shape="round"></ion-input>
                    <ion-input type="password" placeholder="Password" v-model="password" class="ion-padding"
                        shape="round"></ion-input>
                </div>
                <ion-button expand="full" @click="login" shape="round" class="tw-my-5">Login</ion-button>
                <a @click="navigateTo('forgotPassword')"
                    class="tw-underline tw-cursor-pointer hover:tw-no-underline tw-text-white">Forgot Password?</a>
            </div>
            <div class="register-container">
                <p class="tw-text-white">Don't have an account?
                    <a @click="navigateTo('register')"
                        class="tw-underline tw-cursor-pointer hover:tw-no-underline tw-text-white">Register</a>
                </p>
            </div>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import {
    IonPage,
    IonContent,
    IonInput,
    IonButton,
} from "@ionic/vue";

import { ref } from "vue";
import { useRouter } from "vue-router";
import { users } from "../data/users";

const email = ref("");
const password = ref("");
const router = useRouter();
const userslist = ref(users);

const isValid = ref(false);

const login = () => {
    const user = userslist.value.find(
        (user) => user.email === email.value && user.password === password.value
    );

    const userId = user?.id;

    if (userId) {
        localStorage.setItem("userId", userId);
    }

    if (user) {
        isValid.value = true;
        switch (user.type) {
            case "driver":
                router.push("/driver/dashboard");
                break;
            case "customer":
                router.push("/customer_dashboard");
                break;
            default:
                router.push("/login");
                break;
        }
    } else {
        alert("Invalid email or password");
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
