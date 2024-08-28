<template>
    <ion-page>
        <HeaderComponent title="Speedy" headerIcon="person-circle" backIcon="" navigatePathonRight="customer_account"
            navigatePathonLeft="customer_dashboard" />

        <div class="ion-padding-horizontal tw-h-full" id="container">
            <div class="">
                <h5 class="tw-uppercase tw-text-white">Statistics</h5>
                <table class="tw-w-full tw-border tw-rounded-lg">
                    <tr class="tw-border-b">
                        <td class="tw-px-4 tw-py-4 tw-my-auto tw-mx-0">
                            <ion-icon name="card-outline"></ion-icon>
                            To pay:
                        </td>
                        <td class="tw-px-4 tw-py-2">
                            <span class="tw-mx-2">0</span>
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </td>
                    </tr>
                    <tr class="tw-border-b">
                        <td class="tw-px-4 tw-py-4 tw-my-auto tw-mx-0">
                            <ion-icon name="cube-outline"></ion-icon>
                            To ship:
                        </td>
                        <td class="tw-px-4 tw-py-2">
                            <span class="tw-mx-2">0</span>
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </td>
                    </tr>
                    <tr class="tw-border-b">
                        <td class="tw-px-4 tw-py-4 tw-my-auto tw-mx-0">
                            <ion-icon name="car-outline"></ion-icon>
                            To receive:
                        </td>
                        <td class="tw-px-4 tw-py-2">
                            <span class="tw-mx-2">0</span>
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </td>
                    </tr>
                    <tr class="">
                        <td class="tw-px-4 tw-py-4 tw-my-auto tw-mx-0">
                            <ion-icon name="star-outline"></ion-icon>
                            To rate:
                        </td>
                        <td class="tw-px-4 tw-py-2">
                            <span class="tw-mx-2">0</span>
                            <ion-icon name="chevron-forward-outline"></ion-icon>
                        </td>
                    </tr>
                </table>
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
table {
    border-radius: 8px;
    border-collapse: separate;
    overflow: hidden;
}

td {
    padding: 4px;
    color: #ffffff;
    background: #640d14;
    border-radius: 0;
    width: 50%;
}

tr {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    justify-content: center;
    width: 100%;
}

td:nth-child(1) {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
}

td:nth-child(2) {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
}

ion-icon {
    font-size: 18px;
    color: #ffffff;
    margin: 0 4px;
}
</style>
