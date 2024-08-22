<template>
    <ion-page>
        <HeaderComponent title="Account" headerIcon="person-circle" backIcon="chevron-back-outline"
            navigatePathonRight="customer_account" navigatePathonLeft="customer_dashboard" />
        <div class="ion-padding" id="account_container">
            <div v-show="!isEditing">
                <h2>Account Management</h2>
                <div class="account-info">
                    <p><strong>First Name:</strong> {{ user.firstName }}</p>
                    <p><strong>Last Name:</strong> {{ user.lastName }}</p>
                    <p><strong>Address:</strong> {{ user.address }}</p>
                    <p><strong>State:</strong> {{ user.state }}</p>
                    <p><strong>Town / City:</strong> {{ user.city }}</p>
                    <p><strong>ZIP:</strong> {{ user.zip }}</p>
                </div>
                <div class="tw-flex tw-justify-between tw-gap-3">
                    <ion-button expand="full" color="primary" @click="toggleEditMode"
                        class="tw-w-full tw-rounded-lg">Edit</ion-button>
                    <ion-button expand="full" color="danger" @click="confirmDelete" class="tw-w-full">Delete
                        Account</ion-button>
                </div>
            </div>

            <!-- Edit Mode -->
            <ion-div v-if="isEditing">
                <h6 class="tw-flex tw-justify-start tw-text-gray-500">Edit
                    Information</h6>
                <div>
                    <ion-input v-model="editUser.firstName" placeholder="First Name"
                        class="tw-border ion-padding-horizontal"></ion-input>
                    <ion-input v-model="editUser.lastName" placeholder="Last Name"
                        class="tw-border ion-padding-horizontal"></ion-input>
                    <ion-input v-model="editUser.address" placeholder="Address"
                        class="tw-border ion-padding-horizontal"></ion-input>
                    <ion-input v-model="editUser.state" placeholder="State"
                        class="tw-border ion-padding-horizontal"></ion-input>
                    <ion-input v-model="editUser.city" placeholder="Town / City"
                        class="tw-border ion-padding-horizontal"></ion-input>
                    <ion-input v-model="editUser.zip" placeholder="ZIP"
                        class="tw-border ion-padding-horizontal"></ion-input>
                    <ion-button expand="full" shape="round" @click="saveChanges">Save</ion-button>
                    <ion-button expand="full" shape="round" color="light" @click="toggleEditMode">Cancel</ion-button>
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
    margin-bottom: 36px;
}

ion-input {
    border-radius: 1rem;
}

ion-button {
    --border-radius: 10px;
}

.account-info {
    margin-bottom: 20px;
}

ion-input {
    margin-top: 0.5rem;
}
</style>
