<template>
    <ion-page>
        <ion-content :fullscreen="true" class="ion-padding tw-flex tw-flex-col tw-justify-between tw-h-full tw-border">
            <div class="login-container">
                <h1 class="tw-text-white">Register</h1>
                <p class="tw-text-white">We deliver your love to the world.</p>
                <div class="input-container tw-flex tw-flex-col tw-gap-4 tw-my-4">
                    <ion-input type="text" placeholder="First Name" v-model="firstName" class="ion-padding"
                        shape="round"></ion-input>
                    <ion-input type="text" placeholder="Last Name" v-model="lastName" class="ion-padding"
                        shape="round"></ion-input>
                    <ion-input type="email" placeholder="Email" v-model="email" class="ion-padding"
                        shape="round"></ion-input>
                    <ion-input type="password" placeholder="Password" v-model="password" class="ion-padding"
                        shape="round"></ion-input>
                    <ion-input type="password" placeholder="Confirm Password" v-model="confirmPassword"
                        class="ion-padding" shape="round"></ion-input>
                </div>
                <ion-button expand="full" @click="login" shape="round" class="tw-my-5"
                    color="secondary">Register</ion-button>
                <p class="tw-text-white">By registering, you agree to our Terms of Service and Privacy Policy</p>
            </div>
            <div class="tw-my-4">
                <p class="tw-text-white">Already have an account?
                    <a @click="navigateTo('login')"
                        class="tw-underline tw-cursor-pointer hover:tw-no-underline tw-text-white">Login</a>
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

const firstName = ref("");
const lastName = ref("");
const email = ref("");
const password = ref("");
const confirmPassword = ref("");
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
                router.push("/driver_dashboard");
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
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 50vh;
}

ion-input {
    --background: #ffffff;
}
</style>
