<template>
    <ion-page>
        <div>
            <header-component title="" headerIcon="" backIcon="chevron-back-outline" navigatePathonRight=""
                navigatePathonLeft="driver_account" />
        </div>
        <ion-content :fullscreen="true" class="ion-padding-horizontal" id="account_container" color="primary">
            <h2>Driver Verification</h2>
            <form @submit.prevent="submitEmail">
                <!-- Identity Verification -->
                <h4>Identity Verification</h4>
                <ion-list class="tw-rounded-lg tw-bg-white">
                    <ion-item>
                        <ion-label class="font">Name</ion-label>
                        <ion-input type="text" v-model="name" class="tw-w-1/2 ion-text-end" />
                    </ion-item>
                    <ion-item :inset="true">
                        <ion-label class="font">Driver's License No.</ion-label>
                        <ion-input type="text" v-model="name" class="tw-w-1/2 ion-text-end" />
                    </ion-item>
                    <ion-item>
                        <ion-label class="font">Proof of ID </ion-label>
                        <label class="file-input-label">
                            <span>Upload ID</span>
                            <input type="file" />
                        </label>
                    </ion-item>
                    <ion-item>
                        <ion-label class="font">Selfie with ID</ion-label>
                        <label class="file-input-label">
                            <span>Upload Selfie</span>
                            <input type="file" />
                        </label>
                    </ion-item>
                </ion-list>

                <!-- Vehicle Verification -->
                <h4>Vehicle Verification</h4>
                <ion-list class="tw-rounded-lg">
                    <ion-item :inset="true">
                        <ion-label class="font">Vehicle Registration No.</ion-label>
                        <ion-input type="number" v-model="name" class="tw-w-1/3 ion-text-end" />
                    </ion-item>
                    <ion-item>
                        <ion-label class="font">Proof of Registration</ion-label>
                        <label class="file-input-label">
                            <span>Upload</span>
                            <input type="file" />
                        </label>
                    </ion-item>
                    <ion-item>
                        <ion-label class="font">Vehicle Type</ion-label>
                        <ion-input type="text" v-model="name" class="tw-w-1/2 ion-text-end" />
                    </ion-item>
                    <ion-item>
                        <ion-label class="font">Vehicle Photos</ion-label>
                        <label class="file-input-label">
                            <span>Upload</span>
                            <input type="file" />
                        </label>
                    </ion-item>
                    <ion-item>
                        <ion-label class="font">Vehicle Model</ion-label>
                        <ion-input type="text" v-model="name" class="tw-w-1/2 ion-text-end" />
                    </ion-item>
                    <ion-item>
                        <ion-label class="font">License Plate Number</ion-label>
                        <ion-input type="text" v-model="name" class="tw-w-1/3 ion-text-end" />
                    </ion-item>
                </ion-list>

                <!-- Submit Button -->
                <ion-button expand="full" type="submit" color="secondary" mode="ios" shape="round">Submit</ion-button>
                <ion-button expand="full" type="submit" color="tertiary" mode="ios" shape="round"
                    @click="router.push('/driver_account')">Cancel</ion-button>
            </form>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { IonPage, IonContent, IonList, IonItem, IonLabel, IonButton } from "@ionic/vue";
import HeaderComponent from "../main_components/HeaderComponent.vue";

const driverId = ref('');
const name = ref('');
const router = useRouter();

const submitEmail = () => {
    console.log(driverId.value);
}

</script>

<style scoped>
ion-list {
    --margin-top: 0;
    --margin-bottom: 0;
    --margin-inline-start: 0;
    --margin-inline-end: 0;
}

.file-input-label {
    display: flex;
    align-items: center;
    position: relative;
    cursor: pointer;
}

.file-input-label input[type="file"] {
    display: none;
}

.file-input-label span {
    display: inline-block;
    padding: 8px 12px;
    background: #f0f0f5;
    border-radius: 6px;
    color: #ad2831;
    font-size: 16px;
    font-weight: 500;
}

.font {
    font-family: "Moderustic", sans-serif;
}

#account_container {
    display: flex;
    flex-direction: column;
    height: 100%;
    justify-content: normal;
    height: 100%;
    flex: 1;
    overflow-y: auto;
    margin-bottom: 54px;
}
</style>
