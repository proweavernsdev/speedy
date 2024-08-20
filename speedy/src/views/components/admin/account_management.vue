<template>
    <ion-page>
        <HeaderComponent title="Account" headerIcon="person-circle" backIcon="chevron-back"
            navigatePathonRight="account_management" navigatePathonLeft="admin_dashboard" />
        <ContentComponent contentTitle="Account Management"
            contentText="Manage your account details and settings here."/>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage } from '@ionic/vue';
import HeaderComponent from '../main_components/HeaderComponent.vue';
import ContentComponent from '../main_components/ContentComponent.vue';
</script>

<style scoped>
ion-page {
    display: flex;
    flex-direction: column;
    height: 100vh;
}
</style>
