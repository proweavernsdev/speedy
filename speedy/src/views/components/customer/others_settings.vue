<template>
    <ion-page>
        <HeaderComponent title="Others" headerIcon="person-circle" backIcon="" navigatePathonRight="customer_account"
            navigatePathonLeft="customer_dashboard" />
        <div class="ion-content-wrapper">
            <div class="ion-padding-horizontal">
                <h1 class="text-white">Others</h1>
                <div class="my-2">
                    <ion-item
                        class="tw-text-white tw-border-b-2 tw-border-gray-600 tw-text-base tw-flex tw-items-center"
                        color="primary" button @click="router.push('/customer_feedback')">
                        <ion-icon slot="start" name="list-outline"></ion-icon>
                        Feedback
                    </ion-item>
                    <ion-item class="tw-text-white tw-border-gray-600 tw-text-base tw-flex tw-items-center"
                        color="primary" button @click="router.push('/customer_report')">
                        <ion-icon slot="start" name="alert-circle-outline"></ion-icon>
                        Send a Report
                    </ion-item>
                </div>
            </div>

            <div class="ion-padding-horizontal my-4">
                <ion-button expand="full" color="danger" class="tw-rounded-full" @click="logout">
                    Logout
                </ion-button>
            </div>
        </div>
        <FooterComponent :button1Text="'Dashboard'" :button1Link="'customer_dashboard'" :button1Icon="'home-outline'"
            :button2Text="'Current Orders'" :button2Link="'customer_deliveries'" :button2Icon="'cart-outline'"
            :button3Text="'History'" :button3Link="'customer_history'" :button3Icon="'file-tray-full-outline'"
            :button4Text="'Others'" :button4Link="'customer_others-settings'"
            :button4Icon="'ellipsis-horizontal-outline'" />
    </ion-page>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import HeaderComponent from '../main_components/HeaderComponent.vue';
import FooterComponent from '../main_components/FooterComponent.vue';
import { Preferences } from '@capacitor/preferences';

const router = useRouter();

async function logout() {
    try {
        // Clear user authentication data (if any)
        await Preferences.remove({ key: 'userToken' }); // Example key

        // Perform other cleanup operations if necessary

        // Redirect to login page or home screen
        router.push('/login'); // Adjust the route to your login page
    } catch (error) {
        console.error('Logout failed', error);
    }
}

</script>

<style scoped>
ion-page {
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.ion-content-wrapper {
    height: 100%;
    flex: 1;
    overflow-y: auto;
    margin-bottom: 54px;
}

.text-white {
    color: #ffffff;
}

.ion-item {
    --background: transparent;
    --color: #ffffff;
    --border-color: #6c757d;
}

.ion-button {
    --background: #dc3545;
    --color: #ffffff;
}
</style>
