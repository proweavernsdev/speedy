<template>
    <ion-content class="ion-padding">
        <div id="container">
            <strong>{{ contentTitle }}</strong>
            <p>{{ contentText }}</p>
        </div>
    </ion-content>
</template>

<script setup lang="ts">
import { IonContent } from '@ionic/vue';
import { defineProps } from 'vue';
const props = defineProps({
    contentTitle: String,
    contentText: String
});
</script>
