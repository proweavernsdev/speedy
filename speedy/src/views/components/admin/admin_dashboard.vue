<template>
    <ion-page>
        <HeaderComponent title="Dashboard" headerIcon="person-circle" backIcon="construct-outline"
            navigatePathonRight="account_management" navigatePathonLeft="admin_dashboard" />
        <ContentComponent contentTitle="Admin Dashboard"
            contentText="Welcome to the Admin Dashboard. Choose an option from the footer to manage your application." />
        <FooterComponent :button1Text="'Dashboard'" :button1Link="'admin_dashboard'" :button1Icon="'home'"
            :button2Text="'Users'" :button2Link="'users'" :button2Icon="'people'" :button3Text="'Custom'"
            :button3Link="'customization'" :button3Icon="'options'" :button4Text="'Settings'" :button4Link="'settings'"
            :button4Icon="'settings'" />
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage } from '@ionic/vue';
import HeaderComponent from '../main_components/HeaderComponent.vue';
import FooterComponent from '../main_components/FooterComponent.vue';
import ContentComponent from '../main_components/ContentComponent.vue';
</script>

<style scoped>
ion-page {
    display: flex;
    flex-direction: column;
    height: 100vh;
}
</style>
