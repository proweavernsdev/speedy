<template>
    <ion-page>
        <HeaderComponent title="" headerIcon="" backIcon="chevron-back-outline" navigatePathonRight="customer_account"
            navigatePathonLeft="customer_dashboard" />
        <div class="ion-padding-horizontal" id="account_container">
            <div v-show="!isEditing">

                <div class="tw-flex tw-justify-center tw-items-center">
                    <div class="tw-rounded-full tw-border-4 tw-border-white tw-flex tw-justify-center tw-items-center">
                        <ion-icon name="person-circle-outline" class="tw-text-8xl tw-text-white"></ion-icon>
                    </div>
                </div>

                <div class="tw-flex tw-justify-between tw-items-center">
                    <h5 class=" tw-text-white tw-px-3">Profile</h5>
                    <ion-button @click="toggleEditMode" class="ion-text-end">Edit</ion-button>
                </div>

                <div class="tw-bg-white tw-rounded-md ion-margin-bottom">
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">First Name</ion-label>
                        <ion-input type="text" class="tw-w-1/3 ion-text-end" v-model="editUser.firstName"
                            placeholder="First Name"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">Last Name</ion-label>
                        <ion-input type="text" class="tw-w-1/3 ion-text-end" v-model="editUser.lastName"
                            placeholder="Last Name"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">Address</ion-label>
                        <ion-input type="text" class="tw-w-1/3 ion-text-end" v-model="editUser.address"
                            placeholder="Address"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">State</ion-label>
                        <ion-input type="text" class="tw-w-1/3 ion-text-end" v-model="editUser.state"
                            placeholder="State"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3 tw-border-b">
                        <ion-label class="text-label tw-w-1/2">Town / City</ion-label>
                        <ion-input type="text" class="tw-w-1/3 ion-text-end" v-model="editUser.city"
                            placeholder="Town / City"></ion-input>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center tw-w-full tw-px-3">
                        <ion-label class="text-label tw-w-1/2">ZIP</ion-label>
                        <ion-input type="text" class="tw-w-1/3 ion-text-end" v-model="editUser.zip"
                            placeholder="ZIP"></ion-input>
                    </div>
                </div>
                <ion-button expand="full" mode="ios" shape="round" color="secondary" @click="confirmDelete"
                    class="ion-margin-vertical">Delete Account</ion-button>
            </div>

            <!-- Edit Mode -->
            <ion-div v-if="isEditing">
                <h6 class="tw-flex tw-justify-start tw-text-gray-500">Edit
                    Information</h6>
                <div>
                    <ion-input v-model="editUser.firstName" placeholder="First Name"
                        class="tw-border ion-padding-horizontal tw-bg-white"></ion-input>
                    <ion-input v-model="editUser.lastName" placeholder="Last Name"
                        class="tw-border ion-padding-horizontal tw-bg-white"></ion-input>
                    <ion-input v-model="editUser.address" placeholder="Address"
                        class="tw-border ion-padding-horizontal tw-bg-white"></ion-input>
                    <ion-input v-model="editUser.state" placeholder="State"
                        class="tw-border ion-padding-horizontal tw-bg-white"></ion-input>
                    <ion-input v-model="editUser.city" placeholder="Town / City"
                        class="tw-border ion-padding-horizontal tw-bg-white"></ion-input>
                    <ion-input v-model="editUser.zip" placeholder="ZIP"
                        class="tw-border ion-padding-horizontal tw-bg-white"></ion-input>
                    <div class="tw-my-4 tw-flex tw-flex-col tw-gap-2">
                        <ion-button expand="full" shape="round" color="secondary" @click="saveChanges">Save</ion-button>
                        <ion-button expand="full" shape="round" color="light"
                            @click="toggleEditMode">Cancel</ion-button>
                    </div>
                </div>
            </ion-div>
        </div>
    </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { IonPage, IonButton, IonInput } from "@ionic/vue";
import HeaderComponent from "../main_components/HeaderComponent.vue";

// User information
const user = ref({
    firstName: 'John',
    lastName: 'Doe',
    address: '123 Main St',
    state: 'NY',
    city: 'New York',
    zip: '10001'
});

const isEditing = ref(false);
const editUser = ref({ ...user.value });

const toggleEditMode = () => {
    isEditing.value = !isEditing.value;
    if (!isEditing.value) {
        editUser.value = { ...user.value };
    }
};

const saveChanges = () => {
    user.value = { ...editUser.value };
    toggleEditMode();
};

const confirmDelete = () => {
    if (confirm('Are you sure you want to delete your account?')) {
        // Logic to delete the account
    }
};
</script>

<style scoped>
ion-page {
    display: flex;
    flex-direction: column;
    height: 100vh;
    justify-content: normal;
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

ion-input {
    border-radius: 1rem;
}

ion-button {
    --border-radius: 10px;
    margin: 0px;
}

.account-info {
    margin-bottom: 20px;
}

ion-input {
    margin-top: 0.5rem;
}
</style>
