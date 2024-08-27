<template>
    <ion-page>
        <ion-content :fullscreen="true" class="ion-padding" color="primary">
            <h1>Driver Verification</h1>
            <form @submit.prevent="submitForm">
                <!-- Identity Verification -->
                <h2>Identity Verification</h2>
                <ion-list :inset="true">
                    <ion-item>
                        <ion-input v-model="identity.driverLicense" type="text" label="Driver's License"
                            required></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-input v-model="identity.proofOfId" type="text" label="Passport/National ID"
                            required></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-label position="floating">Selfie with ID</ion-label>
                        <input type="file" @change="handleFileUpload('selfie')" label="Selfie with ID" />
                    </ion-item>
                </ion-list>

                <!-- Vehicle Verification -->
                <h2>Vehicle Verification</h2>
                <ion-list :inset="true">
                    <ion-item>
                        <ion-input v-model="vehicle.registration" type="text" label="Vehicle Registration"
                            required></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-input v-model="vehicle.insurance" type="text" label="Proof of Insurance"
                            required></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-label position="floating">Vehicle Inspection Report</ion-label>
                        <input type="file" @change="handleFileUpload('inspectionReport')" />
                    </ion-item>
                    <ion-item>
                        <ion-label position="floating">Vehicle Photos</ion-label>
                        <input type="file" @change="handleFileUpload('vehiclePhotos')" multiple />
                    </ion-item>
                    <ion-item>
                        <ion-label position="floating">Vehicle Make and Model</ion-label>
                        <ion-input v-model="vehicle.makeModel" type="text" required></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-label position="floating">License Plate Number</ion-label>
                        <ion-input v-model="vehicle.licensePlate" type="text" required></ion-input>
                    </ion-item>
                </ion-list>

                <!-- Submit Button -->
                <ion-button expand="full" type="submit" color="secondary">Submit</ion-button>
            </form>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const identity = ref({
    driverLicense: '',
    proofOfId: '',
    selfie: null,
});

const vehicle = ref({
    registration: '',
    insurance: '',
    inspectionReport: null,
    vehiclePhotos: [],
    makeModel: '',
    licensePlate: '',
});

const handleFileUpload = (type: string) => (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (input.files) {
        if (type === 'selfie') {
            identity.value.selfie = input.files[0];
        } else if (type === 'inspectionReport') {
            vehicle.value.inspectionReport = input.files[0];
        } else if (type === 'vehiclePhotos') {
            vehicle.value.vehiclePhotos = Array.from(input.files);
        }
    }
};

const submitForm = () => {
    // Handle form submission
    console.log('Identity:', identity.value);
    console.log('Vehicle:', vehicle.value);

    // You might want to send the data to a server here
};
</script>

<style scoped></style>
