<template>
    <ion-page>
        <ion-content :fullscreen="true">
            <ion-header>
                <ion-toolbar>
                    <ion-title>Test Page</ion-title>
                </ion-toolbar>
            </ion-header>
            <ion-content class="ion-padding">
                <div id="container">
                    <div id="content">
                        <p>Test Page</p>
                    </div>
                    <div>
                        <ion-icon name="heart"></ion-icon>
                    </div>
                </div>
            </ion-content>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage, IonContent, IonHeader, IonToolbar, IonTitle, IonIcon } from '@ionic/vue';
</script>

<style></style>
