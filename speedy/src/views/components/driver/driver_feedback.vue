<template>
    <ion-page>
        <HeaderComponent title="" headerIcon="" backIcon="chevron-back-outline"
            navigatePathonRight="driver_others-settings" navigatePathonLeft="driver_others-settings" />

        <div class="ion-padding-horizontal tw-h-full" id="container">
            <ion-list>
                <ion-item v-for="(feedback, index) in feedbackList" :key="index">
                    <ion-avatar slot="start">
                        <img :src="feedback.riderPhoto" alt="Rider Photo" />
                    </ion-avatar>
                    <ion-label id="customer-feedback">
                        <h2 class="tw-font-medium tw-text-white">{{ feedback.riderName }}</h2>
                        <div class="tw-flex tw-justify-between">
                            <p class=" tw-text-white">{{ feedback.date }}</p>
                            <p class="tw-font-medium tw-text-white">{{ feedback.rating }} / 5</p>
                        </div>
                        <p class="tw-text-white">{{ feedback.comments }}</p>
                    </ion-label>
                </ion-item>
                <ion-item v-if="feedbackList.length === 0">
                    <ion-label class="tw-text-white">No feedback available.</ion-label>
                </ion-item>
            </ion-list>
        </div>
    </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { IonPage, IonList, IonItem, IonAvatar, IonLabel } from '@ionic/vue';
import HeaderComponent from "../main_components/HeaderComponent.vue";
// import { useRouter } from 'vue-router';

const feedbackList = ref([]);

onMounted(() => {
    fetchFeedback();
});

function fetchFeedback() {
    try {
        feedbackList.value = [
            {
                riderPhoto: 'https://via.placeholder.com/50',
                riderName: 'Mike Johnson',
                date: '2024-09-05',
                rating: 4,
                comments: 'The delivery was prompt and the rider was courteous.'
            },
            {
                riderPhoto: 'https://via.placeholder.com/50',
                riderName: 'Lisa Brown',
                date: '2024-09-04',
                rating: 5,
                comments: 'Excellent service, highly recommend!'
            }
        ];
    } catch (error) {
        console.error('Error fetching feedback:', error); // Log error for debugging
    }
}
</script>


<style scoped>
.ion-content-wrapper {
    height: 100%;
    flex: 1;
    overflow-y: auto;
    margin-bottom: 54px;
}

/* Add any custom styles here if needed */
ion-item {
    --padding-start: 0;
    --inner-padding-end: 0;
    --background: ;
}

ion-avatar {
    margin-right: 10px;
}

ion-list {
    background-color: #250902;
}

#customer-feedback {
    display: flex;
    gap: 0.25rem;
    flex-direction: column;
    margin-top: 1rem;
    margin-bottom: 1rem;
}
</style>
