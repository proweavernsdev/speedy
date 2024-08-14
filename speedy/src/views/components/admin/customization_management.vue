<template>
    <ion-page>
        <HeaderComponent title="" headerIcon="person-circle" navigatePath="admin_dashboard" />
        <ContentComponent contentTitle="Customization Management"
            contentText="Manage your application settings here. You can edit, delete, and add settings." />
        <FooterComponent :button1Text="'Dashboard'" :button1Link="'admin_dashboard'" :button1Icon="'home'"
            :button2Text="'Users'" :button2Link="'users'" :button2Icon="'people'" :button3Text="'Custom'"
            :button3Link="'customization'" :button3Icon="'options'" :button4Text="'Settings'" :button4Link="'settings'"
            :button4Icon="'settings'" />
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage } from '@ionic/vue';
import HeaderComponent from '../main_components/HeaderComponent.vue';
import ContentComponent from '../main_components/ContentComponent.vue';
import FooterComponent from '../main_components/FooterComponent.vue';
</script>

<style></style>
