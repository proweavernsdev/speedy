<template>
    <ion-page>
        <ion-content class="ion-padding" color="primary">
            <h1>User Type</h1>
            <p>Choose your user type</p>
            <ion-button expand="block" color="secondary" @click="toggleModal('customer')">Customer</ion-button>
            <ion-button expand="block" color="secondary" @click="toggleModal('driver')">Driver</ion-button>

            <ion-modal :is-open="isOpen">
                <div class="ion-page" v-if="userType === 'customer'">
                    <ion-header>
                        <ion-toolbar color="primary">
                            <ion-title>Customer Form</ion-title>
                            <ion-buttons slot="end">
                                <ion-button @click="toggleModal('customer')">Close</ion-button>
                            </ion-buttons>
                        </ion-toolbar>
                    </ion-header>
                    <ion-content class="ion-padding " color="secondary">
                        <ion-item>
                            <ion-input label="First Name" label-placement="floating" type="text"></ion-input>
                        </ion-item>
                        <ion-item>
                            <ion-input label="Last Name" label-placement="floating" type="text"></ion-input>
                        </ion-item>
                        <ion-item>
                            <ion-input label="Address" label-placement="floating" type="text"></ion-input>
                        </ion-item>
                        <ion-item>
                            <ion-input label="Town/City" label-placement="floating" type="text"></ion-input>
                        </ion-item>
                        <ion-item>
                            <ion-input label="State" label-placement="floating" type="text"></ion-input>
                        </ion-item>
                        <ion-item>
                            <ion-input label="ZIP" label-placement="floating" type="number"></ion-input>
                        </ion-item>
                        <ion-button expand="block" @click="nextLevel">Sign Up</ion-button>
                    </ion-content>
                </div>

                <div class="ion-page" v-if="userType === 'driver'">
                    <ion-header>
                        <ion-toolbar color="primary">
                            <ion-title>Driver Form</ion-title>
                            <ion-buttons slot="end">
                                <ion-button @click="toggleModal('driver')">Close</ion-button>
                            </ion-buttons>
                        </ion-toolbar>
                    </ion-header>
                    <ion-content class="ion-padding" color="secondary">
                        <p class="tw-uppercase tw-mx-2 tw-mb-1">Personal Details</p>
                        <div>
                            <ion-item>
                                <ion-input label="First Name" label-placement="floating" type="text"></ion-input>
                            </ion-item>
                            <ion-item>
                                <ion-input label="Last Name" label-placement="floating" type="text"></ion-input>
                            </ion-item>
                            <ion-item>
                                <ion-input label="Address" label-placement="floating" type="text"></ion-input>
                            </ion-item>
                            <ion-item>
                                <ion-input label="Town/City" label-placement="floating" type="text"></ion-input>
                            </ion-item>
                            <ion-item>
                                <ion-input label="State" label-placement="floating" type="text"></ion-input>
                            </ion-item>
                            <ion-item>
                                <ion-input label="ZIP" label-placement="floating" type="number"></ion-input>
                            </ion-item>
                        </div>
                        <br />
                        <p class="tw-uppercase tw-mx-2 tw-mb-1">Vehicle Details</p>
                        <div>
                            <ion-item>
                                <ion-input label="Type" label-placement="floating" type="text"></ion-input>
                            </ion-item>
                            <ion-item>
                                <ion-input label="License No." label-placement="floating" type="number"></ion-input>
                            </ion-item>
                            <ion-item>
                                <ion-input label="Images" label-placement="floating" type="text"></ion-input>
                            </ion-item>
                        </div>
                        <ion-button expand="block" @click="nextLevel" color="tertiary">Continue</ion-button>
                    </ion-content>
                </div>
            </ion-modal>
        </ion-content>
    </ion-page>
</template>

<script lang="ts" setup>
import { IonPage, IonContent, IonButton, IonModal, IonHeader, IonToolbar, IonTitle, IonButtons, IonItem, IonLabel, IonRadioGroup, IonRadio } from '@ionic/vue';
import { ref } from 'vue';

const isOpen = ref(false);
const userType = ref('')

function toggleModal(user) {
    if (user === 'customer') {
        userType.value = 'customer';
        isOpen.value = !isOpen.value;
    } else if (user === 'driver') {
        userType.value = 'driver';
        isOpen.value = !isOpen.value;
    }
    
}

function nextLevel() {
    // Handle the selected level and close the modal
    console.log("Selected Access Level:", level.value);
    toggleModal();
}

</script>

<style scoped>
ion-content{
    --background: #250902;
    --height: 100vh;
}

ion-item {
    --background: #250902;
    --color: #ffffff;
}
</style>
