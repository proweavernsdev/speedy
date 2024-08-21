<template>
    <ion-page>
        <HeaderComponent title="Deliveries" headerIcon="person-circle" backIcon=""
            navigatePathonRight="customer_account" navigatePathonLeft="customer_dashboard" />

        <div class="ion-padding">
            <div class="tw-flex tw-justify-between tw-items-center">
                <div>
                    <h1 v-if="!isUserOrdering && isUserHasOrders">New Order:</h1>
                    <h1 v-else>Current Orders</h1>
                </div>

                <div>
                    <ion-icon v-if="isUserHasOrders" name="remove-circle-outline"
                        @click="addNewOrder('add')"></ion-icon>
                    <ion-icon v-else name="add-circle-outline" @click="addNewOrder('remove')"></ion-icon>
                </div>
            </div>
        </div>


        <div v-if="!isUserHasOrders"
            class="ion-padding tw-h-full tw-flex tw-justify-center tw-items-center tw-text-gray-300">
            <h4>No Current Orders</h4>
        </div>

        <div v-else>
            <ion-item>
                <ion-label class="text-label" position="floating">Delivery Address</ion-label>
                <ion-input type="text"></ion-input>
            </ion-item>
            <ion-item>
                <ion-label class="text-label" position="floating">Pickup Address</ion-label>
                <ion-input type="text"></ion-input>
            </ion-item>
            <h6 class="ion-padding tw-flex tw-justify-start tw-text-gray-500">General
                Information</h6>
            <ion-card class="tw-shadow-none tw-border tw-border-gray-300 tw-rounded-xl">
                <ion-card-header class="header-container">
                    <ion-card-title class="tw-font-bold">Item Details</ion-card-title>
                </ion-card-header>
                <ion-card-content>
                    <ion-item>
                        <ion-label class="text-label" position="floating">Name of Item</ion-label>
                        <ion-input type="text"></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-label class="text-label" position="floating">Item Description</ion-label>
                        <ion-input type="text"></ion-input>
                    </ion-item>
                </ion-card-content>
                <hr class="tw-border-gray-300 tw-border tw-rounded-xl tw-w-1/2 tw-m-0 tw-mx-auto">
                <ion-card-content>
                    <ion-item>
                        <ion-label class="text-label" position="floating">Name of Item</ion-label>
                        <ion-input type="text"></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-label class="text-label" position="floating">Item Description</ion-label>
                        <ion-input type="text"></ion-input>
                    </ion-item>
                </ion-card-content>
            </ion-card>

        </div>

        <FooterComponent :button1Text="'Dashboard'" :button1Link="'customer_dashboard'" :button1Icon="'home'"
            :button2Text="'Current Orders'" :button2Link="'customer_deliveries'" :button2Icon="'cart'"
            :button3Text="'History'" :button3Link="'customer_history'" :button3Icon="'file-tray-full'"
            :button4Text="'Others'" :button4Link="'customer_others-settings'"
            :button4Icon="'ellipsis-horizontal-outline'" />
    </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import {
    IonPage,
    IonItem,
    IonLabel,
    IonInput,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardContent,
    IonIcon
} from "@ionic/vue";
import HeaderComponent from "../main_components/HeaderComponent.vue";
import FooterComponent from "../main_components/FooterComponent.vue";

const isUserHasOrders = ref(false);
const isUserOrdering = ref(false);


function addNewOrder(state: string) {
    if (state === "add") {
        console.log("add");
        isUserHasOrders.value = false;
    } else {
        console.log("remove");
        isUserHasOrders.value = true;
    }
}
</script>

<style scoped>
ion-page {
    display: flex;
    flex-direction: column;
    height: 100vh;
    justify-content: normal;
    overflow-y: auto;
    flex: 1;
}

ion-item {
    margin-bottom: 16px;
    --padding-start: 16px;
    --padding-end: 16px;
    display: flex;
    flex-direction: column;
}

ion-card>ion-card-content>ion-item {
    --padding-start: 0;
    --padding-end: 0;
}

.text-label {
    font-family: "Moderustic", sans-serif;
}

.header-container {
    padding: 16px 16px 0;
}

ion-icon {
    margin-top: 18px;
    font-size: 32px;
    color: #ad2831;
    cursor: pointer;
}
</style>
