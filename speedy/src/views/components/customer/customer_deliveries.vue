<template>
    <ion-page>
        <HeaderComponent title="Deliveries" headerIcon="person-circle" backIcon=""
            navigatePathonRight="customer_account" navigatePathonLeft="customer_dashboard" />

        <div class="ion-content-wrapper">
            <div class="ion-padding">
                <div class="tw-flex tw-justify-between tw-items-center">
                    <div>
                        <h3 v-if="!isUserOrdering && isUserHasOrders">New Order:</h3>
                        <h3 v-else>Current Orders</h3>
                    </div>

                    <div>
                        <ion-icon v-if="isUserHasOrders" name="close-outline" @click="addNewOrder('add')"></ion-icon>
                        <ion-icon v-else name="add-circle-outline" @click="addNewOrder('remove')"></ion-icon>
                    </div>
                </div>
            </div>

            <div v-if="!isUserHasOrders"
                class="ion-padding tw-h-min tw-flex tw-justify-center tw-items-center tw-text-gray-200">
                <h5>No Current Orders</h5>
            </div>

            <div v-else class="tw-px-4">
                <div class="tw-bg-white tw-rounded-md">
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">Delivery Address</ion-label>
                        <ion-input type="text" class="tw-w-1/3"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3">
                        <ion-label class="text-label tw-w-1/2">Pickup Address</ion-label>
                        <ion-input type="text" class="tw-w-1/3"></ion-input>
                    </div>
                </div>
                <h6 class=" tw-text-[#D9D9D9]">General Information</h6>
                <div class="tw-bg-white tw-rounded-md">
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">Name of Item</ion-label>
                        <ion-input type="text" class="tw-w-1/3"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">Item Description</ion-label>
                        <ion-input type="text" class="tw-w-1/3"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">Preferred Delivery Date</ion-label>
                        <ion-input type="text" class="tw-w-1/3"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-full">Preferred Delivery Time From</ion-label>
                        <ion-input type="text" class="tw-w-1/3"></ion-input>
                    </div>

                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">Estimated Cost</ion-label>
                        <ion-input type="text" class="tw-w-1/3"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3">
                        <ion-label class="text-label tw-w-1/2">Payment Method</ion-label>
                        <ion-input type="text" class="tw-w-1/3"></ion-input>
                    </div>
                </div>
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
import { ref } from 'vue';
import {
    IonPage,
    IonLabel,
    IonInput,
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
    overflow: hidden;
}

.ion-content-wrapper {
    height: 100%;
    flex: 1;
    overflow-y: auto;
    margin-bottom: 36px;
}

ion-card>ion-card-content>ion-item {
    --padding-start: 0;
    --padding-end: 0;
}

ion-card-content {
    padding-top: 0px;
}

.text-label {
    font-family: "Moderustic", sans-serif;
    width: 62.5%;
}

.header-container {
    padding: 16px 16px 0;
}


h3 {
    color: #ffffff;
}

ion-input {
    text-align: right;
}

ion-icon {
    margin-top: 18px;
    font-size: 32px;
    color: #ffffff;
    cursor: pointer;
}
</style>
