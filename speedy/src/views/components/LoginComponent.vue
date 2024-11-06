<template>
    <ion-page>
        <ion-content :fullscreen="true" class="ion-padding tw-flex tw-flex-col tw-justify-between tw-h-full">
            <div class="login-container">
                <h1 class="tw-text-white">Login</h1>
                <p class="tw-text-white">Welcome back! Please login to your account.</p>
                <div class="input-container tw-flex tw-flex-col tw-gap-4 tw-my-4">
                    <ion-input type="email" placeholder="Email" v-model="email" class="ion-padding" color="secondary" shape="round"></ion-input>
                    <ion-input type="password" placeholder="Password" v-model="password" class="ion-padding"
                        shape="round"></ion-input>
                </div>
                <ion-button expand="full" @click="loginUser" shape="round" class="tw-my-5"
                    color="secondary">Login</ion-button>
                <p class="tw-text-white">Don't have an account?
                    <a @click="navigateTo('register')"
                        class="tw-underline tw-cursor-pointer hover:tw-no-underline tw-text-white">Register</a>
                </p>
            </div>
            <ion-toast :is-open="showToast" :message="toastMessage" duration="2000" position="bottom"
                :color="toastColor" @did-dismiss="showToast = false" />
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonInput, IonButton, IonToast } from "@ionic/vue";
import { ref } from "vue";
import { useRouter } from "vue-router";
import { loginAuth } from "../../services/api/api.js"; // Import your login function here

const email = ref("");
const password = ref("");
const info = ref("");
const router = useRouter();

const showToast = ref(false);
const toastMessage = ref("");
const toastColor = ref("danger"); // Default to red for error

const loginUser = async () => {
    try {
        const data = await loginAuth(email.value, password.value); // Call your login API function
        console.log(data);

        if (data && data.result) {
            info.value = data.result;
            localStorage.setItem("token", JSON.parse(data.result));
            toastMessage.value = "Login successful!";
            toastColor.value = "success";
            showToast.value = true;
            router.push("/load"); 
        } else {
            throw new Error('Invalid login data');
        }

    } catch (error) {
        console.error(error);

        // Show error toast
        toastMessage.value = "Login failed. Please check your credentials.";
        toastColor.value = "danger"; // Set color to red for error
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
