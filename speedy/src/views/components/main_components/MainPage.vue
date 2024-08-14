<template>
    <ion-page>
        <HeaderComponent title="Dashboard" buttonText="Profile" navigatePath="account" />
        <ContentComponent contentTitle="Admin Dashboard"
            contentText="A simple app to help you get started with Ionic and Vue." />
        <FooterComponent footerText="Footer" />
    </ion-page>
</template>

<script setup lang="ts">
import HeaderComponent from './HeaderComponent.vue';
import FooterComponent from './FooterComponent.vue';
import ContentComponent from './ContentComponent.vue';
</script>

<style>
ion-page {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

ion-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

#container {
    flex: 1;
}

ion-footer {
    margin-top: auto;
}
</style>
