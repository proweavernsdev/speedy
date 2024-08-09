<template>

    <ion-page>
        <ion-content :fullscreen="true">
            <ion-header>
                <ion-toolbar>
                    <ion-title>Driver Dashboard</ion-title>
                </ion-toolbar>
            </ion-header>
            <ion-content class="ion-padding">
                <div id="container">
                    <strong>Driver Dashboard</strong>
                    <p>
                        A simple app to help you get started with Ionic and Vue.
                    </p>
                </div>
            </ion-content>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonHeader, IonToolbar, IonTitle } from '@ionic/vue';
</script>

<style></style>