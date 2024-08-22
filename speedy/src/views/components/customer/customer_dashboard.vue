<template>
    <ion-page>
        <HeaderComponent title="Dashboard" headerIcon="person-circle" backIcon="" navigatePathonRight="customer_account"
            navigatePathonLeft="customer_dashboard" />

        <div class="ion-padding">
            <div class="tw-flex tw-justify-between tw-items-center">
                <h1>Deliveries</h1>
            </div>
        </div>

        <FooterComponent :button1Text="'Dashboard'" :button1Link="'customer_dashboard'" :button1Icon="'home'"
            :button2Text="'Current Orders'" :button2Link="'customer_deliveries'" :button2Icon="'cart'"
            :button3Text="'History'" :button3Link="'customer_history'" :button3Icon="'file-tray-full'"
            :button4Text="'Others'" :button4Link="'customer_others-settings'"
            :button4Icon="'ellipsis-horizontal-outline'" />
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage } from "@ionic/vue";
import HeaderComponent from "../main_components/HeaderComponent.vue";
import FooterComponent from "../main_components/FooterComponent.vue";
</script>

<style scoped>
ion-page {
    display: flex;
    flex-direction: column;
    height: 100vh;
    justify-content: normal;
}
</style>
