<template>
    <ion-page>
        <header-component title="History" header-icon="person-circle" back-icon=""
            navigate-path-on-right="customer_account" navigate-path-on-left="customer_dashboard" />
        <div class="ion-content-wrapper">
            <div class="ion-padding-horizontal">
                <div class="tw-flex tw-justify-between tw-items-center">
                    <h6 class="tw-uppercase tw-text-gray-500">Items</h6>
                    <button class="tw-rounded-full tw-bg-gray-200 tw-text-gray-500" @click="openFilterModal">
                        <ion-icon name="filter-circle-outline" color="primary"></ion-icon>
                    </button>
                </div>
                <div>
                    <ion-grid class="tw-w-full tw-rounded-t-md tw-rounded-b-md">
                        <ion-row class="tw-bg-[#FFE0DE] ion-text-center">
                            <ion-col>Name</ion-col>
                            <ion-col>Size</ion-col>
                            <ion-col>Status</ion-col>
                        </ion-row>
                        <ion-row class="tw-bg-[#FFF5F4] ion-text-center">
                            <ion-col>Name 1</ion-col>
                            <ion-col>Size 1</ion-col>
                            <ion-col>Status 1</ion-col>
                        </ion-row>
                    </ion-grid>
                </div>
            </div>
        </div>
        <footer-component :button1-text="'Dashboard'" :button1-link="'customer_dashboard'"
            :button1-icon="'home-outline'" :button2-text="'Current Orders'" :button2-link="'customer_deliveries'"
            :button2-icon="'cart-outline'" :button3-text="'History'" :button3-link="'customer_history'"
            :button3-icon="'file-tray-full-outline'" :button4-text="'Others'" :button4-link="'customer_others-settings'"
            :button4-icon="'ellipsis-horizontal-outline'" />

        <ion-modal class="tw-w-auto tw-h-auto" :is-open="isFilterModalOpen" @did-dismiss="isFilterModalOpen = false">
            <div class="tw-flex tw-justify-center tw-items-end tw-w-full tw-h-full">
                <div class="tw-bg-white tw-w-full tw-rounded-t-xl tw-p-4">
                    <h4 class="tw-text-left tw-font-bold">Filter Options</h4>
                    <ion-list>
                        <ion-item>
                            <ion-label>Name:</ion-label>
                            <ion-input type="text" v-model="filterName" class="tw-w-1/2 ion-text-end"></ion-input>
                        </ion-item>
                        <ion-item>
                            <ion-label>Size:</ion-label>
                            <div class="tw-w-min tw-flex tw-justify-end">
                                <ion-select v-model="filterSize" interface="alert" placeholder="Select Size">
                                    <ion-select-option value="small">Small</ion-select-option>
                                    <ion-select-option value="medium">Medium</ion-select-option>
                                    <ion-select-option value="large">Large</ion-select-option>
                                </ion-select>
                            </div>
                        </ion-item>
                        <ion-item>
                            <ion-label>Status:</ion-label>
                            <div class="tw-w-min tw-flex tw-justify-end">
                                <ion-select v-model="filterStatus" interface="alert" placeholder="Select Status">
                                    <ion-select-option value="pending">Pending</ion-select-option>
                                    <ion-select-option value="completed">Completed</ion-select-option>
                                    <ion-select-option value="canceled">Canceled</ion-select-option>
                                </ion-select>
                            </div>
                        </ion-item>
                    </ion-list>
                    <div class="tw-flex tw-justify-end tw-mt-4 tw-gap-4">
                        <ion-button class="tw-rounded-full tw-bg-gray-200 tw-text-gray-500" @click="applyFilters()"
                            color="primary">Apply</ion-button>
                        <ion-button class="tw-rounded-full tw-bg-gray-200 tw-text-gray-500"
                            @click="isFilterModalOpen = false" color="secondary">Cancel</ion-button>
                    </div>
                </div>
            </div>
        </ion-modal>
    </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IonPage, IonGrid, IonRow, IonCol, IonButton, IonModal, IonList, IonItem, IonLabel, IonInput, IonSelect, IonSelectOption, IonIcon } from '@ionic/vue';
import HeaderComponent from "../main_components/HeaderComponent.vue";
import FooterComponent from "../main_components/FooterComponent.vue";


const isFilterModalOpen = ref(false);
const filterName = ref('');
const filterSize = ref('');
const filterStatus = ref('');
const applyFilters = () => {
    console.log('Filters applied:', filterName.value, filterSize.value, filterStatus.value);
    isFilterModalOpen.value = false;
};

const openFilterModal = () => {
    isFilterModalOpen.value = true;
};


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

ion-icon {
    font-size: 1.5rem;
    color: #d9d9d9;
}

ion-modal {
    --background: transparent;
}

ion-select {
    display: flex;
    justify-content: end;
    align-items: end;
}
</style>
