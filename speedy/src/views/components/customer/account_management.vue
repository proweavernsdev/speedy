<template>
    <ion-page>
        <HeaderComponent title="Account" headerIcon="person-circle" backIcon="chevron-back-outline"
            navigatePathonRight="customer_account" navigatePathonLeft="customer_dashboard" />
            <div class="ion-padding" id="account_container">
                <h1>Account Management</h1>
            </div>
    </ion-page>
</template>

<script setup lang="ts">
import { IonPage } from "@ionic/vue";
import HeaderComponent from "../main_components/HeaderComponent.vue";
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
}
</style>
