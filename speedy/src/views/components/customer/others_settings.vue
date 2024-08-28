<template>
    <ion-page>
        <HeaderComponent title="Others" headerIcon="person-circle" backIcon="" navigatePathonRight="customer_account"
            navigatePathonLeft="customer_dashboard" />
        <div class="ion-content-wrapper">
            <div class="ion-padding-horizontal">
                <h1>Others</h1>
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
import { IonPage } from "@ionic/vue";
import HeaderComponent from "../main_components/HeaderComponent.vue";
import FooterComponent from "../main_components/FooterComponent.vue";
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
    margin-bottom: 36px;
}
</style>
