<template>
    <ion-page>
        <HeaderComponent title="Others" headerIcon="person-circle" backIcon="" navigatePathonRight="driver_account"
            navigatePathonLeft="driver_dashboard" />
        <div class="ion-content-wrapper tw-h-screen tw-flex tw-flex-col tw-justify-between">
            <div class="ion-padding-horizontal tw-flex tw-flex-col ">
                <h1 class="tw-text-white">Others</h1>
                <div class="tw-flex tw-flex-col tw-my-2 tw-gap-4">
                    <button
                        class=" tw-text-white tw-py-2 tw-px-4 tw-w-full tw-border-b-2 tw-border-gray-600 tw-text-base tw-flex tw-items-center tw-gap-3"
                        @click="router.push('/driver_feedback')">
                        <ion-icon name="list-outline"></ion-icon>
                        Feedback
                    </button>
                    <button
                        class=" tw-text-white tw-py-2 tw-px-4 tw-w-full tw-border-b-2 tw-border-gray-600 tw-text-base tw-flex tw-items-center tw-gap-3"
                        @click="router.push('/driver_report')">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        Send a Report
                    </button>
                </div>
            </div>

            <div class="ion-padding-horizontal">
                <button class=" tw-text-white tw-py-4 tw-px-6 tw-rounded-full tw-w-full tw-bg-red-900">Logout</button>
            </div>
        </div>
        <FooterComponent :button1Text="'Dashboard'" :button1Link="'driver_dashboard'" :button1Icon="'home-outline'"
            :button2Text="'Current Orders'" :button2Link="'driver_deliveries'" :button2Icon="'cart-outline'"
            :button3Text="'History'" :button3Link="'driver_history'" :button3Icon="'file-tray-full-outline'"
            :button4Text="'Others'" :button4Link="'driver_others-settings'"
            :button4Icon="'ellipsis-horizontal-outline'" />
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage } from "@ionic/vue";
import HeaderComponent from "../main_components/HeaderComponent.vue";
import FooterComponent from "../main_components/FooterComponent.vue";
import { useRouter } from "vue-router";

const router = useRouter();
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

ion-icon {
    font-size: 1.5rem;
    color: #ffffff;
}
</style>
