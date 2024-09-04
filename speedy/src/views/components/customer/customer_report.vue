<template>
    <ion-page>
        <HeaderComponent title="" headerIcon="" backIcon="chevron-back-outline"
            navigatePathonRight="customer_others-settings" navigatePathonLeft="customer_others-settings" />

        <div class="ion-padding-horizontal tw-h-full" id="container">
            <h1 class="tw-text-3xl tw-font-semibold tw-text-white tw-my-4">Report an issue</h1>
            <form @submit.prevent="submitReport">
                <ion-item color="secondary" class="tw-rounded-t-lg">
                    <ion-select label="Issue Type" v-model="issueType" placeholder="Select an issue">
                        <ion-select-option value="delivery">Delivery Issue</ion-select-option>
                        <ion-select-option value="rider">Rider Issue</ion-select-option>
                        <ion-select-option value="other">Other</ion-select-option>
                    </ion-select>
                </ion-item>

                <ion-item color="secondary" class="tw-rounded-b-lg">
                    <ion-textarea label="Details" v-model="details"
                        placeholder="Details about the issue"></ion-textarea>
                </ion-item>

                <!-- <ion-item color="secondary" id="upload">
                    <input type="file" @change="handleFileUpload" />
                </ion-item> -->
                <ion-button expand="full" type="submit" class="mt-4" color="secondary" shape="round" mode="ios">Submit
                    Report</ion-button>
            </form>
        </div>
        <ion-toast :is-open="showToastSuccess" :message="toastMessage" duration="1250" position="bottom" color="success"
            @did-dismiss="showToastSuccess = false" />
        <ion-toast :is-open="showToastError" :message="toastMessage" duration="1250" position="bottom" color="danger"
            @did-dismiss="showToastError = false" />
        <ion-toast :is-open="showToastWarning" :message="toastMessage" duration="2000" position="bottom" color="warning"
            @did-dismiss="showToastWarning = false" />
    </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import HeaderComponent from "../main_components/HeaderComponent.vue";
import { IonPage, IonItem, IonSelect, IonSelectOption, IonTextarea, IonButton, IonToast } from "@ionic/vue";
import { Preferences } from '@capacitor/preferences';
import { Filesystem, Directory } from '@capacitor/filesystem';

const router = useRouter();
const PHOTO_STORAGE = 'photos'; // Key for storing photo paths

const issueType = ref('');
const details = ref('');
const file = ref<File | null>(null);

const showToastSuccess = ref(false);
const showToastError = ref(false);
const showToastWarning = ref(false);
const toastMessage = ref('');

const photos = ref<{ filepath: string; webviewPath?: string }[]>([]);

function goBack() {
    router.go(-1);
}

function handleFileUpload(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        file.value = target.files[0];
        // Assuming you save the photo to the filesystem here
        savePhotoToFilesystem(file.value);
    }
}

async function savePhotoToFilesystem(photo: File) {
    try {
        const fileName = new Date().getTime() + '.jpg'; // Unique file name
        const filePath = `photos/${fileName}`;

        // Save the file to the filesystem
        await Filesystem.writeFile({
            path: filePath,
            data: await fileToBase64(photo),
            directory: Directory.Data,
            encoding: 'base64'
        });

        // Save the photo pointer to Preferences
        const savedPhotos = await loadSavedPhotos();
        savedPhotos.push({ filepath: filePath });
        await Preferences.set({ key: PHOTO_STORAGE, value: JSON.stringify(savedPhotos) });

        // Update the photo list in the component
        photos.value = savedPhotos;
    } catch (error) {
        console.error('Failed to save photo', error);
    }
}

async function loadSavedPhotos() {
    const { value } = await Preferences.get({ key: PHOTO_STORAGE });
    return value ? JSON.parse(value) : [];
}

async function submitReport() {
    if (!issueType.value || !details.value) {
        toastMessage.value = 'Please fill in all required fields.';
        showToastWarning.value = true;
        return;
    }

    // const reportData = {
    //     issueType: issueType.value,
    //     details: details.value,
    //     photos: photos.value
    // };

    try {
        // Example of how you might handle the report submission
        // await axios.post('/api/report', reportData);

        toastMessage.value = 'Report submitted successfully.';
        showToastSuccess.value = true;

        goBack();
    } catch (error) {
        toastMessage.value = 'Failed to submit report. Please try again.';
        showToastError.value = true;
    }
}

function fileToBase64(file: File): Promise<string> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => {
            resolve(reader.result as string);
        };
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

async function loadPhotos() {
    try {
        const savedPhotos = await loadSavedPhotos();
        for (let photo of savedPhotos) {
            const file = await Filesystem.readFile({
                path: photo.filepath,
                directory: Directory.Data,
            });
            photo.webviewPath = `data:image/jpeg;base64,${file.data}`;
        }
        photos.value = savedPhotos;
    } catch (error) {
        console.error('Failed to load photos', error);
    }
}

onMounted(() => {
    loadPhotos();
});
</script>

<style scoped>
ion-item,
ion-label {
    font-family: "Moderustic", sans-serif;
}

#upload {
    --display: flex;
    --flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
}
</style>
